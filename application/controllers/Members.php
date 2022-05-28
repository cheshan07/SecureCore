<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller
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
        $this->load->model('members_model', '', TRUE);
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_members'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_member'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/member_view');
        }
        $this->load->view('web/footer_view');
    }

    public function getAllMembers()
    {
        $html = '';
        $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Fist Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Phone No</th>
                        <th>NIC</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
        if ($this->role_id == 1) {
            $members = $this->members_model->get_members('member_id', 'asc');
        } else {
            $members = $this->members_model->get_members('member_id', 'asc', $this->user_id);
        }

        /*echo '<pre>';
        print_r($members);
        die;*/
        if ($members != null) {
            foreach ($members as $row) {
                $sts_lable = '';
                $sts_cls = '';
                if ($row->member_status == 1) {
                    $sts_lable = 'Active';
                    $sts_cls = 'adstatus adstatusactive';
                } else {
                    $sts_lable = 'In Active';
                    $sts_cls = 'adstatus adstatusdeleted';
                }
                $html .= "<tr data-category='active'>
                                        <td>$row->first_name</td>
                                        <td>$row->last_name</td>
                                        <td>$row->gender</td>
                                        <td>$row->phone_no</td>
                                        <td>$row->nic</td>
                                        <td data-title='Ad Status'><span class='$sts_cls'>$sts_lable</span></td>
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                if (isset($this->data['edit_member'])) {
                    $html .= "<a class='btn-action btn-edit' href='#' title='Edit' onclick='getData($row->member_id);'><i class='lni-pencil'></i></a>";
                }
                if (isset($this->data['delete_member'])) {
                    $html .= "<a class='btn-action btn-delete' href='#' title='Delete' onclick='deleteData($row->member_id);'><i class='lni-trash'></i></a>";
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

    public function getMember()
    {
        $id = $this->input->post('id');
        $first_name = '';
        $last_name = '';
        $gender = '';
        $address = '';
        $phone_no = '';
        $nic = '';
        $maleSelect = '';
        $femaleSelect = '';
        $nicField = '';

        if ($id != null) {
            $member = $this->members_model->get_member($id);
            if ($member != null) {
                $nicField = 'readonly';

                $first_name = $member->first_name;
                $last_name = $member->last_name;

                $gender = $member->gender;
                if ($gender == 'Male') {
                    $maleSelect = 'selected';
                } else {
                    $femaleSelect = 'selected';
                }
                $address = $member->address;
                $phone_no = $member->phone_no;
                $nic = $member->nic;
            }
        }
        $html = '';
        $html .= "<form class='login-form' id='form-id'>
                        <div class='form-group'>
                            <input type='hidden' class='tg-select form-control' name='id' id='id' value='$id'>
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
                                <input type='text' class='form-control' name='nic' id='nic'
                                       placeholder='NIC' value='$nic' $nicField>
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

    public function saveMember()
    {
        $id = $this->db->escape_str($this->input->post('id'));

        $user_id = $this->user_id;
        $role_id = $this->role_id;
        $status = 1;
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $gender = $this->input->post('gender');
        $address = $this->input->post('address');
        $phone_no = $this->input->post('phone_no');
        $nic = $this->input->post('nic');

        $user = $this->user_model->get_user($user_id);
        if($user != null) {
            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'gender' => $gender,
                'address' => $address,
                'phone_no' => $phone_no,
                'nic' => $nic,
                'user_id' => $user_id,
                'user_nic' => $user->user_nic,
                'role_id' => $role_id,
                'member_status' => $status,
                'actioned_user_id' => $this->user_id,
                'action_date' => date("Y-m-d H:i:s"),
                'last_action_status' => 'added'
            );
            if ($id != null) {
                $member = $this->members_model->checkExistingEdit($id, $nic);
            } else {
                $member = $this->members_model->checkExisting($nic);
            }

            if ($member != null) {
                echo 'Member already exist';
            } else {
                if ($id != null) {
                    $response = $this->members_model->edit($id, $data);
                } else {
                    $response = $this->members_model->add($data);
                }
                if ($response === true) {
                    echo 'ok';
                } else {
                    echo 'error';
                }
            }
        }
    }

    public function deleteMember()
    {
        $id = $this->db->escape_str($this->input->post('id'));
        $user = $this->members_model->get_member($id);
        if ($user != null) {
            $result = $this->members_model->delete($id);
            if ($result === true) {
                echo 'ok';
            }
        } else {
            echo 'unknown member';
        }
    }
}