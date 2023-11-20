<!DOCTYPE html>
<html lang="en" style="background: url({{asset('assets/img/cover.png')}});background-size: cover;background-repeat: no-repeat;">
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Valet Pro Admin</title>
        <!-- App favicon -->
        <!-- <link rel="shortcut icon" href="assets/img/favicon.ico"> -->
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">

        <!-- App css -->
        <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/metismenu.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
        <script src="{{asset('assets/admin/js/modernizr.min.js')}}"></script>
    </head>

    <body class="bg-accpunt-pages">

        <!-- HOME -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="account-pages">
                                <div class="account-box" id="login_content">
                                    <div class="account-logo-box">
                                        <h2 class="text-uppercase text-center">
                                            <a href="/" class="text-success">
                                                <span><img src="{{asset('assets/img/xhdpi.png')}}" alt="Valet Pro Logo" style="background-color: darkgray;"></span>
                                            </a>
                                        </h2>
                                    </div>
                                    <form method="post" action="{{url('login')}}">
                                        @csrf
                                        <div class="account-content">
                                        @if(session()->has('error_msg'))
                                            <div class="alert alert-danger">
                                            {{ session()->get('error_msg') }}
                                            </div>
                                        @endif
                                            <form class="form-horizontal" method="post">
                                                <div class="form-group m-b-20 row">
                                                    <div class="col-12">
                                                        <label for="emailaddress">Email address</label>
                                                        <input class="form-control" type="email" name="email" value="{{old('email')}}" id="emailaddress"  placeholder="Enter Email Address">
                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row m-b-20">
                                                    <div class="col-12">
                                                        <label for="password">Password</label>
                                                        <input class="form-control" type="password" name="password"  id="password" placeholder="Enter your password">
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row text-center m-t-10">
                                                    <div class="col-12">
                                                        <button class="btn btn-md btn-block btn-primary waves-effect waves-light" type="submit">Sign In</button>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </form>
                                </div>

                                <div class="account-box" id="forget_content" style="display: none;">
                                    <div class="text-center account-logo-box">
                                        <h2 class="text-uppercase">
                                            <a href="/" class="text-success">
                                                <span><img src="{{asset('assets/img/xhdpi.png')}}" alt="Valet Pro Logo" style="background-color: darkgray;"></span>
                                            </a>
                                        </h2>
                                        <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                    </div>
                                    <div class="account-content">
                                        <div class="text-center m-b-20" id="forgetPasswordformHead">
                                            <p class="text-muted m-b-0">Enter your email address and we'll send you an email with instructions to reset your password.  </p>
                                        </div>
                                        <form class="form-horizontal" id="forgetPasswordform">
                                            <div class="form-group m-b-20">
                                                <div class="col-xs-12">
                                                    <label for="emailaddress">Email address</label>
                                                    <input class="form-control" type="email" name="femail" id="emailaddress" required="" placeholder="john@deo.com">
                                                </div>
                                            </div>

                                            <div class="form-group text-center m-t-10">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-md btn-block btn-primary waves-effect waves-light" onclick="doResetPassword();" type="button">Reset Password</button>
                                                </div>
                                            </div>

                                        </form>
                                        <form method="post" id="confirmpassword" style="display: none;"> 
                                            <div class="form-group m-b-20">
                                                <label for="otp">Enter OTP</label>
                                                <input id="otp" name="otp" type="text" class="form-control loginf" placeholder="Please Enter Code"/>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="for_new_password">New Password</label>
                                                <input id="for_new_password" name="new_password" type="password" class="form-control loginf" placeholder="Enter New Password"/>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 2px;">
                                                <label for="for_cpassword">Confirm New Password</label>
                                                <input id="for_cpassword" name="cpassword" type="password" class="form-control loginf" placeholder="Enter Confirm Password"/>
                                            </div>                                                    
                                            <div class="clearfix"></div>                    
                                            <button type="button" onclick="doAdminResetPassword();" class="btn btn-info btn-block m-t-20"> SUBMIT </button>
                                        </form>

                                        <div class="clearfix"></div>

                                        <div class="row m-t-40">
                                            <div class="col-sm-12 text-center">
                                                <p class="text-muted">Back to <a href="/admin-login" class="text-dark m-l-5"><b>Sign In</b></a></p>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!-- end card-box-->

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
        </section>
          <!-- END HOME -->


        <script>
            var resizefunc = [];
        </script>


        <!-- jQuery  -->
        <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
        <!-- Toaster -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        {{--<script type="text/javascript">
            <?php if($this->session->flashdata('success')){ ?>
                toastr.success("<?php echo $this->session->flashdata('success'); ?>");
            <?php }else if($this->session->flashdata('error')){  ?>
                toastr.error("<?php echo $this->session->flashdata('error'); ?>");
            <?php }else if($this->session->flashdata('warning')){  ?>
                toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
            <?php }else if($this->session->flashdata('info')){  ?>
                toastr.info("<?php echo $this->session->flashdata('info'); ?>");
            <?php } ?>
        </script>--}}

    </body>
</html>


<script>
var base_url = "/";
function forgetPassword(){
    $("#forget_content").show();
    $("#login_content").hide();
}
setTimeout(function(){$(".alert").hide(); }, 5500);

function doResetPassword(){
    var $form = $("#forgetPasswordform");
    var data = getFormData($form);
    if(data.femail==''){
        toastr.error("Please enter email address.");
        return;
    }else if(validateEmail(data.femail)){
        toastr.error("Please enter valid email address.");
        return;
    }else{
        $.ajax({        
            type : 'POST',
            url : base_url+'admin_login/forgotPassword',
            headers : {},
            contentType : 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            success : function(response) {
                if(response.success){
                  otp = response.otp;
                  id = response.id;
                    $("#confirmpassword").show();
                    $("#forgetPasswordform").hide();
                    $("#forgetPasswordformHead").hide();
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            },
            error : function(xhr, status, error) {
                $("#preloader").hide();
                toastr.error("There is an error");
            }
        });
    }
}

function doAdminResetPassword(){
    var $form = $("#confirmpassword");
    var data = getFormData($form);  
    data.id = id;  
    if(data.otp==''){
        toastr.error("Please enter OTP.");
        return;
    }else if(data.otp!=otp){
        toastr.error("Please enter valid OTP");
        return;
    }else if(data.new_password==''){
        toastr.error("Please enter New Password.");
        return;
    }else if(data.cpassword==''){
        toastr.error("Please enter valid Confirm Password.");
        return;
    }else if(data.cpassword!=data.new_password){
        toastr.error("New Password and Confirm Password must be same.");
        return;
    }else{
        $("#preloader").show();
        $.ajax({        
            type : 'POST',
            url : base_url+'admin_login/resetPassword',
            headers : {},
            contentType : 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            success : function(response) {
                $("#preloader").hide();
                if(response.success){
                    toastr.success(response.message);
                    setTimeout(function(){
                        window.location.reload();
                    },3500);
                    
                }else{
                    toastr.error(response.message);
                }
            },
            error : function(xhr, status, error) {
                $("#preloader").hide();
                toastr.error("There is an error");
            }
        });
    }
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};
    console.log(unindexed_array);
    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function validateEmail(email){
    console.log(email);
    var x = email;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        return true;
    }else{
        return false;
    }
}
</script>
