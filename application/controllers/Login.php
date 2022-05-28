<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; // Load Composer's autoloader

require 'vendor/autoload.php'; // Instantiation

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->logged_in) redirect('home', 'refresh');

        $this->load->library('auth');
        //get user id
        $this->user_id = $this->auth->getUserId();
        //get user name
        $this->user_name = $this->auth->getUserName();

        $this->load->model('user_model', '', TRUE);
        $this->load->model('members_model', '', TRUE);

        $this->otp_status = $this->auth->otpSettings();
        $this->otp_data = $this->auth->otpUserData();
    }

    public function index()
    {
        $this->load->view('web/header_view');
        $this->load->view('web/login_view');
    }

    public function forgot()
    {
        $this->load->view('web/header_view');
        $this->load->view('web/forgot_password_view');
    }

    public function emergency()
    {
        $this->load->view('web/header_view');
        $this->load->view('web/emergency_view');
    }

    public function forgot_submit()
    {
        $email = $this->input->post('email');

        $user = $this->user_model->checkExisting($email);
        if ($user != null) {
            $reset_code = $this->randomNumber(50);

            $data = array(
                'email' => $email,
                'password_reset_code' => $reset_code,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );

            $response = $this->user_model->update_user($email, $data);
            if ($response === true) {
                $this->send_password_reset_mail($email, $reset_code);
                echo 'ok';
            } else {
                echo 'error';
            }
        } else {
            echo 'No account was found with the email address you entered.';
        }
    }

    public function register()
    {
        $this->load->view('web/header_view');
        $this->load->view('web/register_view');
    }

    public function register_submit()
    {
        $role_id = $this->input->post('role_id');
        $title = $this->input->post('title');
        $first_name = $this->input->post('first_name');
        $gender = $this->input->post('gender');
        $user_nic = $this->input->post('user_nic');
        $phone_no = $this->input->post('phone_no');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->user_model->checkExisting($email);
        if ($user != null) {
            echo 'User already exist';
        } else {
            $options = [
                'cost' => 12
            ];
            $new_password = password_hash($password, PASSWORD_BCRYPT, $options);

            $verify_code = $this->randomNumber(50);

            $data = array(
                'title' => $title,
                'first_name' => $first_name,
                'gender' => $gender,
                'user_nic' => $user_nic,
                'phone_no' => $phone_no,
                'email' => $email,
                'password' => $new_password,
                'role_id' => $role_id,
                'verify_code' => $verify_code,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'added'
            );

            $response = $this->user_model->add($data);
            if ($response === true) {
                $this->send_verify_mail($email, $verify_code);
                echo 'ok';
            } else {
                echo 'error';
            }
        }
    }

    private function randomNumber($length)
    {
        $result = '';
        $length = $length;
        $str = '1234567890abcefghijklmnopqrstuvwxyz';
        $result = substr(str_shuffle($str), 0, $length);
        return $result;
    }

    private function send_verify_mail($email, $code)
    {
        //send verification mail
        $from = 'support@codered-technology.com';
        $from_name = "Secure Core";
        $sub = 'Verify your new account';
        $msg = "Hi<br>";
        $segments = array('login', 'register_verify', $email, $code);
        $url = site_url($segments);
        $msg .= "Please click on the following link to complete registration process <br><br>";
        $msg .= "<a href='$url' target='_blank'>Verify Registration</a>";
        $this->load->library('email');
        $this->email->clear();
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from, $from_name);
        $this->email->to($email);
        $this->email->subject($sub);
        $this->email->message($msg);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    private function send_password_reset_mail($email, $code)
    {
        //send verification mail
        $from = 'support@codered-technology.com';
        $from_name = "Secure Core";
        $sub = 'Your account password reset link';
        $msg = "Hi<br>";
        $segments = array('login', 'password_reset', $email, $code);
        $url = site_url($segments);
        $msg .= "Please click on the following link to reset your password <br><br>";
        $msg .= "<a href='$url' target='_blank'>Reset Password</a>";
        $this->load->library('email');
        $this->email->clear();
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from, $from_name);
        $this->email->to($email);
        $this->email->subject($sub);
        $this->email->message($msg);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function authenticate()
    {
        $email = $this->db->escape_str($this->input->post('email', TRUE));
        $password = $this->db->escape_str($this->input->post('password', TRUE));

        $pass = $this->user_model->get_pass($email);
        if ($pass != null) {
            foreach ($pass as $pass) {
                $hash = array($pass->password);
            }
            $hash = $hash[0];
            if (password_verify($password, $hash)) {
                $result = $this->user_model->login($email);
                if ($result != null) {
                    foreach ($result as $row) {
                        if ($row->status == 0) {
                            echo 'Please verify your email address';
                        } else {
                            $timestamp = date("Y-m-d H:i:s");
                            $start_time = date($timestamp);
                            $expires = strtotime('+2 minutes', strtotime($timestamp));
                            $time_diff = ($expires - strtotime($timestamp)) / 86400;
                            $exp_time = date('Y-m-d H:i:s', $expires);

                            $otp = $this->randomOtp(6);
                            $data = array(
                                'email' => $email,
                                'otp' => $otp,
                                'otp_expire_time' => $exp_time,
                                'action_date' => date("Y-m-d H:i:s"),
                                'last_action_status' => 'updated'
                            );

                            $result = $this->user_model->update_otp($email, $data);
                            if ($result === true) {
                                if($this->otp_status === true) {
                                    //send otp
                                    $user = $this->otp_data['user'];
                                    $password = $this->otp_data['password'];
                                    $text = urlencode("Your one time password for Secure Core is " . $otp . " Only valid 2 minutes");
                                    $to = $row->phone_no;

                                    $baseurl = $this->otp_data['url'];
                                    $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                                    $ret = file($url);

                                    $res = explode(":", $ret[0]);

                                    if (trim($res[0]) == "OK") {
                                        //echo "Message Sent - ID : ".$res[1];
                                        echo 'ok';
                                    } else {
                                        echo "Sent Failed - Error : " . $res[1];
                                    }
                                } else {
                                    echo 'ok';
                                }
                            }
                        }
                    }
                } else {
                    echo 'Invalid User Name or Password';
                }
            } else {
                echo 'Invalid User Name or Password';
            }
        } else {
            echo 'Invalid User Name or Password';
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

    public function check_otp()
    {
        $email = $this->db->escape_str($this->input->post('email', TRUE));
        $otp = $this->db->escape_str($this->input->post('otp', TRUE));

        $result = $this->user_model->check_otp($email, $otp);
        if ($result != null) {
            foreach ($result as $row) {
                $timestamp = date("Y-m-d H:i:s");
                $expire = $row->otp_expire_time;
                if (strtotime($timestamp) > strtotime($expire)) {
                    echo 'your OTP has expired';
                } else {
                    $sess_array = array(
                        'user_id' => $row->user_id,
                        'user_name' => $row->email,
                        'role_id' => $row->role_id
                    );
                    $this->session->set_userdata('logged_in', $sess_array);
                    echo 'ok';
                }
            }
        } else {
            echo 'Invalid OTP';
        }
    }

    public function register_verify($email, $verify_code)
    {

        $result = $this->user_model->register_verify($email, $verify_code);
        if ($result != null) {
            $data['verify_msg'] = 'Thank you for signing up.';
            $this->load->view('web/header_view');
            $this->load->view('web/register_verify_view', $data);

            $data = array(
                'status' => 1,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );

            $result = $this->user_model->update_user($email, $data);
        } else {
            $data['verify_msg'] = 'User Registration Failed.';
            $this->load->view('web/header_view');
            $this->load->view('web/register_verify_view', $data);
        }
    }

    public function password_reset($email, $password_reset_code)
    {

        $result = $this->user_model->password_reset_verify($email, $password_reset_code);
        if ($result != null) {
            $data['email'] = $email;
            $this->load->view('web/header_view');
            $this->load->view('web/reset_password_view', $data);
        } else {
            $data['verify_msg'] = 'Invalid details.';
            $this->load->view('web/header_view');
            $this->load->view('web/register_verify_view', $data);
        }
    }

    public function reset_password()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->user_model->checkExisting($email);
        if ($user == null) {
            echo 'No account was found with the email address you entered.';
        } else {
            $options = [
                'cost' => 12
            ];
            $new_password = password_hash($password, PASSWORD_BCRYPT, $options);

            $data = array(
                'password' => $new_password,
                'password_reset_code' => null,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );

            $response = $this->user_model->update_user($email, $data);
            if ($response === true) {
                //$this->send_verify_mail($email, $verify_code);
                echo 'ok';
            } else {
                echo 'error';
            }
        }
    }

    public function emergencySubmit()
    {
        $nic = $this->input->post('nic');

        $member = $this->members_model->checkExisting($nic);
        if ($member != null) {
            $member_id = $member->member_id;
            $phone_no = $member->phone_no;

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

            $result = $this->members_model->update_otp($member_id, $data);
            if ($result === true) {
                if($this->otp_status === true) {
                    //send otp
                    $user = $this->otp_data['user'];
                    $password = $this->otp_data['password'];
                    $text = urlencode("Your one time password for Secure Core system is " . $otp . " Only valid 2 minutes");
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
            echo 'No member account was found with the NIC you entered.';
        }
    }

    public function emergencyOtp()
    {
        $nic = $this->db->escape_str($this->input->post('nic', TRUE));
        $otp = $this->db->escape_str($this->input->post('otp', TRUE));

        $result = $this->members_model->check_otp($nic, $otp);
        if ($result != null) {
            foreach ($result as $row) {
                $timestamp = date("Y-m-d H:i:s");
                $expire = $row->otp_expire_time;
                if (strtotime($timestamp) > strtotime($expire)) {
                    echo 'your OTP has expired';
                } else {
                    $sess_array = array(
                        'user_id' => $row->user_id,
                        'user_name' => $row->email,
                        'role_id' => $row->role_id
                    );
                    $this->session->set_userdata('logged_in', $sess_array);
                    echo 'ok';
                }
            }
        } else {
            echo 'Invalid OTP';
        }
    }

    public function emergencyAccess($otp) {
        $data = array(
            'otp' => null,
            'otp_expire_time' => null,
            'access_flag' => 1,
            'action_date' => date("Y-m-d H:i:s"),
            'last_action_status' => 'updated'
        );
        $rstOtp = $this->members_model->checkExistingOtp($otp);
        if($rstOtp != null) {
            $result = $this->members_model->emergencyAccess($otp, $data);
            echo 'ok';
        } else {
            echo 'error otp';
        }

    }

}
