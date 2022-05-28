<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Patient Dashboard</h2>
            </div>

            <div class="dashboard-wrapper">
                <form class="row form-dashboard">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                        <div class="privacy-box privacysetting">
                            <div class="dashboardboxtitle">
                                <h2>Patient Details</h2>
                            </div>
                            <div class="dashboardholder">
                                <?php
                                $html = '';
                                $profileUrl = base_url().'profile';

                                if ($this->session->logged_in) {
                                    $session_data = $this->session->userdata('logged_in');
                                    $role_id = $session_data['role_id'];
                                }

                                if($user != null) {
                                    $html .= "<ul>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Patient ID: $user->user_id</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Name: $user->title. $user->first_name $user->last_name</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Gender: $user->gender</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>NIC: $user->user_nic</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Address: $user->address</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Phone No: $user->phone_no</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class='custom-control'>
                                            <label class='custom-control-label1'>Email: $user->email</label>
                                        </div>
                                    </li>
                                    </ul>";
                                    if($role_id == 2) {
                                       $html .= "<a href='$profileUrl' class='btn btn-common'>Update</a>";
                                    }

                                }
                                echo $html;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="privacy-box deleteaccount">
                            <div class="dashboardboxtitle">
                                <h2>Family Members Details</h2>
                            </div>
                            <div class="dashboardholder">
                                <div class="form-group mb-3 tg-inputwithicon">
                                    <?php
                                    $html = '';
                                    $memberUrl = base_url().'members';
                                    $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>NIC</th>
                                            <th>Phone No</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                                    if($members != null) {
                                        foreach($members as $rowMember) {
                                            $html .= "<tr data-category='active'>
                                                <td>$rowMember->first_name $rowMember->last_name</td>
                                                <td>$rowMember->gender</td>
                                                <td>$rowMember->nic</td>
                                                <td>$rowMember->phone_no</td>
                                            </tr>";
                                        }
                                    }
                                    $html .= "</tbody>
                                        </table>";
                                    if($role_id == 2) {
                                        $html .= "<a href='$memberUrl' class='btn btn-common'>Update</a>";
                                    }
                                    echo $html;
                                    ?>
                                </div>
                            </div>

                            <div class="form-group md-3">
                                <section id="editor">
                                    <div id="summernote">
                                    </div>
                                </section>
                            </div>
                            <label class="tg-fileuploadlabel" for="tg-photogallery">
                                <span>Update Your Medical Record</span>
                                <a href='<?php echo base_url()?>medical_records' class='btn btn-common'>Update</a>
                            </label>

                        </div>
                    </div>
                </form>
                <div class="dashboardboxtitle">
                    <h2>Report Details (Medical Records History)</h2>
                </div>
                <?php

                $html = '';
                $html = "<table class='table  dashboardtable tablemyads' width='100%'>
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Report Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
                if ($reports != null) {
                    foreach ($reports as $row) {
                        $report_link = $this->reportUrl . $row->report_link;
                        $html .= "<tr data-category='active'>
                                        <td>$row->report_name</td>
                                        <td>$row->description</td>
                                        <td>$row->report_link</td>
                                        <td data-title='Action'>
                                        <div class='btns-actions'>";
                        if (isset($this->data['edit_medical_record'])) {
                            $url = base_url().'patients/download/'.$row->record_id;
                            if($row->report_link != null)
                            $html .= "<a class='btn-action btn-edit' href='$url' title='Download'><i class='lni-download'></i></a>";
                        }
                        $html .= "</div>
                                    </td>
                                      </tr>";

                    }
                }
                $html .= "</tbody>
                </table>";

                echo $html;
                ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>


<script src="<?php echo base_url(); ?>themes/assets/js/jquery-min.js"></script>
<script src="<?php echo base_url(); ?>themes/assets/js/toastr.min.js"></script>
<script src="<?php echo base_url(); ?>themes/assets/js/validate.js"></script>

