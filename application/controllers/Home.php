<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('auth');
        //check user login
        $this->auth->checkUserLogin();
        //get user id
        $this->user_id = $this->auth->getUserId();
        //get user id
        $this->role_id = $this->auth->getUserRoleId();
        //get user role
        $this->data = $this->auth->getUserPermissions($this->role_id);

        $this->load->model('user_model', '', TRUE);
    }

    public function index()
    {
        $data['li_dashboard'] = 'active';
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);
        $users = $this->user_model->get_users('user_id', 'asc');
        $totalUsers = 0;
        $totalDoctors = 0;
        $totalPatients = 0;
        if($users != null) {
            foreach($users as $row) {
                if($row->status == 1) {
                    if($row->role_id == 1 || $row->role_id == 4) {
                        $totalUsers++;
                    } else if($row->role_id == 2) {
                        $totalPatients++;
                    } else if($row->role_id == 3) {
                        $totalDoctors++;
                    }
                }
            }
        }
        $data['totalUsers'] = $totalUsers;
        $data['totalPatients'] = $totalPatients;
        $data['totalDoctors'] = $totalDoctors;

        if($this->role_id == 2) {
            $url = base_url();
            redirect($url . 'patients/dashboard?id=' . $this->user_id, 'refresh');
        }
        $this->load->view('web/header_view');
        if(isset($this->data['view_dashboard'])){
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/home_view', $data);
        }
        $this->load->view('web/footer_view');
    }

    public function log_out() {
        $this->auth->logoutUser();
    }

}