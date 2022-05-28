<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Patients</h2>
            </div>
            <?php
            if (isset($this->data['add_patient'])) {
                ?>
                <button class='btn btn-common log-btn' type='button' id='new' onclick="getData();">Add New</button>
                <?php
            }
            ?>
            <br> <br>
            <form id="contactForm" class="contact-form page_load_show">
                <h4 class="contact-title">
                    Search Patient
                </h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name='patient_id' id='patient_id' placeholder="Patient NIC">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name='patient_name' id='patient_name' placeholder="Patient Name">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="submit" class="btn btn-common" onclick="getAllData()">Search</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
            <div class="dashboard-wrapper">

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

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "500",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $(document).ready(function () {
        getAllData();

    });

    function getAllData() {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>patients/get/all',
            data: {
                'patient_id': $('#patient_id').val(),
                'patient_name': $('#patient_name').val(),
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
                success: function (responce) {
                if (responce.status == 'ok') {
                    $('.dashboard-wrapper').html(responce.msg);
                }
                else {
                    toastr.error(responce);
                }
            }
        });
    }

    function getData(id) {
        $('#contactForm').removeClass('page_load_show')
        $('#contactForm').addClass('page_load')
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>patients/get',
            data: {
                'id': id,
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
                success: function (responce) {
                if (responce.status == 'ok') {
                    $('.dashboard-wrapper').html(responce.msg);
                }
                else {
                    toastr.error(responce);
                }
            }
        });
    }

    function saveData() {
        if (validate()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>patients/save',
                data: $('#form-id').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('Successfully saved');
                        var delay = 1500;
                        setTimeout(function () {
                            location.reload();
                        }, delay);
                    }
                    else {
                        toastr.error(responce);
                    }
                }
            });
        }
    }

    function deleteData(id) {
        var bConfirm = confirm("Do you want to delete this patient?");
        if (bConfirm) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>patients/delete',
                data: {
                    'id': id,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('Successfully deleted');
                        var delay = 1500;
                        setTimeout(function () {
                            location.reload();
                        }, delay);
                    }
                    else {
                        toastr.error(responce);
                    }
                }
            });
        }
    }

    function cancelData() {
        location.reload();
    }

    function validate() {
        return (isSelected("title", "Title is required")
            && isNotEmpty("first_name", "First Name is required")
            && isSelected("gender", "Gender is required")
            && isNotEmpty("user_nic", "NIC is required")
            && isNumeric("phone_no", "Phone Number is required")
            && isValidEmail("email", "Email Address is required")
            && isNotEmpty("password", "Password is required")
            && isNotEmpty("retype_password", "Retype Password is required")
            && verifyPassword("password", "retype_password", "Valid Password is required")
        );
    }

    function viewData(id) {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>patients/otp',
            data: {
                'id': id,
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'html',
                success: function (responce) {
                if (responce == 'ok') {
                    //get otp form
                    getOtpForm(id);
                }
                else {
                    toastr.error(responce);
                }
            }
        });
    }

    function getOtpForm(id) {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>patients/getOtpForm',
            data: {
                'id': id,
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'html',
            success: function (responce) {
                $('.dashboard-wrapper').html(responce);
                $('.js-timeout').text("2:00");
                countdown();
            }
        });
    }

    var interval;

    function countdown() {
        clearInterval(interval);
        interval = setInterval( function() {
            var timer = $('.js-timeout').html();
            timer = timer.split(':');
            var minutes = timer[0];
            var seconds = timer[1];
            seconds -= 1;
            if (minutes < 0) return;
            else if (seconds < 0 && minutes != 0) {
                minutes -= 1;
                seconds = 59;
            }
            else if (seconds < 10 && length.seconds != 2) seconds = '0' + seconds;

            $('.js-timeout').html(minutes + ':' + seconds);


            if (minutes == 0 && seconds == 0) {
                clearInterval(interval);
                $("#submit_otp").attr("disabled", true);
                $("#otp").attr("disabled", true);
            }
        }, 1000);
    }

    function checkOtp() {
        var patient_id = $('#p_id').val();
        if (validate_otp()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>patients/otp/check',
                data: $('#form-otp').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        //get user's report
                        getMedicalRecord(patient_id);
                    }
                    else {
                        toastr.error(responce);
                    }
                }
            });
        }
    };

    function validate_otp() {
        return (isNotEmpty("otp", "OTP is required")
        );
    }

    function getMedicalRecord(patient_id) {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>patients/otp/check',
            data: $('#form-otp').serialize(),
            success: function (responce) {
                if (responce == 'ok') {
                    //get user's report
                    window.location.href = '<?php echo base_url(); ?>patients/dashboard?id='+patient_id;
                }
                else {
                    toastr.error(responce);
                }
            }
        });
    }
</script>