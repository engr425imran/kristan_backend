@section('styles')
<link href="/assets/admin/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                        <h4 class="page-title float-left">Vendor List</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Vendor List</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="m-t-0 header-title"><b>Vendor List</b></h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="m-b-30" style="float:right">
                                    <a href="/admin/addVendors" id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></a>
                                </div>
                            </div>
                        </div>
                                    
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>


                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>Zubair</td>
                                    <td>5185555555</td>
                                    <td>432 State St, Schenectady, NY 12305</td>
                                    <td style="width: 100px;">
                                        <!-- For Active -->
                                        <a href="/admin/changeStatus/0/dp_admin/2?backurl=link" onclick="return confirm('Are you sure? You Want To change');" title="Want to show to user?" class="label label-table label-success">Active</a>
                                        <!-- For Deactive -->
                                        {{--<a href="/admin/changeStatus/0/dp_admin/2?backurl=link" onclick="return confirm('Are you sure? You Want To change');" title="Want to show to user?" class="label label-table label-success">Active</a>--}}
                                    </td>
                                    <td class="actions">
                                        <a href="/admin/addVendors/1" class="on-default edit-row" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil" style="padding-right:25px;"></i></a>
                                        <a href="/admin/deleteVendor/1" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->
</div>
@endsection