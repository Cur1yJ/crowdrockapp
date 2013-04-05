<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Crowdrock_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
        $this->load->helper('html');
                $this->load->library('user_agent');
                $this->current_user = null;

                if($this->agent->is_mobile()){
                    $this->session->set_userdata('browser_client', 'mobile');
                } else {
                    $this->session->set_userdata('browser_client', '');
                    #$this->session->set_userdata('browser_client', 'mobile');
                }
	}

        public function authenticate(){
            if($this->ion_auth->logged_in()){
                return true;
            }
            else{
                $this->session->set_userdata('return_to', $_SERVER['REQUEST_URI']);
                if($this->agent->is_mobile()){
                    redirect("auth/login_mobile", 'refresh');
                }else{
                    redirect("auth/login", 'refresh');
                    #redirect("auth/login_mobile", 'refresh');
                }
                return false;
            }
        }

        public function current_user(){
            if($this->current_user == null)
                $this->current_user = User::find($this->ion_auth->user()->row()->id);
            return $this->current_user;
        }

        public function is_mobile(){
            return (array_key_exists('client', $_GET) && $_GET['client'] != null && $_GET['client'] == 'mobile' || 'mobile' == $this->session->userdata('browser_client'));
        }

        function view_path($path){
            if($this->is_mobile()){
                return $path . "_mobile";
            } else {
                return $path;
            }
        }
}
