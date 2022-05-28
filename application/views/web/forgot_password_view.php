<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-12 col-xs-12">
                <div class="forgot login-area">
                    <h3>
                        Forgot Password
                    </h3>

                    <form role="form" class="login-form" id="form-id">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="icon lni-user"></i>
                                <input type="email" id="email" class="form-control" name="email"
                                       placeholder="Email">
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-common log-btn" type="button" id="submit">Send me my Password</button>
                        </div>
                        <div class="form-group mt-4">
                            <ul class="form-links">
                                <li class="float-left"><a href="<?php echo base_url(); ?>register">Don't have an account?</a></li>
                                <li class="float-right"><a href="<?php echo base_url(); ?>login">Back to Login</a></li>
                            </ul>
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
                url: '<?php echo base_url(); ?>forgot/submit',
                data: $('#form-id').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('We have e-mailed your password reset link');
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
    });

    function validate() {
        return (isValidEmail("email", "Email Address is required")
        );
    }
</script>