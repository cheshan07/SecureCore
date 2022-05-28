<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical_records extends CI_Controller
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

        $this->reportUrl = 'upload/';
    }

    public function index()
    {
        //get user details
        $data['user'] = $this->user_model->get_user($this->user_id);

        $data['li_medical_records'] = 'active';
        $this->load->view('web/header_view');
        if (isset($this->data['view_medical_record'])) {
            $this->load->view('web/menu_view', $data);
            $this->load->view('web/medical_record_view');
        }
        $this->load->view('web/footer_view');
    }

    public function getAllMedicalRecords()
    {
        $html = '';
        $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Report Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
        if ($this->role_id == 1 || $this->role_id == 3) {
            $reports = $this->medical_model->get_reports('record_id', 'asc');
        } else {
            $reports = $this->medical_model->get_reports('record_id', 'asc', $this->user_id);
        }

        /*echo '<pre>';
        print_r($members);
        die;*/
        if ($reports != null) {
            foreach ($reports as $row) {
                $report_link = $this->reportUrl . $row->report_link;
                $html .= "<tr data-category='active'>
                                        <td>$row->first_name $row->last_name</td>
                                        <td>$row->report_name</td>
                                        <td>$row->description</td>
                                        <td>$row->report_link</td>
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                if (isset($this->data['edit_medical_record'])) {
                    $html .= "<a class='btn-action btn-edit' href='#' title='Edit' onclick='getData($row->record_id);'><i class='lni-pencil'></i></a>";
                }
                if (isset($this->data['delete_medical_record'])) {
                    $html .= "<a class='btn-action btn-delete' href='#' title='Delete' onclick='deleteData($row->record_id);'><i class='lni-trash'></i></a>";
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

    public function getMedicalRecords()
    {
        $id = $this->input->post('id');
        $patient_id = '';
        $report_name = '';
        $description = '';
        $report_link = '';
        $patientField = '';
        $first_name = '';
        $last_name = '';

        $patients = $this->user_model->get_users('user_id', 'asc', 2);
        if ($id != null) {
            $report = $this->medical_model->get_report($id);

            if ($report != null) {
                //$patient_id = $report->patient_id;
                $report_name = $report->report_name;
                $description = $report->description;
                $report_link = $report->report_link;
                $patient_id = $report->patient_id;

            }
        }
        $html = '';
        $html .= "<form class='login-form' id='form-id'>
                        <div class='form-group'>
                            <input type='hidden' class='tg-select form-control' name='id' id='id' value='$id'>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <select class='tg-select form-control' name='patient_id' id='patient_id' $patientField>
                                    <option value=''>Select</option>";
        if ($this->role_id == 1 || $this->role_id == 3) {
            if ($patients != null) {
                foreach ($patients as $rowPatient) {
                    if($patient_id == $rowPatient->user_id)
                        $html .= "<option value='$rowPatient->user_id' selected>$rowPatient->first_name $rowPatient->last_name</option>";
                    else
                        $html .= "<option value='$rowPatient->user_id'>$rowPatient->first_name $rowPatient->last_name</option>";
                }
            }
        } else {
            $user = $this->user_model->get_user($this->user_id);
            if ($user != null) {
                $first_name = $user->first_name;
                $last_name = $user->last_name;
            }
            $html .= "<option value='$this->user_id' selected>$first_name  $last_name</option>";
        }
        $html .= "</select>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                                <input type='text' class='form-control' name='report_name' id='report_name'
                                       placeholder='Report Name' value='$report_name'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='input-icon'>
                            <textarea class='form-control' name='description' id='description' placeholder='Description'>$description</textarea>
                            </div>
                        </div>
                         <div class='form-group'>
                            <div class='input-icon'>
                                <input type='file' class='form-control' name='report_link' id='report_link'
                                       placeholder='Report' value='$report_name'>
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

    public function saveMedicalRecords()
    {
        /*$dataBody = $this->input->post();
        print_r($dataBody);
        die;*/
        $id = $this->db->escape_str($this->input->post('id'));

        $user_id = $this->user_id;
        $role_id = $this->role_id;
        $patient_id = $this->input->post('patient_id');
        $report_name = $this->input->post('report_name');
        $description = $this->input->post('description');
        $report_link = $this->input->post('report_link');


        $_FILES['file']['name'] = $_FILES['report_link']['name'];
        $_FILES['file']['type'] = $_FILES['report_link']['type'];
        $_FILES['file']['tmp_name'] = $_FILES['report_link']['tmp_name'];
        $_FILES['file']['error'] = $_FILES['report_link']['error'];
        $_FILES['file']['size'] = $_FILES['report_link']['size'];

        // File upload configuration
        $config = array(
            'upload_path' => "./" . $this->reportUrl,
            'allowed_types' => "*",
            'overwrite' => TRUE,
            'max_size' => "1024",
            'image_library' => "gd2"
        );

        $data = array(
            'patient_id' => $patient_id,
            'report_name' => $report_name,
            'description' => $description,
            'actioned_user_id' => $this->user_id,
            'action_date' => date("Y-m-d H:i:s"),
            'last_action_status' => 'added'
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

                $data['report_link'] = $imageName;
            } else {
                echo 'error';
            }
        }
        if ($id != null) {
            $reports = $this->medical_model->checkExistingEdit($id, $report_name);
        } else {
            $reports = $this->medical_model->checkExisting($report_name);
        }

        if ($reports != null) {
            echo 'Report already exist';
        } else {
            if ($id != null) {
                $response = $this->medical_model->edit($id, $data);
            } else {
                $response = $this->medical_model->add($data);
            }
            if ($response === true) {
                echo 'ok';
            } else {
                echo 'error';
            }
        }

    }

    public function deleteMedicalRecords()
    {
        $id = $this->db->escape_str($this->input->post('id'));
        $report = $this->medical_model->get_report($id);
        if ($report != null) {
            $result = $this->medical_model->delete($id);
            if ($result === true) {
                unlink($this->reportUrl . $report->report_link);
                echo 'ok';
            }
        } else {
            echo 'unknown record';
        }
    }

}