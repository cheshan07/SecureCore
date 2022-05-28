


<section class="login section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-12 col-xs-12">
                <div class="login-form login-area">
                    <h3>
                        Login Now
                    </h3>

                    <form role="form" class="login-form" id="form-id">
                        <div id="login_form" class="page_load_show">
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="lni-user"></i>
                                    <input type="text" class="form-control" name="email" id="email"
                                           placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="lni-lock"></i>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <!--<div class="custom-control custom-checkbox">
                                    <a class="forgetpassword" href="<?php echo base_url(); ?>emergency">Emergency Login</a>
                                </div>-->
                                <a class="forgetpassword" href="<?php echo base_url(); ?>forgot">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-common log-btn" type="button" id="submit">Submit</button>
                            </div>
                        </div>
                        <br>
                        <div id="otp_form" class="page_load">
                            <p>Session will end in <span class="js-timeout">2:00</span> minutes.</p>
                            <div class='form-group'>
                                <div class='input-icon'>
                                    <i class='lni-lock'></i>
                                    <input type='text' class='form-control' id='otp' name='otp' placeholder='OTP'>
                                </div>
                            </div>
                            <div class='text-center'>
                                <button class='btn btn-common log-btn' type="button" id='submit_otp'>Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

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

    $("#submit").click(function () {
        if (validate()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>login/authenticate',
                data: $('#form-id').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('Please enter Valid OTP . Your OTP only valid 2 minutes');
                        var delay = 1500;
                        setTimeout(function () {
                            $("#login_form").removeClass("page_load_show");
                            $("#login_form").addClass("page_load");
                            $("#otp_form").removeClass("page_load");
                            $("#otp_form").addClass("page_load_show");
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
    });


    function validate() {
        return (isValidEmail("email", "Username is required")
            && isNotEmpty("password", "Password is required")
        );
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

    $("#submit_otp").click(function () {
        if (validate_otp()) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>check_otp',
                data: $('#form-id').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        var delay = 1500;
                        setTimeout(function () {
                            window.location.href = '<?php echo base_url(); ?>login';
                        }, delay);
                    }
                    else {
                        toastr.error(responce);
                    }
                }
            });
        }
    });

    function validate_otp() {
        return (isNotEmpty("otp", "OTP is required")
        );
    }
</script>