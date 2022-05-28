<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_members($order_field, $order_type, $user_id = null)
    {
        $this->db->order_by($order_field, $order_type);
        $this->db->join('roles', 'roles.role_id = members.role_id');
        //$this->db->join('users', 'users.user_id = members.user_id');
        if($user_id != null) {
            $this->db->like('members.user_id', $user_id);
            $this->db->or_like('members.user_nic', $user_id);
        }
        $query = $this->db->get('members');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_member($member_id)
    {
        $this->db->join('roles', 'roles.role_id = members.role_id');
        //$this->db->join('users', 'users.user_id = members.user_id');
        $this->db->where('members.member_id', $member_id);
        $this->db->limit(1);
        $query = $this->db->get('members');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkExistingEdit($id, $nic)
    {
        $this->db->where('nic', $nic);
        $this->db->where('member_id !=', $id);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('members');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkExisting($nic)
    {
        $this->db->where('nic', $nic);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('members');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function add($data)
    {
        $this->db->insert('members', $data);
        $this->db->limit(1);
        return true;
    }

    public function edit($id, $data)
    {
        $this->db->where('member_id', $id);
        $this->db->update('members', $data);
        $this->db->limit(1);
        return true;
    }

    public function delete($id)
    {
        $this->db->where('member_id', $id);
        $this->db->delete('members');
        $this->db->limit(1);
        return true;
    }

    public function update_otp($id, $data)
    {
        $this->db->where('member_id', $id);
        $this->db->update('members', $data);
        $this->db->limit(1);
        return true;
    }

    public function emergencyAccess($otp, $data)
    {
        $this->db->where('otp', $otp);
        $this->db->update('members', $data);
        $this->db->limit(1);
        return true;
    }

    public function check_otp($nic, $otp)
    {
        $this->db->join('users', 'users.user_id = members.user_id');
        $this->db->select('members.user_id, users.email, members.role_id, members.otp_expire_time');
        $this->db->where('nic', $nic);
        $this->db->where('members.otp', $otp);
        $this->db->limit(1);
        $query = $this->db->get('members');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkExistingOtp($otp)
    {
        $this->db->where('otp', $otp);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('members');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}