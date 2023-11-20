@section('styles')
<link href="/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endsection
@extends('layouts.app')
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">All users Report</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                All users Report
                            </li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
                <div class="row" id="searchData">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Select date</h4>
                            

                            <div class="row">
                                <div class="col-lg-8">

                                    <div class="">
                                        <form method="post" name="form">

                                            <div class="form-group">
                                                <label>Date Range</label>
                                                <div class="">
                                                    <div class="input-daterange input-group" id="date-range">
                                                        <input type="text" class="form-control" name="start" id="start_date"/>
                                                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                                                        <input type="text" class="form-control" name="end" id="end_date"/>
                                                        <span class="input-group-btn">
                                                            <a href="javascript:;" data-target="#reportData" onClick="generateReport('user');" class="btn waves-effect waves-light btn-primary">Submit</a>
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- end row -->
                <div class="row" id="reportData" style="display:none;">
                    <div class="col-12">
                        <div class="card-box table-responsive">
                                        
                            <table id="datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody id="datatableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

    </div>
</div>
@endsection

