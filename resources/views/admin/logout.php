<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Valet Pro Admin</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico">

        <!-- App css -->
        <link href="<?php echo $this->config->item('asset_base_url');?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('asset_base_url');?>assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('asset_base_url');?>assets/admin/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('asset_base_url');?>assets/admin/css/style.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $this->config->item('asset_base_url');?>assets/admin/js/modernizr.min.js"></script>
    </head>

    <body class="bg-accpunt-pages">

    <!-- HOME -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="account-pages">
                                <div class="account-box">
                                    <div class="text-center account-logo-box">
                                        <h2 class="text-uppercase">
                                            <a href="index.html" class="text-success">
                                                <span><img src="<?php echo base_url();?>assets/img/xhdpi.png" alt="Valet Pro Logo" height="110" style="background-color: darkgray;"></span>
                                            </a>
                                        </h2>
                                        <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                    </div>
                                    <div class="account-content">
                                        <div class="text-center m-b-20">
                                            <div class="m-b-20">
                                                <div class="checkmark">
                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                         viewBox="0 0 161.2 161.2" enable-background="new 0 0 161.2 161.2" xml:space="preserve">
                                                    <path class="path" fill="none" stroke="#32c861" stroke-miterlimit="10" d="M425.9,52.1L425.9,52.1c-2.2-2.6-6-2.6-8.3-0.1l-42.7,46.2l-14.3-16.4
                                                        c-2.3-2.7-6.2-2.7-8.6-0.1c-1.9,2.1-2,5.6-0.1,7.7l17.6,20.3c0.2,0.3,0.4,0.6,0.6,0.9c1.8,2,4.4,2.5,6.6,1.4c0.7-0.3,1.4-0.8,2-1.5
                                                        c0.3-0.3,0.5-0.6,0.7-0.9l46.3-50.1C427.7,57.5,427.7,54.2,425.9,52.1z"/>
                                                    <circle class="path" fill="none" stroke="#32c861" stroke-width="4" stroke-miterlimit="10" cx="80.6" cy="80.6" r="62.1"/>
                                                    <polyline class="path" fill="none" stroke="#32c861" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="113,52.8
                                                        74.1,108.4 48.2,86.4 "/>

                                                    <circle class="spin" fill="none" stroke="#32c861" stroke-width="4" stroke-miterlimit="10" stroke-dasharray="12.2175,12.2175" cx="80.6" cy="80.6" r="73.9"/>

                                                </svg>

                                                </div>
                                            </div>

                                            <h3>See You Again !</h3>

                                            <p class="text-muted font-13 m-t-10"> You are now successfully sign out. Back to <a href="<?php echo base_url();?>admin-login" class="text-primary m-r-5"><b>Sign In</b></a></p>
                                        </div>

                                    </div>

                                </div>
                                <!-- end card-box-->
                            </div>

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
          </section>


        <script>
            var resizefunc = [];
        </script>


        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
        <!-- Toaster -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script type="text/javascript">
            <?php if($this->session->flashdata('success')){ ?>
                toastr.success("<?php echo $this->session->flashdata('success'); ?>");
            <?php }else if($this->session->flashdata('error')){  ?>
                toastr.error("<?php echo $this->session->flashdata('error'); ?>");
            <?php }else if($this->session->flashdata('warning')){  ?>
                toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
            <?php }else if($this->session->flashdata('info')){  ?>
                toastr.info("<?php echo $this->session->flashdata('info'); ?>");
            <?php } ?>
        </script>

    </body>
</html>


<script>
  setTimeout(function(){$(".alert").hide(); }, 5500);
</script>
