<?php

class Party extends ActiveRecord\Model {

    static $belongs_to = array(array('organizer'));
    static $has_many = array(array('party_songs', 'class_name' => 'PartySong', 'foreign_key' => 'party_id', 'order' => 'up_vote_count desc'));
    static $validates_presence_of = array(array('title'));
    static $after_create = array('generate_access_token');
    static $after_destroy = array('delete_all_related_data');

    public function generate_access_token() {
        $datetime = new DateTime();
        //$token = "" . $this->id . "-" . $datetime->getTimestamp() . "-" . rand(1000, 9999);
        $token = "" . $datetime->getTimestamp() . "" . rand(1000, 9999) . "-" . str_replace(" ", "-", url_title($this->title));
        $this->update_attribute("access_token", $token);
    }

    public function email_party_link($email_addresses) {
        $ci = &get_instance();
        $ci->load->library('email');
        $public_url = $this->public_url();

        foreach ($email_addresses as $email_address) {
            $ci->email->from('party@crowdrockapp.com', 'CrowdRock');
            $ci->email->to(trim($email_address));
            //$ci->email->bcc('admin@crowdrock.com');

            $ci->email->subject("CrowdRock - " . $this->title);
            $ci->email->message($public_url);

            $ci->email->send();

            //echo $ci->email->print_debugger();
        }
    }

    public function public_url() {
        $ci = &get_instance();
        //return $ci->config->item('base_url') . '/partysongs/front_end/' . $this->access_token;
        return $ci->config->item('base_url') . '/' . str_replace(" ", "-", $this->organizer->user->username) . '/parties/' . $this->access_token;
    }

    public function last_minute_request_summary($request_type, $minutes = 10, $limit = 10) {
        $add_minute = '0:' . $minutes . ':0';
        $request_summary_query = "SELECT party_song_id, request_type, count(*) as request_count FROM participant_requests
        where created_at > ADDTIME((select max(created_at) from participant_requests), '-$add_minute') and request_type = '$request_type' and party_id = $this->id
        group by request_type, party_song_id
        order by request_count desc
        limit $limit";
        //echo $request_summary_query;

        $request_summary = PartySong::find_by_sql($request_summary_query);
        return $request_summary;
    }

    public function update_song_ranking() {
        $up_vote_summary = $this->last_minute_request_summary(ParticipantRequest::$TYPE_UP_VOTE, 59);
        $up_vote_summary = PartySong::find_by_sql("SELECT party_song_id, request_type, count(*) as request_count FROM participant_requests
        where request_type = '". ParticipantRequest::$TYPE_UP_VOTE . "' and party_id = $this->id group by request_type, party_song_id order by request_count desc");
        
        $down_vote_summary = $this->last_minute_request_summary(ParticipantRequest::$TYPE_DOWN_VOTE, 59);
        $down_vote_summary = PartySong::find_by_sql("SELECT party_song_id, request_type, count(*) as request_count FROM participant_requests
        where request_type = '". ParticipantRequest::$TYPE_DOWN_VOTE . "' and party_id = $this->id group by request_type, party_song_id order by request_count desc");
        $ranking_summary = array();

        foreach ($up_vote_summary as $up_vote_info) {
            $ranking_summary['' . $up_vote_info->party_song_id] = array('up_vote_count' => $up_vote_info->request_count,
                'party_song_id' => $up_vote_info->party_song_id, 'party_song' => null, 'down_vote_count' => 0);
        }

        foreach ($down_vote_summary as $down_vote_info) {
            $up_vote_count = array_key_exists('' . $down_vote_info->party_song_id, $ranking_summary) ? $ranking_summary['' . $down_vote_info->party_song_id]['up_vote_count'] : 0;

            $ranking_summary['' . $down_vote_info->party_song_id] = array('up_vote_count' => $up_vote_count,
                'party_song_id' => $down_vote_info->party_song_id, 'party_song' => null, 'down_vote_count' => $down_vote_info->request_count);
        }

        $party_song_ids = array();
        foreach ($ranking_summary as $key => $ranking_info) {
            //$ranking_summary[$key]['rank'] = ($ranking_info['up_vote_count'] - $ranking_summary[$key]['down_vote_count']) / ($ranking_summary[$key]['up_vote_count'] + $ranking_summary[$key]['down_vote_count']);
            $down_vote_count = $ranking_summary[$key]['down_vote_count'] == 0 ? 0 : $ranking_summary[$key]['down_vote_count'];
            $ranking_summary[$key]['rank'] = ($ranking_info['up_vote_count'] - $down_vote_count);
            array_push($party_song_ids, $ranking_summary[$key]['party_song_id']);
        }
//        print_r($up_vote_summary);
//        echo "<br/>====<br/>";
//        print_r($down_vote_summary);
//        echo "<br/>====<br/>";
//        print_r($ranking_summary);
//        echo "<br/>====<br/>";
        if (sizeof($party_song_ids) == 0)
            return array();

        $ci = &get_instance();
        $ci->db->query('update party_songs set rank = 0');

        $party_songs = PartySong::find($party_song_ids);
        if (gettype($party_songs) == "object")
            $party_songs = array($party_songs);

        foreach ($party_songs as $party_song) {
            $ranking_summary[$party_song->id]['party_song'] = $party_song;
            $party_song->update_attribute('rank', $ranking_summary[$party_song->id]['rank']);
        }

        return $ranking_summary;
    }

    function popular_songs() {
        return PartySong::find('all', array('conditions' => 'party_id = ' . $this->id . ' ',
            'order' => 'rank desc, up_vote_count desc, down_vote_count asc', 'limit' => 5));
    }

    function delete_all_related_data() {
        $party_songs = PartySong::find('all', array('conditions' => array('party_id' => $this->id)));
        foreach ($party_songs as $party_song) {
            $party_song->delete();
        }

        $points = Point::find('all', array('conditions' => array('party_id' => $this->id)));
        foreach ($points as $point) {
            $point->delete();
        }

        $participant_requests = ParticipantRequest::find('all', array('conditions' => array('party_id' => $this->id)));
        foreach ($participant_requests as $participant_request) {
            $participant_request->delete();
        }
    }

    function leader_info() {
        $highest_party_point = Point::first(array('conditions' => array('party_id = ? and user_id != ?', $this->id, User::anonymous()->id), 'group' => 'user_id', 'order' => 'total_points desc', 'select' => 'user_id, sum(point) as total_points'));
        if ($highest_party_point == null || $highest_party_point->user_id == null) {
            return array('user_id' => null, 'total_party_points' => 0, 'up_vote_count' => 0, 'down_vote_count' => 0, 'vote_count' => 0, 'total_earned_points' => 0, 'total_votes' => 0);
        }
        log_message('error', 'user id: ' . $highest_party_point->user_id);

        $party_leader_info = array();
        $party_leader_info['user_id'] = $highest_party_point->user_id;
        $party_leader_info['total_party_points'] = $highest_party_point->total_points;
        $party_leader_info['up_vote_count'] = ParticipantRequest::first(array('conditions' => array('party_id = ? and user_id = ? and request_type = ?', $this->id, $highest_party_point->user_id, ParticipantRequest::$TYPE_UP_VOTE), 'order' => 'total_votes', 'select' => 'user_id, count(request_type) as total_votes'))->total_votes;
        $party_leader_info['down_vote_count'] = ParticipantRequest::first(array('conditions' => array('party_id = ? and user_id = ? and request_type = ?', $this->id, $highest_party_point->user_id, ParticipantRequest::$TYPE_DOWN_VOTE), 'order' => 'total_votes', 'select' => 'user_id, count(request_type) as total_votes'))->total_votes;
        $party_leader_info['vote_count'] = ParticipantRequest::first(array('conditions' => array('party_id = ? and user_id = ?', $this->id, $highest_party_point->user_id), 'order' => 'total_votes', 'select' => 'user_id, count(request_type) as total_votes'))->total_votes;
        $party_leader_info['total_earned_points'] = Point::first(array('group' => 'user_id', 'order' => 'total_points desc', 'select' => 'user_id, sum(point) as total_points'))->total_points;

        return $party_leader_info;
    }

    function leader_points() {
        $leader_points = Point::find('all', array('conditions' => array('party_id = ? and user_id != ?', $this->id, User::anonymous()->id),
            'group' => 'user_id',
            'order' => 'total_points desc',
            'select' => 'user_id, sum(point) as total_points, users.first_name as first_name, users.last_name as last_name, users.email as email',
            'joins' => 'inner join users on users.id = user_id', 
            'limit' => 10));
        
        return $leader_points;
    }

    public static function nearby($latitude, $longitude, $distance=2 /* in km*/){
        $sql = "SELECT *, (3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude - parties.latitude) *  pi()/180 / 2), 2) +COS($latitude * pi()/180) * COS(parties.latitude * pi()/180) * POWER(SIN(($longitude-parties.longitude) * pi()/180 / 2), 2) ))) as distance from parties having distance < $distance ORDER BY distance;";
        return Party::find_by_sql($sql);
    }

    public function stop(){
        foreach ($this->party_songs as $party_song) {
            if($party_song->up_vote_count / ($party_song->up_vote_count + down_vote_count) > .5){
                foreach ($party_song->up_vote_requests() as $up_vote_requests) {
                    #TODO:: Add user points
                }
            }
        }
    }

}
?>
