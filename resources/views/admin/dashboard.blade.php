@extends('layouts.app')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title float-left">Dashboard</h4>

                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="/admin/dashboard">Dashboard</a></li>
                            </ol>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                <div class="row">
                    
                    <!-- <div class="col-lg-3 col-md-6">
                        <a href="/admin/vendor">
                            <div class="card-box widget-box-two widget-two-custom">
                                <i class="mdi mdi-account widget-two-icon"></i>
                                <div class="wigdet-two-content">
                                    <p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics" style="color:#797979;">Total Vendors</p>
                                    <h2 class="font-600"><span><i class="mdi mdi-arrow-up"></i></span> <span data-plugin="counterup">45</span></h2>
                                </div>
                            </div>
                        </a>
                    </div> -->
                

                    <div class="col-lg-4 col-md-6">
                        <a href="{{url('admin/users/manager')}}">
                            <div class="card-box widget-box-two widget-two-custom">
                                <i class="mdi mdi-account-key widget-two-icon"></i>
                                <div class="wigdet-two-content">
                                    <p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics" style="color:#797979;">Total Managers</p>
                                    <h2 class="font-600"><span><i class="mdi mdi-arrow-up"></i></span> <span data-plugin="counterup">{{$users->where(['user_type'=> 1])->count()}}</span></h2>
                                </div>
                            </div>
                        </a>
                    </div><!-- end col -->

                    <div class="col-lg-4 col-md-6">
                        <a href="{{url('admin/users/valet')}}">
                            <div class="card-box widget-box-two widget-two-custom">
                                <i class="mdi mdi-account-multiple-outline widget-two-icon"></i>
                                <div class="wigdet-two-content">
                                    <p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics" style="color:#797979;">Total Valets</p>
                                    <h2 class="font-600"><span><i class="mdi mdi-arrow-up"></i></span> <span data-plugin="counterup">{{$users->where(['user_type'=> 2])->count()}}</span></h2>
                                </div>
                            </div>
                        </a>
                    </div><!-- end col -->

                    <div class="col-lg-4 col-md-6">
                        <a href="{{url('admin/users/customer')}}">
                            <div class="card-box widget-box-two widget-two-custom">
                                <i class="mdi mdi-account-multiple widget-two-icon"></i>
                                <div class="wigdet-two-content">
                                    <p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics" style="color:#797979;">Total Users</p>
                                    <h2 class="font-600"><span><i class="mdi mdi-arrow-up"></i></span> <span data-plugin="counterup">{{$users->where(['user_type'=> 0])->count()}}</span></h2>
                                </div>
                            </div>
                        </a>
                    </div><!-- end col -->

                </div>
                <!-- end row -->


                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Recent Candidates</b></h4>

                            <div class="table-responsive">
                                <table class="table table-hover m-0 table-actions-bar">

                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn btn-secondary btn-xs dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Dropdown link</a>
                                                    <a class="dropdown-item" href="#">Dropdown link</a>
                                                </div>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Job Timing</th>
                                        <th>Salary</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <img src="assets/images/users/avatar-2.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm"  onerror="loadDefaultUserImage(this)"/>
                                        </td>

                                        <td>
                                            <h5 class="m-b-0 m-t-0 font-600">Tomaslau</h5>
                                            <p class="m-b-0"><small>Web Designer</small></p>
                                        </td>

                                        <td>
                                            <i class="mdi mdi-map-marker text-primary"></i> New York
                                        </td>

                                        <td>
                                            <i class="mdi mdi-clock text-success"></i> Full Time
                                        </td>

                                        <td>
                                            <i class="mdi mdi-currency-usd text-warning"></i> 3265
                                        </td>

                                        <td>
                                            <a href="#" class="table-action-btn"><i class="mdi mdi-pencil"></i></a>
                                            <a href="#" class="table-action-btn"><i class="mdi mdi-close"></i></a>
                                        </td>
                                    </tr>
                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div> -->
                <!--- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

    </div>
@endsection    