<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Patients Encounter Details</h2>
            </div>
            <form role="form" class="login-form" id="form-id">
                <div id="login_form" class="page_load_show">
                    <div class='form-group'>
                        <div class='input-icon'>
                            <select class='tg-select form-control' name='patient_id' id='patient_id'>
                                <option value=''>Patient</option>
                                <?php
                                if ($patients != null) {
                                    foreach ($patients as $row) {
                                        ?>
                                        <option value='<?php echo $row->user_id ?>'><?php echo $row->first_name.' '. $row->last_name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button class='btn btn-common log-btn' type='button' id='new' onclick="sendOtp();">View Data</button>
                </div>
                <br>
                <div id="otp_form" class="page_load">
                    <p>Session will end in <span class="js-timeout">2:00</span> minutes.</p>
                    <div class='form-group'>
                        <div class='input-icon'>
                            <input type='text' class='form-control' id='otp' name='otp' placeholder='OTP'>
                        </div>
                    </div>
                    <div class='text-center'>
                        <button class='btn btn-common log-btn' type="button" id='submit_otp'>Submit</button>
                        <button class='btn btn-warning log-btn' type="button" id='cancel'>Cancel</button>
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


<script src=" <?php echo base_url(); ?>themes/assets/js/jquery-min.js"></script>
<script src = "<?php echo base_url(); ?>themes/assets/js/toastr.min.js" ></script>
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

    function sendOtp() {
        if (validate()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>reports/send/otp',
                data: {
                    'patient_id': $('#patient_id').val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>:
                    '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('Please enter Valid OTP . Your OTP only valid 2 minutes');
                        var delay = 1500;
                        setTimeout(function () {
                            $("#login_form").removeClass("page_load_show");
                            $("#login_form").addClass("page_load");
                            $("#otp_form").removeClass("page_load");
                            $("#login_form").addClass("page_load_show");
                            $('.js-timeout').text("2:00");
                            countdown();
                        }, delay);
                    }
                    else {
                        toastr.error(responce);
                    }
                }
            });
        }
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

    function validate() {
        return (isSelected("patient_id", "Patient is required")
        );
    }

    $("#submit_otp").click(function () {
        if (validate_otp()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>reports/get/all',
                data: $('#form-id').serialize(),
                dataType: 'json',
                success: function (responce) {
                    if (responce.status == 'ok') {
                        $("#login_form").removeClass("page_load_show");
                        $("#login_form").addClass("page_load");
                        $("#otp_form").removeClass("page_load_show");
                        $("#otp_form").addClass("page_load");
                        $('.dashboard-wrapper').html(responce.msg);
                    }
                    else {
                        toastr.error(responce.msg);
                    }
                }
            });
        }
    });

    function validate_otp() {
        return (isNotEmpty("otp", "OTP is required")
        );
    }

    $("#cancel").click(function () {
        location.reload();
    });
</script>

