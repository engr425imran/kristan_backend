@section('styles')
<link href="/assets/admin/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet" />
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
                        <h4 class="page-title float-left">Edit Customer</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/users/"> List</a></li>
                            <li class="breadcrumb-item active">Edit Customer</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Edit Customer</b></h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Name</label>
                                            <div class="col-10">
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Email</label>
                                            <div class="col-10">
                                                <input type="email" name="email" class="form-control" placeholder="Type email" value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Password</label>
                                            <div class="col-10">
                                                <input type="password" name="password" class="form-control" placeholder="Password" value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Contact Number</label>
                                            <div class="col-10">
                                                <input type="tel" name="phone" class="form-control" value="" maxlength="13" minlength="10" placeholder="Mobile" onkeyup="onlynumbers(this);"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Image Upload</label>
                                            <div class="col-10">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new thumbnail text-left" style="width: 200px; height: 150px;">
                                                        <img src="/assets/img/no.jpg" alt=""/>
                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                    <div>
                                                        <button type="button" class="btn btn-secondary btn-file">
                                                            <input type="file" name="image" value="" accept="image/*">
                                                            <span class="fileupload-new"><i class="fa fa-paper"></i> Select image</span>
                                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                                            
                                                            </span>
                                                        </button>
                                                        <button class="btn btn-danger fileupload-exists" data-dismiss="fileupload">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <div class="col-8 offset-4">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                                    Update
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