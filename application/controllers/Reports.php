<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
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
        $this->load->model('encounter_model', '', TRUE);

        $this->otp_status = $this->auth->otpSettings();
        $this->otp_data = $this->auth->otpUserData();
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);
        $data['patients'] = $this->user_model->get_users('user_id', 'asc', 2);
        $data['li_reports'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_report'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/report_view', $data);
        }
        $this->load->view('web/footer_view');
    }

    public function sendOtp()
    {
        $patient_id = $this->db->escape_str($this->input->post('patient_id', TRUE));
        $user = $this->user_model->get_user($patient_id);

        if ($user != null) {
            $email = $user->email;
            $phone_no = $user->phone_no;

            $timestamp = date("Y-m-d H:i:s");
            $start_time = date($timestamp);
            $expires = strtotime('+2 minutes', strtotime($timestamp));
            $time_diff = ($expires - strtotime($timestamp)) / 86400;
            $exp_time = date('Y-m-d H:i:s', $expires);

            $otp = $this->randomOtp(6);

            $data = array(
                'otp' => $otp,
                'otp_expire_time' => $exp_time,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );
            $result = $this->user_model->update_otp($email, $data);
            if ($result === true) {
                if ($this->otp_status === true) {
                    //send otp
                    $user = $this->otp_data['user'];
                    $password = $this->otp_data['password'];
                    $text = urlencode("Your one time password for Secure Core system Report viewing is " . $otp . " Only valid 2 minutes");
                    $to = $phone_no;

                    $baseurl = $this->otp_data['url'];
                    $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                    $ret = file($url);

                    $res = explode(":", $ret[0]);

                    if (trim($res[0]) == "OK") {
                        echo 'ok';
                    } else {
                        echo "Sent Failed - Error : " . $res[1];
                    }
                } else {
                    echo 'ok';
                }
            }
        } else {
            echo 'invalid user';
        }
    }

    private function randomOtp($length)
    {
        $result = '';
        $length = $length;
        $str = '1234567890';
        $result = substr(str_shuffle($str), 0, $length);
        return $result;
    }

    public function getAll()
    {
        $patient_id = $this->db->escape_str($this->input->post('patient_id', TRUE));
        $otp = $this->db->escape_str($this->input->post('otp', TRUE));

        $user = $this->user_model->get_user($patient_id);

        if ($user != null) {
            $email = $user->email;
            $result = $this->user_model->check_otp($email, $otp);
            if ($result != null) {
                foreach ($result as $row) {
                    $timestamp = date("Y-m-d H:i:s");
                    $expire = $row->otp_expire_time;
                    if (strtotime($timestamp) > strtotime($expire)) {
                        $response = array(
                            'status' => 'error',
                            'msg' => 'your OTP has expired'
                        );
                        echo json_encode($response);
                    } else {
                        $html = '';
                        $patient_id = $this->input->post('patient_id');
                        $html .= "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>";
                        $encounters = $this->encounter_model->get_encounters('encounter_id', 'asc', $patient_id, null);
                        if ($encounters != null) {
                            foreach ($encounters as $row) {
                                $patient_id = $row->patient_id;
                                $doctor_id = $row->doctor_id;

                                $patient = $this->user_model->get_user($patient_id);
                                $doctor = $this->user_model->get_user($doctor_id);

                                $html .= "<tr data-category='active'>
                                        <td>$patient->first_name $patient->last_name</td>
                                        <td>$doctor->first_name $doctor->last_name</td>
                                        <td>$row->description</td>
                                        <td>$row->action_date</td>
                                      </tr>";

                            }
                        }
                        $html .= "</tbody>
                </table>";
                        $response = array(
                            'status' => 'ok',
                            'msg' => $html
                        );
                        echo json_encode($response);
                    }
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'msg' => 'Invalid OTP'
                );

                echo json_encode($response);
            }
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'invalid user'
            );
            echo json_encode($response);
        }

    }
}