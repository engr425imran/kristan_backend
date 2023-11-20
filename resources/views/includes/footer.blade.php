
<!-- END wrapper -->

<!-- jQuery  -->
<script src="{{asset('assets/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/js/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/admin/js/waves.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.slimscroll.js')}}"></script>

<!-- Counter js  -->
<!--<script src="../plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="../plugins/counterup/jquery.counterup.min.js"></script>-->

<!-- Dashboard init -->
<!--<script src="assets/admin/js/jquery.dashboard.js"></script>-->

<!-- App js -->
<!-- <script src="assets/admin/js/jquery.core.js"></script>-->
<script src="{{asset('assets/admin/js/jquery.app.js')}}"></script>
<!-- Toaster -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{{--<script type="text/javascript">
    <?php if($this->session->flashdata('success')){ ?>
        toastr.success("<?php echo $this->session->flashdata('success'); ?>");
    <?php }else if($this->session->flashdata('error')){  ?>
        toastr.error("<?php echo $this->session->flashdata('error'); ?>");
    <?php }else if($this->session->flashdata('warning')){  ?>
        toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
    <?php }else if($this->session->flashdata('info')){  ?>
        toastr.info("<?php echo $this->session->flashdata('info'); ?>");
    <?php } ?>
</script>--}}

<script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('assets/admin/plugins/jquery.filer.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/bootstrap-fileupload/bootstrap-fileupload.js')}}"></script>
<script src="/assets/admin/js/jquery.fileuploads.init.js"></script>

<script src="{{asset('assets/admin/plugins/timepicker/bootstrap-timepicker.js')}}"></script>
<script src="{{asset('assets/admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>


<script src="{{asset('assets/admin/js/jquery.form-pickers.init.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );

</script>