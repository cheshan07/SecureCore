<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserPermission($role_id)
    {
        $this->db->where('role_id', $role_id);
        $this->db->join('permissions', 'permissions.permission_id = user_permissions.permission_id');
        $query = $this->db->get('user_permissions');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkExisting($email)
    {
        $this->db->where('email', $email);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkExistingEdit($id, $email)
    {
        $this->db->where('email', $email);
        $this->db->where('user_id !=', $id);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function add($data)
    {
        $this->db->insert('users', $data);
        $this->db->limit(1);
        return true;
    }

    public function edit($id, $data)
    {
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);
        $this->db->limit(1);
        return true;
    }

    public function delete($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete('users');
        $this->db->limit(1);
        return true;
    }

    public function get_pass($email)
    {
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function login($email)
    {
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function update_otp($email, $data)
    {
        $this->db->where('email', $email);
        $this->db->update('users', $data);
        $this->db->limit(1);
        return true;
    }

    public function check_otp($email, $otp)
    {
        $this->db->where('email', $email);
        $this->db->where('otp', $otp);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function check_report_otp($p_id, $otp)
    {
        $this->db->where('user_id', $p_id);
        $this->db->where('report_otp', $otp);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_user($user_id)
    {
        $this->db->join('roles', 'roles.role_id = users.role_id');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_users($order_field, $order_type, $role_id = null, $patient_id = null, $patient_name = null)
    {
        $this->db->order_by($order_field, $order_type);
        $this->db->join('roles', 'roles.role_id = users.role_id');
        if ($role_id != null)
            $this->db->where('users.role_id', $role_id);

        if ($patient_id != null)
            $this->db->like('user_nic', $patient_id);

        if ($patient_name != null) {
            $this->db->like('first_name', $patient_name);
            $this->db->or_like('last_name', $patient_name);
        }

        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function register_verify($email, $verify_code)
    {
        $this->db->where('email', $email);
        $this->db->where('verify_code', $verify_code);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function update_user($email, $data)
    {
        $this->db->where('email', $email);
        $this->db->update('users', $data);
        $this->db->limit(1);
        return true;
    }

    public function password_reset_verify($email, $password_reset_code)
    {
        $this->db->where('email', $email);
        $this->db->where('password_reset_code', $password_reset_code);
        $this->db->limit(1);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_roles()
    {
        $query = $this->db->get('roles');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}