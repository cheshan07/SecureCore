<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emergency extends CI_Controller
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
        $this->load->model('medical_model', '', TRUE);
        $this->load->model('members_model', '', TRUE);

        $this->otp_status = $this->auth->otpSettings();
        $this->otp_data = $this->auth->otpUserData();
        $this->reportUrl = 'upload/';
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_emergency'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_patient'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/emergency_view');
        }
        $this->load->view('web/footer_view');
    }

    public function getAllPatients()
    {
        $patient_id = $this->input->post('patient_id');

        $html = '';
        $html = "<strong>Member Details</strong><table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>NIC</th>
                        <th>Phone No</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";

        $members = $this->members_model->get_members('member_id', 'asc', $patient_id);
        if ($members != null) {
            foreach ($members as $row) {
                    $html .= "<tr data-category='active'>
                                        <td>$row->first_name $row->last_name</td>
                                        <td>$row->gender</td>
                                        <td>$row->nic</td>
                                        <td>$row->phone_no</td>
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                    if (isset($this->data['add_emergency'])) {
                        $html .= "<a class='btn-action btn-edit' href='#' title='Contact' onclick='contactData($row->member_id);'><i class='lni-pencil'></i></a>";
                    }
                    $html .= "</div>
                                    </td>
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

    public function emergencySubmit()
    {
        $id = $this->input->post('id');

        $member = $this->members_model->get_member($id);
        if ($member != null) {
            $member_id = $member->member_id;
            $phone_no = $member->phone_no;
            $name = $member->first_name.' '. $member->last_name;

            $timestamp = date("Y-m-d H:i:s");
            $start_time = date($timestamp);
            $expires = strtotime('+2 minutes', strtotime($timestamp));
            $time_diff = ($expires - strtotime($timestamp)) / 86400;
            $exp_time = date('Y-m-d H:i:s', $expires);

            $otp = $this->randomOtp(6);
            $data = array(
                'otp' => $otp,
                'otp_expire_time' => $exp_time,
                'access_flag' => 0,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );

            $html = '';
            $html .= "<input type='hidden' name='member_id' id='member_id' value='$member_id'>Message Sent to: $name Phone No: $phone_no Please click on button to check whether user has authorized ";
            $html .= "<button type='button' id='submit' class='btn btn-common' onclick='checkData()'> Check</button>";

            $result = $this->members_model->update_otp($member_id, $data);
            if ($result === true) {
                if($this->otp_status === true) {
                    //send otp
                    $user = $this->otp_data['user'];
                    $password = $this->otp_data['password'];
                    $ul = base_url().'login/emergencyAccess/'.$otp;
                    //$link = "<a href='$ul'></a>";
                    $link = $ul;

                    $text = urlencode("Emergency contact from the Secure Core. Please give permission to access patient details. Click Link " . $link . " valid 2 minutes");
                    $to = $phone_no;

                    $baseurl = $this->otp_data['url'];
                    $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                    $ret = file($url);

                    $res = explode(":", $ret[0]);

                    if (trim($res[0]) == "OK") {
                        //echo 'ok';
                        $response = array(
                            'status' => 'ok',
                            'msg' => $html
                        );
                    } else {
                        //echo "Sent Failed - Error : " . $res[1];
                        $response = array(
                            'status' => 'error',
                            'msg' => 'Sent Failed - Error : ' . $res[1]
                        );
                    }
                } else {
                    //echo 'ok';
                    $response = array(
                        'status' => 'ok',
                        'msg' => $html
                    );
                }
            }
        } else {
            //echo 'No member account was found with the NIC you entered.';
            $response = array(
                'status' => 'error',
                'msg' => 'No member account was found with the NIC you entered.'
            );
        }

        echo json_encode($response);
    }

    private function randomOtp($length)
    {
        $result = '';
        $length = $length;
        $str = '1234567890';
        $result = substr(str_shuffle($str), 0, $length);
        return $result;
    }

    public function emergencyOtp() {
        $member_id = $this->input->post('member_id');
        $member = $this->members_model->get_member($member_id);
        if($member != null) {
            if($member->access_flag == 1)
                echo 'ok';
            else
                echo 'not authorized yet';
        } else {
            echo 'error';
        }
    }


}