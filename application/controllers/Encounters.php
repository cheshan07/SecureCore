<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encounters extends CI_Controller
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
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_encounters'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_encounter'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/encounter_view');
        }
        $this->load->view('web/footer_view');
    }

    public function getAllEncounters()
    {
        $html = '';
        $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
        if ($this->role_id == 1 || $this->role_id == 4) {
            $encounters = $this->encounter_model->get_encounters('encounter_id', 'asc', null, null);
        } else if ($this->role_id == 2) {
            $encounters = $this->encounter_model->get_encounters('encounter_id', 'asc', $this->user_id, null);
        } else if ($this->role_id == 3) {
            $encounters = $this->encounter_model->get_encounters('encounter_id', 'asc', null, $this->user_id);
        }

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
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                if (isset($this->data['edit_encounter'])) {
                    $html .= "<a class='btn-action btn-edit' href='#' title='Edit' onclick='getData($row->encounter_id);'><i class='lni-pencil'></i></a>";
                }
                if (isset($this->data['delete_encounter'])) {
                    $html .= "<a class='btn-action btn-delete' href='#' title='Delete' onclick='deleteData($row->encounter_id);'><i class='lni-trash'></i></a>";
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

    public function getEncounter()
    {
        $id = $this->input->post('id');
        $patient_id = '';
        $description = '';
        $action_date = '';

        $users = $this->user_model->get_users('user_id', 'asc', 2);

        if ($id != null) {
            $encounter = $this->encounter_model->get_encounter($id);
            if($encounter != null) {
                $patient_id = $encounter->patient_id;
                $description = $encounter->description;
                $action_date = $encounter->action_date;
            }
        }

        $html = '';
        $html .= "<form class='login-form' id='form-id'>
                        <div class='form-group'>
                            <input type='hidden' class='tg-select form-control' name='id' id='id' value='$id'>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <select class='tg-select form-control' name='patient_id' id='patient_id'>
                                    <option value=''>Patient</option>";
                                    if ($users != null) {
                                        foreach($users as $rowUser) {
                                            if($rowUser->user_id == $patient_id)
                                                $html .= "<option value='$rowUser->user_id' selected>$rowUser->first_name $rowUser->last_name</option>";
                                            else
                                                $html .= "<option value='$rowUser->user_id'>$rowUser->first_name $rowUser->last_name</option>";
                                        }
                                    }
                                    $html .= "
                                </select>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class='input-icon'>
                                <textarea class='form-control' name='description' id='description'
                                       placeholder='Description'>$description</textarea>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='date' class='form-control' name='action_date' id='action_date'
                                       placeholder='Date' value='$action_date'>
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

    public function saveEncounter()
    {
        $id = $this->db->escape_str($this->input->post('id'));

        $patient_id = $this->input->post('patient_id');
        $description = $this->input->post('description');
        $action_date = $this->input->post('action_date');

        $data = array(
            'patient_id' => $patient_id,
            'doctor_id' => $this->user_id ,
            'description' => $description,
            'action_date' => $action_date,
            'actioned_user_id' => $this->user_id,
            'last_action_status' => 'added'
        );
        $encounter = null;
        if ($id != null) {
            $encounter = $this->encounter_model->checkExisting($id);
        }

        if ($encounter != null) {
            $response = $this->encounter_model->edit($id, $data);
        } else {
            $response = $this->encounter_model->add($data);
        }
        if ($response === true) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    public function deleteEncounter()
    {
        $id = $this->db->escape_str($this->input->post('id'));
        $encounter = $this->encounter_model->checkExisting($id);
        if ($encounter != null) {
            $result = $this->encounter_model->delete($id);
            if ($result === true) {
                echo 'ok';
            }
        } else {
            echo 'unknown encounter details';
        }
    }
}