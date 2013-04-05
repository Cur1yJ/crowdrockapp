<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends Crowdrock_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->data['message'] = "";
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect("auth/login", 'refresh');
            if ($this->is_mobile())
                redirect("/parties/upcoming", 'refresh');
            else
                $this->load->view('welcome_message', $this->data);
            return;
        }

        if ($this->ion_auth->is_admin())
            redirect("auth/index", 'refresh');
        else {
            if ($this->is_mobile())
                redirect("/parties/upcoming", 'refresh');
            else
                redirect("parties", 'refresh');
        }
        //$this->load->view('welcome_message');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */