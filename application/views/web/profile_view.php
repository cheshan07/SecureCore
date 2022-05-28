<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">My Profile</h2>
            </div>
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
        getData();

    });

    function getData() {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>profile/get',
            data: {
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
            var form_data = new FormData($("#form-id")[0]);
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>profile/save',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                mimeType: "multipart/form-data",
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
</script>