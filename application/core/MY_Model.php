<?php

class MY_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //add
    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
        $this->db->limit(1);
        return true;
    }

    //add batch
    public function insert_batch($table, $data)
    {
        $this->db->insert_batch($table, $data);
        return true;
    }

    //add & return id
    public function insert_id($table, $data)
    {
        $this->db->insert($table, $data);
        $this->db->limit(1);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    //edit
    public function update($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        $this->db->limit(1);
        return true;
    }

    public function update_batch($table, $data, $field)
    {
        $this->db->update_batch($table, $data, $field);
        return true;
    }

    //delete
    public function delete($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return true;
    }

    //delete all
    public function empty_table($table)
    {
        $this->db->empty_table($table);
        return true;
    }

    //get all
    public function get_all($table, $where = null, $order_field = null, $order_type = null, $group_by = null)
    {
        if ($order_field != null && $order_type != null)
            $this->db->order_by($order_field, $order_type);

        if ($where != null)
            $this->db->where($where);

        if($group_by != null)
            $this->db->group_by($group_by);

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //get all limit
    public function limit_all($table, $where = null, $order_field = null, $order_type = null, $limit = null)
    {
        if ($order_field != null && $order_type != null)
            $this->db->order_by($order_field, $order_type);

        if ($where != null)
            $this->db->where($where);

        if($limit != null)
            $this->db->limit($limit);

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //get by id
    public function get($table, $where)
    {
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get($table);
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    //get distinct
    public function get_distinct($table, $where, $select)
    {
        $this->db->where($where);
        $this->db->select($select);
        $this->db->distinct();
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //get last row
    public function get_last($table)
    {
        $query = $this->db->get($table);
        $row = $query->last_row();
        return $row;
    }

    //get max
    public function get_max($table, $select)
    {
        $this->db->select_max($select);
        $query = $this->db->get($table);
        if ($query->num_rows() == 1):
            return $query->row();
        else:
            return false;
        endif;
    }

    //get min
    public function get_min($table, $select)
    {
        $this->db->select_min($select);
        $query = $this->db->get($table);
        if ($query->num_rows() == 1):
            return $query->row();
        else:
            return false;
        endif;
    }

    //set
    public function set($table, $where = null, $set)
    {
        if ($where != null)
            $this->db->where($where);

        $this->db->set($set);
        $this->db->update($table);
        return true;
    }
}