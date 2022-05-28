<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class encryptiondecryption_model extends CI_model
{
    function insert($data)
    {
        $this->db->insert('users',$data);
    }

  
}