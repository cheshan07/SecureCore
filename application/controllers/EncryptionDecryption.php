<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    function insert()
    {
        $this->load->view('register_verify_view')
    }
    {
        this->load->view('register_view');
    }

    function insert()
    {
        thsi->load->library('encrypt');

        $data = array(
            'first_name'    => $this->encrypt->encode($this->input->post('first_name'))
        );
        $this->load->model('encryptiondecryption_model');
        $this->encryptiondecryption_model->insert($data);
        $this->session->set_flashdata('action','Data inserted');
        redirect('register_verify_view');
    }


}