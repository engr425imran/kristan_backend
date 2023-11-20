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
                        <h4 class="page-title float-left">Valet List</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item active">Valet List</li>
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
                    <div class="card-box table-responsive">
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="m-t-0 header-title"><b>Valet List</b></h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="m-b-30" style="float:right">
                                    <a href="/admin/addUsers/" id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></a>
                                </div>
                            </div>
                        </div>
                                    
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Company Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($valets as $index=>$valet)
                                <tr>
                                    <td>{{++$index}}</td>
                                    <td><img src="{{!empty($valet->profile) ? asset($valet->profile) : asset('assets/img/user.jpg')}}" style="width: 50px;height: 50px;" /></td>
                                    <td>{{$valet->first_name.' '.$valet->last_name}}</td>
                                    <td>{{$valet->phone_number}}</td>
                                    <td>{{$valet->company}}</td>
                                    <td class="actions">
                                        <!-- <a href="/admin/addUsers/valet/50" class="on-default edit-row" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil" style="padding-right:25px;"></i></a> -->
                                        <a href="{{url('delete/user',$valet->id)}}" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" onclick="return confirm('Are you sure? You Want To Delete.');"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$valets->links()}}
                    </div>
                </div>
            </div> <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->
</div>
@endsection