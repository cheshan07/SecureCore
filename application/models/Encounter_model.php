<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encounter_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_encounters($order_field, $order_type, $patient_id = null, $doctor_id = null)
    {
        $this->db->order_by($order_field, $order_type);
        if ($patient_id != null)
            $this->db->where('patient_id', $patient_id);
        if ($doctor_id != null)
            $this->db->where('doctor_id', $doctor_id);
        $query = $this->db->get('encounters');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_encounter($encounter_id)
    {
        $this->db->where('encounter_id', $encounter_id);
        $this->db->limit(1);
        $query = $this->db->get('encounters');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkExisting($id)
    {
        $this->db->where('encounter_id', $id);
        $this->db->where('last_action_status !=', 'deleted');
        $this->db->limit(1);
        $query = $this->db->get('encounters');
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function add($data)
    {
        $this->db->insert('encounters', $data);
        $this->db->limit(1);
        return true;
    }

    public function edit($id, $data)
    {
        $this->db->where('encounter_id', $id);
        $this->db->update('encounters', $data);
        $this->db->limit(1);
        return true;
    }

    public function delete($id)
    {
        $this->db->where('encounter_id', $id);
        $this->db->delete('encounters');
        $this->db->limit(1);
        return true;
    }
}