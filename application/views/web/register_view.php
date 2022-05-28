<section class="register section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-12 col-xs-12">
                <div class="register-form login-area">
                    <h3>
                        Register
                    </h3>

                    <form class="login-form" id="form-id">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-users"></i>
                                <select class="tg-select form-control" name="role_id" id="role_id">
                                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Register as</option>
                                    <option value="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Patient</option>
                                    <option value="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Doctor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-users"></i>
                                <select class="tg-select form-control" name="title" id="title">
                                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title</option>
                                    <option value="Mr">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mr.</option>
                                    <option value="Mrs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mrs.</option>
                                    <option value="Mrs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Miss.</option>
                                    <option value="Dr">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dr.</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="text" class="form-control" name="first_name" id="first_name"
                                       placeholder="First Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-users"></i>
                                <select class="tg-select form-control" name="gender" id="gender">
                                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender</option>
                                    <option value="Male">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Male.</option>
                                    <option value="Female">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female.</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-notepad"></i>
                                <input type="text" class="form-control" name="user_nic" id="user_nic"
                                       placeholder="NIC">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-phone"></i>
                                <input type="text" class="form-control" name="phone_no" id="phone_no"
                                       placeholder="Phone Number">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-envelope"></i>
                                <input type="text" class="form-control" name="email" id="email"
                                       placeholder="Email Address">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" name="retype_password" id="retype_password"
                                       placeholder="Retype Password">
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-common log-btn" type="button" id="submit">Register</button>
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
                url: '<?php echo base_url(); ?>register/submit',
                data: $('#form-id').serialize(),
                success: function (responce) {
                    if (responce == 'ok') {
                        toastr.success('Please verify your email');
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
        return (isSelected("role_id", "Register type is required")
            && isSelected("title", "Title is required")
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
</script>