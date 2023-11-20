<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left">Gate List</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Dashboard</a></li>
                            <li class="breadcrumb-item active">Gate List</li>
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
                                <h4 class="m-t-0 header-title"><b>Gate List</b></h4>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="m-b-30" style="float:right">
                                    <a href="<?php echo base_url();?>admin/addGate" id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></a>
                                </div>
                            </div>
                        </div>
                                    
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach ($result as $row) { $i++;?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row->gate_name;?></td>
                                    <td><?php echo $row->gate_location;?></td>
                                    <td style="width: 100px;">
                                        <?php if($row->status==0){?>
                                        <a href="<?php echo base_url(); ?>admin/changeStatus/1/dp_org_gate/<?php echo $row->id; ?>?backurl=<?php echo $back_link;?>" onclick="return confirm('Are you sure? You Want To change');" title="Want to hide for user?" class="label label-table label-danger">Deactive</a>
                                        <?php }else{  ?>
                                        <a href="<?php echo base_url(); ?>admin/changeStatus/0/dp_org_gate/<?php echo $row->id; ?>?backurl=<?php echo $back_link;?>" onclick="return confirm('Are you sure? You Want To change');" title="Want to show to user?" class="label label-table label-success">Active</a><?php } ?>
                                    </td>
                                    <td class="actions">
                                        <a href="<?php echo base_url();?>admin/addGate/<?php echo $row->id;?>" class="on-default edit-row" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil" style="padding-right:25px;"></i></a>
                                        <a href="<?php echo base_url();?>admin/deleteGate/<?php echo $row->id;?>" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" onclick="return confirm('Are you sure? You Want To Delete.');"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->
</div>
