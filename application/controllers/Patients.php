<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends CI_Controller
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

        $data['li_patients'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_patient'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/patient_view');
        }
        $this->load->view('web/footer_view');
    }

    public function getAllPatients()
    {
        $patient_id = $this->input->post('patient_id');
        $patient_name = $this->input->post('patient_name');

        $html = '';
        $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Name</th>
                        <th>NIC</th>
                        <th>Phone No</th>
                        <th>Email</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
        $users = $this->user_model->get_users('user_id', 'asc', null, $patient_id, $patient_name);
        if ($users != null) {
            foreach ($users as $row) {
                $sts_lable = '';
                $sts_cls = '';
                if ($row->status == 1) {
                    $sts_lable = 'Active';
                    $sts_cls = 'adstatus adstatusactive';
                } else {
                    $sts_lable = 'In Active';
                    $sts_cls = 'adstatus adstatusdeleted';
                }
                if($row->role_id == 2) {
                    $html .= "<tr data-category='active'>
                                        <td>$row->title</td>
                                        <td>$row->first_name $row->last_name</td>
                                        <td>$row->user_nic</td>
                                        <td>$row->phone_no</td>
                                        <td>$row->email</td>

                                        <td data-title='Ad Status'><span class='$sts_cls'>$sts_lable</span></td>
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                    if (isset($this->data['view_medical_record'])) {
                        $html .= "<a class='btn-action btn-view' href='#' title='View' onclick='viewData($row->user_id);'><i class='lni-eye'></i></a>";
                    }
                    if (isset($this->data['edit_patient'])) {
                        $html .= "<a class='btn-action btn-edit' href='#' title='Edit' onclick='getData($row->user_id);'><i class='lni-pencil'></i></a>";
                    }
                    if (isset($this->data['delete_patient'])) {
                        $html .= "<a class='btn-action btn-delete' href='#' title='Delete' onclick='deleteData($row->user_id);'><i class='lni-trash'></i></a>";
                    }
                    $html .= "</div>
                                    </td>
                                      </tr>";
                }
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

    public function getPatient()
    {
        $id = $this->input->post('id');
        $title = '';
        $first_name = '';
        $last_name = '';
        $gender = '';
        $user_nic = '';
        $address = '';
        $phone_no = '';
        $email = '';

        $mrSelect = '';
        $mrsSelect = '';
        $missSelect = '';
        $drSelect = '';
        $maleSelect = '';
        $femaleSelect = '';
        $emailField = '';

        if ($id != null) {
            $user = $this->user_model->get_user($id);
            if ($user != null) {
                $emailField = 'readonly';
                $title = $user->title;
                if($title == 'Mr') {
                    $mrSelect = 'selected';
                } else if($title == 'Mrs') {
                    $mrsSelect = 'selected';
                } else if($title == 'Miss') {
                    $missSelect = 'selected';
                } else if($title == 'Dr') {
                    $drSelect = 'selected';
                }

                $first_name = $user->first_name;
                $last_name = $user->last_name;

                $gender = $user->gender;
                if($gender == 'Male') {
                    $maleSelect = 'selected';
                } else {
                    $femaleSelect = 'selected';
                }
                $address = $user->address;
                $phone_no = $user->phone_no;
                $email = $user->email;
                $user_nic = $user->user_nic;
            }
        }
        $html = '';
        $html .= "<form class='login-form' id='form-id'>
                        <div class='form-group'>
                            <input type='hidden' class='tg-select form-control' name='id' id='id' value='$id'>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <select class='tg-select form-control' name='title' id='title'>
                                    <option value=''>Title</option>
                                    <option value='Mr' $mrSelect>Mr</option>
                                    <option value='Mrs' $mrsSelect>Mrs</option>
                                    <option value='Miss' $missSelect>Miss</option>
                                    <option value='Dr' $drSelect>Dr</option>
                                </select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='first_name' id='first_name'
                                       placeholder='First Name' value='$first_name'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='last_name' id='last_name'
                                       placeholder='Last Name' value='$last_name'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <select class='tg-select form-control' name='gender' id='gender'>
                                    <option value=''>Gender</option>
                                    <option value='Male' $maleSelect>Male</option>
                                    <option value='Female' $femaleSelect>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='user_nic' id='user_nic'
                                       placeholder='NIC' value='$user_nic'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <textarea class='form-control' name='address' id='address'
                                       placeholder='Address'>$address</textarea>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='phone_no' id='phone_no'
                                       placeholder='Phone Number' value='$phone_no'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='email' id='email'
                                       placeholder='Email Address' value='$email' $emailField>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='password' class='form-control' name='password' id='password'
                                       placeholder='Password'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='password' class='form-control' name='retype_password' id='retype_password'
                                       placeholder='Retype Password'>
                            </div>
                        </div>
                        <div class='text-center'>
                            <button class='btn btn-common log-btn' type='button' id='save' onclick='saveData();'>Save</button>
                            <button class='btn btn-warning log-btn' type='button' id='cancel' onclick='cancelData();'>Cancel</button>
                        </div>
                    </form>";

        $response = array(
            'status' => 'ok',
            'msg' => $html
        );

        echo json_encode($response);
    }

    public function savePatient()
    {
        $id = $this->db->escape_str($this->input->post('id'));

        $role_id = 2;
        $status = 1;
        $title = $this->input->post('title');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $gender = $this->input->post('gender');
        $user_nic = $this->input->post('user_nic');
        $address = $this->input->post('address');
        $phone_no = $this->input->post('phone_no');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $options = [
            'cost' => 12
        ];
        $new_password = password_hash($password, PASSWORD_BCRYPT, $options);
        $data = array(
            'title' => $title,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'gender' => $gender,
            'user_nic' => $user_nic,
            'address' => $address,
            'phone_no' => $phone_no,
            'email' => $email,
            'password' => $new_password,
            'role_id' => $role_id,
            'status' => $status,
            'actioned_user_id' => $this->user_id,
            'action_date' => date("Y-m-d H:i:s"),
            'last_action_status' => 'added'
        );
        if ($id != null) {
            $user = $this->user_model->checkExistingEdit($id, $email);
        } else {
            $user = $this->user_model->checkExisting($email);
        }

        if ($user != null) {
            echo 'Patient already exist';
        } else {
            if ($id != null) {
                $response = $this->user_model->edit($id, $data);
            } else {
                $response = $this->user_model->add($data);
            }
            if ($response === true) {
                echo 'ok';
            } else {
                echo 'error';
            }
        }
    }

    public function deletePatient() {
        $id = $this->db->escape_str($this->input->post('id'));
        $user = $this->user_model->get_user($id);
        if($user != null) {
            $result = $this->user_model->delete($id);
            if($result === true) {
                echo 'ok';
            }
        } else {
            echo 'unknown patient';
        }
    }

    public function sendOtp() {
        $id = $this->db->escape_str($this->input->post('id'));
        $user = $this->user_model->get_user($id);

        //get doctor
        $doctor = $this->user_model->get_user($this->user_id);
        $doc_name = '';
        if($doctor != null) {
            $doc_name = $doctor->first_name.' '.$doctor->last_name;
        }

        if($user != null) {
            $email = $user->email;

            $timestamp = date("Y-m-d H:i:s");
            $start_time = date($timestamp);
            $expires = strtotime('+2 minutes', strtotime($timestamp));
            $time_diff = ($expires - strtotime($timestamp)) / 86400;
            $exp_time = date('Y-m-d H:i:s', $expires);

            $otp = $this->randomOtp(6);
            $data = array(
                'email' => $email,
                'report_otp' => $otp,
                'report_otp_expire_time' => $exp_time,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'updated'
            );

            $result = $this->user_model->update_otp($email, $data);
            if($this->otp_status === true) {
                //send otp
                $to = $user->phone_no;
                $user = $this->otp_data['user'];
                $password = $this->otp_data['password'];
                $text = urlencode("Your one time password for Secure Core is " . $otp . " Only valid 2 minutes. Please let inform OTP to doctor: ".$doc_name);


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

    private function randomOtp($length)
    {
        $result = '';
        $length = $length;
        $str = '1234567890';
        $result = substr(str_shuffle($str), 0, $length);
        return $result;
    }

    public function getOtpForm() {
        $id = $this->db->escape_str($this->input->post('id'));
        $html = '';
        $html .= "<form role='form' class='login-form' id='form-otp'>";
        $html .= "<div id='otp_form'>
                            <p>Session will end in <span class='js-timeout'>2:00</span> minutes.</p>
                            <div class='form-group'>
                                <div class='input-icon'>
                                    <input type='hidden' class='form-control' id='p_id' name='p_id' value='$id'>
                                    <input type='text' class='form-control' id='otp' name='otp' placeholder='OTP'>
                                </div>
                            </div>
                            <div class='text-center'>
                                <button class='btn btn-common log-btn' type='button' id='submit_otp' onclick='checkOtp()'>Submit</button>
                                <button class='btn btn-warning log-btn' type='button' id='cancel' onclick='cancelData();'>Cancel</button>
                            </div>
                        </div>";
        $html .= "</form>";

        echo $html;
    }

    public function checkOtp()
    {
        $p_id = $this->db->escape_str($this->input->post('p_id', TRUE));
        $otp = $this->db->escape_str($this->input->post('otp', TRUE));

        $result = $this->user_model->check_report_otp($p_id, $otp);
        if ($result != null) {
            foreach ($result as $row) {
                $timestamp = date("Y-m-d H:i:s");
                $expire = $row->report_otp_expire_time;
                if (strtotime($timestamp) > strtotime($expire)) {
                    echo 'your OTP has expired';
                } else {
                    echo 'ok';
                }
            }
        } else {
            echo 'Invalid OTP';
        }
    }

    public function dashboard()
    {
        $data['user'] = $this->user_model->get_user($this->user_id);
        //get user details
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $data['user'] = $this->user_model->get_user($id);
            $data['members'] = $this->members_model->get_members('member_id', 'asc', $id);
            $data['reports'] = $this->medical_model->get_reports('record_id', 'asc', $id);
            $data['li_dashboard'] = 'active';
            $this->load->view('web/header_view');
            if (isset($this->data['view_medical_record'])) {
                $this->load->view('web/menu_view', $data);
                $this->load->view('web/patient_dashboard_view', $data);
            }
            $this->load->view('web/footer_view');

        }
    }

    public function download($id) {
        //$id = $this->input->post('id');
        $report = $this->medical_model->get_report($id);

        if ($report != null) {
            $this->load->helper('download');
            force_download ( $report->report_link,  $report->report_link);
            redirect('patients/dashboard?id='.$id, 'refresh');
        }
    }
}