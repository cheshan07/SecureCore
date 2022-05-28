<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="OTP">
    <meta name="keywords" content="OTP">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Core</title>

    <!-- Css Styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/fonts/line-icons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/slicknav.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/color-switcher.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/assets/css/toastr.min.css">

</head>
<body>

<header id="header-wrap">

    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-5 col-xs-12">
                </div>
                <div class="col-lg-5 col-md-7 col-xs-12">
                    <div class="header-top-right float-right">
                        <?php
                        if ($this->session->logged_in) {
                            $session_data = $this->session->userdata('logged_in');
                            $user_name = $session_data['user_name'];
                            ?>
                            <?php echo $user_name; ?>|
                            <a href="<?php echo base_url(); ?>log_out" class="header-top-button"><i
                                    class="lni-pencil"></i> Log Out</a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php echo base_url(); ?>login" class="header-top-button"><i class="lni-lock"></i>
                                Log In</a> |
                            <a href="<?php echo base_url(); ?>register" class="header-top-button"><i
                                    class="lni-pencil"></i> Register</a>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
