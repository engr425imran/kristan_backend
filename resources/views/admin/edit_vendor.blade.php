<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Edit Vendor</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/vendor">Vendor List</a></li>
                            <li class="breadcrumb-item active">Edit Vendor</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Edit Vendor</b></h4>

                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Name</label>
                                            <div class="col-10">
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="<?php if(isset($row->name)){ echo $row->name; }?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Email</label>
                                            <div class="col-10">
                                                <input type="email" name="email" class="form-control" placeholder="Type email" value="<?php if(isset($row->email)){ echo $row->email; }?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Password</label>
                                            <div class="col-10">
                                                <input type="password" name="password" class="form-control" placeholder="Password" value="<?php if(isset($row->password)){ echo $row->password; }?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Contact Number</label>
                                            <div class="col-10">
                                                <input type="tel" name="phone" class="form-control" value="<?php if(isset($row->phone)){ echo $row->phone; }?>" onkeyup="onlynumbers(this);" maxlength="13" minlength="10" placeholder="Mobile"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Address</label>
                                            <div class="col-10">
                                                <input type="text" name="address" id="pac-inputs" class="form-control" placeholder="Address" onkeyup="googleAutoComplete();" value="<?php if(isset($row->address)){ echo $row->address; }?>"/>
                                                <input type="hidden" id="latitude" name="latitude" value="<?php if(isset($row->latitude)){ echo $row->latitude; }?>"/>
                                                <input type="hidden" id="longitude" name="longitude" value="<?php if(isset($row->longitude)){ echo $row->longitude; }?>"/>
                                            </div>

                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <div class="col-8 offset-4">
                                                <button class="btn btn-primary waves-effect waves-light" href="<?php echo base_url();?>admin/addVendors" type="submit">
                                                    Update
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-box -->
                </div><!-- end col -->
            </div>
        </div> <!-- container -->
    </div> <!-- content -->
</div>