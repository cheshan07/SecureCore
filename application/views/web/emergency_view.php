<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Emergency Contact</h2>
            </div>

            <form id="contactForm" class="contact-form page_load_show">
                <h4 class="contact-title">
                    Search Patient's Members
                </h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name='patient_id' id='patient_id' placeholder="Patient ID or NIC">
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

    function getAllData() {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>emergency/get/all',
            data: {
                'patient_id': $('#patient_id').val(),
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

    function contactData(id) {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>emergency/submit',
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


    function checkData() {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>emergency/otp',
            data: {
                'member_id': $('#member_id').val(),
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'html',
                success: function (responce) {
                if (responce == 'ok') {
                    window.location.href = '<?php echo base_url(); ?>patients/dashboard?id='+$('#patient_id').val();
                }
                else {
                    toastr.error(responce);
                }
            }
        });
    }
</script>