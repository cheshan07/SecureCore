
<?php
if (isset($li_dashboard)) $li_dashboard = $li_dashboard;
else $li_dashboard = '';

if (isset($li_roles)) $li_roles = $li_roles;
else $li_roles = '';

if (isset($li_users)) $li_users = $li_users;
else $li_users = '';

if (isset($li_patients)) $li_patients = $li_patients;
else $li_patients = '';

if (isset($li_doctors)) $li_doctors = $li_doctors;
else $li_doctors = '';

if (isset($li_encounters)) $li_encounters = $li_encounters;
else $li_encounters = '';

if (isset($li_members)) $li_members = $li_members;
else $li_members = '';

if (isset($li_reports)) $li_reports = $li_reports;
else $li_reports = '';

if (isset($li_profile)) $li_profile = $li_profile;
else $li_profile = '';

if (isset($li_medical_records)) $li_medical_records = $li_medical_records;
else $li_medical_records = '';

if (isset($li_emergency)) $li_emergency = $li_emergency;
else $li_emergency = '';
?>
<div id="content" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3 page-sidebar">
                <aside>
                    <div class="sidebar-box">
                        <div class="user">
                            <div class="usercontent">
                                <?php
                                $html = '';
                                if($user != null) {
                                    if($user->profile_img != null) {
                                        $pimg = 'upload/'.$user->profile_img;
                                        $html .= "<img src='$pimg' width='70px' height='70px'><br><br>";
                                    }

                                    $html .= "<h3>Hi $user->first_name $user->last_name</h3>";
                                    $html .= "<h4>(Role: $user->role)</h4>";
                                    echo $html;
                                }
                                ?>
                            </div>
                        </div>
                        <nav class="navdashboard">
                            <ul>
                                <li>
                                    <a class="<?php echo $li_dashboard; ?>" href="<?php echo base_url(); ?>home">
                                        <i class="lni-dashboard"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <?php
                                    if (isset($this->data['view_roles'])) {
                                    ?>
                                    <a class="<?php echo $li_roles; ?>" href="<?php echo base_url(); ?>roles">
                                        <i class="lni-user"></i>
                                        <span>User Roles</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($this->data['view_user'])) {
                                    ?>
                                    <a class="<?php echo $li_users; ?>" href="<?php echo base_url(); ?>users">
                                        <i class="lni-users"></i>
                                        <span>Users</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($this->data['view_patient'])) {
                                    ?>
                                    <a class="<?php echo $li_patients; ?>" href="<?php echo base_url(); ?>patients">
                                        <i class="lni-layers"></i>
                                        <span>Patients</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($this->data['view_doctor'])) {
                                    ?>
                                    <a class="<?php echo $li_doctors; ?>" href="<?php echo base_url(); ?>doctors">
                                        <i class="lni-plus"></i>
                                        <span>Doctors</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>

                                <li>
                                    <?php
                                    if (isset($this->data['view_emergency'])) {
                                        ?>
                                        <a class="<?php echo $li_emergency; ?>" href="<?php echo base_url(); ?>emergency">
                                            <i class="lni-phone"></i>
                                            <span>Emergency Contact</span>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($this->data['view_member'])) {
                                    ?>
                                    <a class="<?php echo $li_members; ?>" href="<?php echo base_url(); ?>members">
                                        <i class="lni-users"></i>
                                        <span>Members</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <!--<li>
                                    <?php
                                    if (isset($this->data['view_report'])) {
                                    ?>
                                    <a class="<?php echo $li_reports; ?>" href="<?php echo base_url(); ?>reports">
                                        <i class="lni-bar-chart"></i>
                                        <span>Reports</span>
                                    </a>
                                        <?php
                                    }
                                    ?>
                                </li>-->
                                <li>
                                    <a class="<?php echo $li_profile; ?>" href="<?php echo base_url(); ?>profile">
                                        <i class="lni-lock"></i>
                                        <span>Profile Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>log_out">
                                        <i class="lni-enter"></i>
                                        <span>Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>
            </div>
