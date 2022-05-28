<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller
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
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_roles'] = 'active';
        $data['roles'] = $this->user_model->get_roles();

        $this->load->view('web/header_view');
        if(isset($this->data['view_roles'])){
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/roles_view', $data);
        }
        $this->load->view('web/footer_view');
    }

}