<link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet" />
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Edit Gate</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/organisationGate/<?php echo $this->session->userdata('admin_id');?>">Gate List</a></li>
                            <li class="breadcrumb-item active">Edit Gate</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Edit Gate</b></h4>

                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label">Name</label>
                                            <div class="col-10">
                                                <input type="text" name="gate_name" class="form-control" placeholder="Name" value="<?php if(isset($row->gate_name)){ echo $row->gate_name; }?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-2 col-form-label" for="example-email">Location</label>
                                            <div class="col-10">
                                                <input type="text" name="gate_location" id="pac-inputs" class="form-control" placeholder="Type location" value="<?php if(isset($row->gate_location)){ echo $row->gate_location; }?>">
                                                <input type="hidden" id="latitude" name="latitude"/>
                                                <input type="hidden" id="longitude" name="longitude"/>
                                                <div id="myMap" style="height:400px;width:100%"></div>
                                                <div id="overview-map" style="bottom: 148px;left: 60px;" onClick="changeMap();"></div>
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
<script type="text/javascript">
     addGateInitializeMap('<?php echo $this->session->userdata('latitude');?>','<?php echo $this->session->userdata('longitude');?>');
    $('#pac-inputs').keypress(function(e) {
          if (e.keyCode == '13') {
             e.preventDefault();
             //your code here
           }
        });
     </script>