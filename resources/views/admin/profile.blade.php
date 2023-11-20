@extends('layouts.app')
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Admin Profile</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Admin</a></li>
                            <li class="breadcrumb-item active">Profile</li>
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
                        <h4 class="m-t-0 header-title"><b>Admin Profile</b></h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" action="{{url('update-profile')}}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">First Name</label>
                                            <div class="col-10">
                                                <input type="text" name="first_name" class="form-control" placeholder="Type your name" value="{{auth()->user()->first_name}}">
                                                @error('auth()->user()->last_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Last Name</label>
                                            <div class="col-10">
                                                <input type="text" name="last_name" class="form-control" placeholder="Type your name" value="{{auth()->user()->last_name}}">
                                                @error('last_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Email</label>
                                            <div class="col-10">
                                                <input type="email" name="email" class="form-control" placeholder="Type your email" value="{{auth()->user()->email}}">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Password</label>
                                            <div class="col-10">
                                                <input type="password" name="password" class="form-control" placeholder="Please enter password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Mobile</label>
                                            <div class="col-10">
                                                <input type="tel" name="phone" value="{{auth()->user()->phone_number}}" class="form-control" value="" maxlength="13" minlength="10" placeholder="Mobile"/>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <div class="col-8 offset-4">
                                                <button class="btn btn-primary waves-effect waves-light" href="/admin/profile" type="submit">
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