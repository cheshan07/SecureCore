<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller
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

        $this->reportUrl = 'upload/';
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_profile'] = 'active';
        $this->load->view('web/header_view');
        $this->load->view('web/menu_view', $data);
        $this->load->view('web/profile_view');
        $this->load->view('web/footer_view');
    }

    public function getProfile()
    {
        $id = $this->user_id;
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
        $profile_img = '';

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

                $profile_img = $user->profile_img;
            }
        }


        $html = '';
        $html .= "<form class='login-form' id='form-id'>
                        <div class='form-group'>";
                if($profile_img != null) {
                    $pimg = $this->reportUrl.$profile_img;
                    $html .= "<img src='$pimg' width='100px' height='100px'>";
                }

        $html .= "</div>
                        <div class='form-group'>
                            <input type='hidden' class='tg-select form-control' name='id' id='id' value='$id'>
                            <input type='text' class='tg-select form-control' name='pid' id='pid' value='$id' readonly>
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
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='file' class='form-control' name='profile_img' id='profile_img'
                                       placeholder='Report'>
                            </div>
                        </div>
                        <div class='text-center'>
                            <button class='btn btn-common log-btn' type='button' id='save' onclick='saveData();'>Save</button>
                        </div>
                    </form>";

        $response = array(
            'status' => 'ok',
            'msg' => $html
        );

        echo json_encode($response);
    }

    public function saveProfile()
    {
        $id = $this->user_id;

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

        $_FILES['file']['name'] = $_FILES['profile_img']['name'];
        $_FILES['file']['type'] = $_FILES['profile_img']['type'];
        $_FILES['file']['tmp_name'] = $_FILES['profile_img']['tmp_name'];
        $_FILES['file']['error'] = $_FILES['profile_img']['error'];
        $_FILES['file']['size'] = $_FILES['profile_img']['size'];

        // File upload configuration
        $config = array(
            'upload_path' => "./" . $this->reportUrl,
            'allowed_types' => "*",
            'overwrite' => TRUE,
            'max_size' => "1024",
            'image_library' => "gd2"
        );

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
            'actioned_user_id' => $this->user_id,
            'action_date' => date("Y-m-d H:i:s"),
            'last_action_status' => 'edited'
        );

        // Load and initialize upload library
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // Upload file to server
        if ($this->upload->do_upload('file')) {
            // Uploaded file data
            $fileData = $this->upload->data();
            if ($fileData != null) {
                $image_config["image_library"] = "gd2";
                $image_config["source_image"] = $fileData["full_path"];
                $image_config['create_thumb'] = FALSE;
                $image_config['maintain_ratio'] = FALSE;

                $this->load->library('image_lib', $image_config);
                $this->image_lib->initialize($image_config);

                $this->image_lib->resize();
                $this->image_lib->clear();

                //rename the file

                $img_name = $fileData['raw_name'];
                $newN = $img_name . '_' . date("Y-m-d H:i:s");
                $img_name_new = $newN . $fileData['file_ext'];
                //rename($fileData['full_path'], $fileData['file_path'] . $img_name_new);
                $imageName = $img_name . $fileData['file_ext'];

                $data['profile_img'] = $imageName;
            } else {
                echo 'error';
            }
        }

        $user = $this->user_model->checkExisting($email);

        if ($user != null) {
            $response = $this->user_model->edit($id, $data);
            echo 'ok';
        } else {
            echo 'error';
        }
    }
}