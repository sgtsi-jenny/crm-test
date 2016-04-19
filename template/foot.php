<?php
if($pageTitle!="Login"):
?>
</div><!-- ./wrapper -->
<?php
endif;

?>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript" src="plugins/datatables/media/js/jszip.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/media/js/buttons.print.min.js"></script>

    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- date-range-picker -->
    <script src="dist/js/moment.js"></script>
    <script src="dist/js/bootstrap-datepicker.js"></script>
    <script src="dist/js/bootstrap-datetimepicker.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap time picker -->
    <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="dist/js/bootstrap-filestyle.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard2.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <script type="text/javascript">
    	$(function(){

    	 $('.select2').select2({
        placeholder:$(this).data("placeholder"),
            allowClear:$(this).data("allow-clear")
		  });
		  $('.select2').each(function(index,element){
		    if(typeof $(element).data("selected") !== "undefined"){
		    $(element).val($(element).data("selected")).trigger("change");
		    }

		  });
        
        //Time picker
        $('.time_picker').timepicker();  
    	//Date range picker
        $('.date_range').daterangepicker();
        //Date range picker with time picker
        $('.date_time_range').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    	});

        $('.date_time_picker').datetimepicker();
        $('.date_time_picker').each(function(index,element){
            if(typeof $(element).data("default") !== "undefined"){
            //$(element).val($(element).data("default")).trigger("change");
            $(element).data("DateTimePicker").defaultDate(new Date($(element).data("default")));

            }

          });

        function query(id){
            $('#modal_comments').modal('show');
            $("#comment_table").html("<span class='fa fa-refresh fa-pulse'></span>")
            $("#comment_table").load("ajax/comments.php?id="+id+"&request_type=<?php echo htmlspecialchars($request_type) ?>");

            $("#request_id").val(id);
        }
        function reject(id){
            $('#modal_reject').modal('show');
            $('#reject_id').val(id);
        }
        // $('#modal_comments').on('show.bs.modal', function (e) {
        //   $("#comment_table").load("ajax/comments.php");
        // })
    </script>
  </body>
</html>