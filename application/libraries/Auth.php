<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('user_model', '', TRUE);
    }

    //check user login
    public function checkUserLogin()
    {
        if (!$this->CI->session->logged_in)
            redirect('login', 'refresh');
        else
            return true;
    }

    //get user id
    public function getUserId()
    {
        if ($this->CI->session->logged_in) {
            $session_data = $this->CI->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            return $user_id;
        }
    }

    //get user name
    public function getUserName()
    {
        if ($this->CI->session->logged_in) {
            $session_data = $this->CI->session->userdata('logged_in');
            return $user_name = $session_data['user_name'];
        }
    }

    //get user role id
    public function getUserRoleId()
    {
        if ($this->CI->session->logged_in) {
            $session_data = $this->CI->session->userdata('logged_in');
            return $role_id = $session_data['role_id'];
        }
    }

    //get user permission
    public function getUserPermissions($role_id)
    {
        $user_roles = $this->CI->user_model->getUserPermission($role_id);
        if ($user_roles)
            $data = $this->hasPermission($user_roles);

        return $data;
    }

    //check user has permission
    public function hasPermission($user_roles)
    {
        foreach ($user_roles as $row) {
            $data[$row->permission] = $row->permission;
        }
        return $data;
    }

    //logout user
    public function logoutUser()
    {
        $this->CI->session->unset_userdata('logged_in');
        session_destroy();
        redirect('login', 'refresh');
    }

    public function otpSettings() {
        return true;
    }

    public function otpUserData() {
        return $data = array(
            'user' => '94767133716',
            'password' => '1077',
            'url' => 'http://www.textit.biz/sendmsg'
        );
    }
}
