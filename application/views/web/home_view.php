<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Dashboard</h2>
            </div>
           

            <div class="dashboard-wrapper">
                <div class="dashboard-sections">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                            <div class="dashboardbox">

                                <div class="contentbox">
                                    <h2><a href="#">Patients Details</a></h2>
                                    <?php
                                    if (isset($this->data['view_patient'])) {
                                        ?>
                                        <a class="btn btn-success" href="<?php echo base_url(); ?>patients">
                                            <i class="lni-layers"></i>
                                            <span>Patients</span>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                            <div class="dashboardbox">

                                <div class="contentbox">
                                    <h2><a href="#">Emergency Contact</a></h2>

                                    <?php
                                    if (isset($this->data['view_emergency'])) {
                                        ?>
                                        <a class="btn btn-warning" href="<?php echo base_url(); ?>emergency">
                                            <i class="lni-phone"></i>
                                            <span>Contact</span>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                            <div class="dashboardbox">
                                <div class="contentbox">
                                    <h2><a href="#">Change your profile settings</a></h2>

                                    <a class="btn btn-info" href="<?php echo base_url(); ?>profile">
                                        <i class="lni-lock"></i>
                                        <span>Profile Settings</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>