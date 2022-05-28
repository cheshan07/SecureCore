<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">Patients Encounter Details</h2>
            </div>
            <?php
            if (isset($this->data['add_encounter'])) {
            ?>
                <button class='btn btn-common log-btn' type='button' id='new' onclick="getData();">Add New</button>
            <?php
            }
            ?>

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
            url: '<?php echo base_url(); ?>encounters/get/all',
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

    function getData(id) {
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>encounters/get',
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
                url: '<?php echo base_url(); ?>encounters/save',
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
        var bConfirm = confirm("Do you want to delete this details?");
        if (bConfirm) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>encounters/delete',
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
        return (isSelected("patient_id", "Patient is required")
            && isNotEmpty("description", "Description is required")
            && isNotEmpty("action_date", "Date is required")
        );
    }
</script>