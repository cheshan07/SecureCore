<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(

    //contact screen
    'web/contact_send' => array(
        array(
            'field' => 'name',
            'label' => 'Your Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Your Email',
            'rules' => 'trim|required|valid_email'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'ext_captcha',
            'label' => 'Captcha',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'captcha',
            'label' => 'Captcha',
            'rules' => 'trim|required|matches[ext_captcha]'
        )
    ),

    //plan screen
    'web/tour_plan_send' => array(
        array(
            'field' => 'name',
            'label' => 'Your Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Your Email',
            'rules' => 'trim|required|valid_email'
        ),
        array(
            'field' => 'country',
            'label' => 'Country',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'date_of_arrival',
            'label' => 'Date of Arrival',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'date_of_departure',
            'label' => 'Date of Departure',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'package_permalink',
            'label' => 'Tour Package',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'guest',
            'label' => 'No of guest',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'ext_captcha',
            'label' => 'Captcha',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'captcha',
            'label' => 'Captcha',
            'rules' => 'trim|required|matches[ext_captcha]'
        )
    ),
);