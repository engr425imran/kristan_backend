@extends('layouts.app')
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">About</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Admin</a></li>
                            <li class="breadcrumb-item">CMS</li>
                            <li class="breadcrumb-item active">About</li>
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
                        <h4 class="m-t-0 header-title"><b>About</b></h4>

                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" action="{{url('save-about')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="">
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Page Name</label>
                                            <div class="col-10">
                                                <input type="text" value="{{!empty($about) ? $about->title : ''}}" name="title" class="form-control" placeholder="Page Title" value="">
                                                @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Page Description </label>
                                            <div class="col-10">
                                                <textarea class="ckeditor form-control" name="description" placeholder="Enter text here" rows="10">{{!empty($about) ? $about->description : ''}}</textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
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
@section('scripts')
<script src="/assets/admin/js/ckeditor/ckeditor.js"></script>
<script>
    editor = CKEDITOR.replace( 'description', {
    });
</script>
@endsection