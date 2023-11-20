@extends('layouts.app')
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Add Vendor</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/vendor">Vendor List</a></li>
                            <li class="breadcrumb-item active">Add Vendor</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Add Vendor</b></h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" role="form" method="post">
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Name</label>
                                            <div class="col-10">
                                                <input type="text" name="name" class="form-control" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Email</label>
                                            <div class="col-10">
                                                <input type="email" name="email" class="form-control" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Password</label>
                                            <div class="col-10">
                                                <input type="password" name="password" class="form-control" placeholder="Enter Password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Contact Number</label>
                                            <div class="col-10">
                                                <input type="tel" name="phone" class="form-control" maxlength="13" minlength="10" pattern="[0-9]{10}" id="onlunumberInput" onkeyup="onlynumbers(this);" title="Please enter numeric value" placeholder="Mobile"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Address</label>
                                            <div class="col-10">
                                                <input type="text" name="address" id="pac-inputs" class="form-control" placeholder="Address" onkeypress="googleAutoComplete();"/>
                                                <input type="hidden" id="latitude" name="latitude"/>
                                                <input type="hidden" id="longitude" name="longitude"/>
                                            </div>
                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <div class="col-8 offset-4">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div> <!-- end card-box -->
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>
@endsection