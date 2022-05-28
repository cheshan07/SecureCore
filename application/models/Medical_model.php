<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_reports($order_field, $order_type, $user_id = null)
    {
        $this->db->order_by($order_field, $order_type);
        $this->db->join('users', 'users.user_id = medical_records.patient_id');
        if($user_id != null) {
            $this->db->where('medical_records.patient_id', $user_id);
        }
        $query = $this->db->get('medical_records');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_report($record_id)
    {
        $this->db->join('users', 'users.user_id = medical_records.patient_id');
        $this->db->where('medical_records.record_id', $record_id);
        $this->db->limit(1);
        $query = $this->db->get('medical_records');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkExistingEdit($id, $report_name)
    {
        $this->db->where('report_name', $report_name);
        $this->db->where('record_id !=', $id);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('medical_records');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkExisting($report_name)
    {
        $this->db->where('report_name', $report_name);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('medical_records');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function add($data)
    {
        $this->db->insert('medical_records', $data);
        $this->db->limit(1);
        return true;
    }

    public function edit($id, $data)
    {
        $this->db->where('record_id', $id);
        $this->db->update('medical_records', $data);
        $this->db->limit(1);
        return true;
    }

    public function delete($id)
    {
        $this->db->where('record_id', $id);
        $this->db->delete('medical_records');
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
}