@extends('layouts.app')
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Site Setting</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Site Setting</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            @if(session()->has('success'))
                <div class="alert alert-success">
                {{ session()->get('success') }}
                </div>
            @endif 


            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Site Setting</b></h4>

                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" action="{{url('save-setting')}}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Site Title</label>
                                            <div class="col-10">
                                                <input type="text" value="{{!empty($setting) ? $setting->title : ''}}" name="site_title" class="form-control" placeholder="Site title" >
                                                @error('site_title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Email</label>
                                            <div class="col-10">
                                                <input type="email" name="email" class="form-control" placeholder="Type email" value="{{!empty($setting) ? $setting->email : ''}}">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Contact Number</label>
                                            <div class="col-10">
                                                <input type="tel" name="contact_number" value="{{!empty($setting) ? $setting->number : ''}}" class="form-control" value="" maxlength="13" minlength="10" placeholder="Mobile"/>
                                                @error('contact_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <div class="col-8 offset-4">
                                                <button class="btn btn-primary waves-effect waves-light" href="/admin/setting" type="submit">
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