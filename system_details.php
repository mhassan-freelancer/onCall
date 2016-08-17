<?php
include 'session_protect.php';
include 'role_check_admin.php';
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';
$sdetaisl = null;
$unit = null;
$unitname = null;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $query  = $_POST['query'];
    $dateRange  = $_POST['date-range'];
    if($query != "" || $dateRange != "")
        $sdetaisl = getRadioEventBySearialNumber($query, $dateRange);
    else
        $sdetaisl = (getSystemDetails());

    $unitname = $_POST['query'];
    $realunitname = getUnitName($unitname);
    if($realunitname)
        $unitname = $realunitname;

}
else
{
    $unit = $_GET['unit'];
    if($unit != null || $unit != "") {
        $unitname = $_GET['unit'];
        $realunitname = getUnitName($unitname);
        if($realunitname)
            $unitname = $realunitname;

        $sdetaisl = getRadioEventBySearialNumber($unit, "");
    }
}

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  
  <div class="container-fluid"> 
    
    <!-- Controls -->
    <div class="row mt-50">
      <div class="col-md-4">
        <form method="post">
          <div class="form-group">
            <label>Search</label>
            <?php
                    $units = getUnits();
                    ?>
            <select name="query" class="form-control select2" style="width: 100%;">
              <option value="">Please Select Unit</option>
              <?php
                        foreach ($units as $unit)
                        {
                            echo '<option  value="'.$unit['serial'].'">'.$unit['unit_name'].'</option>';
                            echo '<option  value="'.$unit['serial'].'">'.$unit['serial'].'</option>';
                        }
                        ?>
            </select>
          </div>
          <div class="form-group">
            <label>Date range</label>
            <div class="input-group">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input type="text" class="form-control pull-right" id="date-range" name="date-range">
            </div>
            <!-- /.input group --> 
          </div>
          <button type="submit" class="btn btn-success text-center"> Search </button>
        </form>
      </div>
      <div class="col-md-6">
        <div class="box-body">
          <label>Event Types</label>
          
          <!-- Minimal style --> 
          
          <!-- checkbox -->
          <div class="form-group">
            <?php
                  $events = getEventLists();
                  foreach ($events as $event)
                  {
                      ?>
            <div class="checkbox checkbox-success">
              <input  type="checkbox" name="event[]" value="<?php echo $event['id'];?> " class="styled eventcheckbox" id="inlineCheckbox2" checked>
              <label for="inlineCheckbox2"> <?php echo $event['text'];?></label>
            </div>
            <?php
                  }
                  ?>
          </div>
          <div class="clear-fix"></div>
          <div class="col-md-4"> </div>
        </div>
      </div>
    </div>
    <!--End Controls -->
    
    <div class="row mt-50">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Radio Events - <?php echo $unitname?></h3>
            <button class="btn btn-danger text-center pull-right" style="margin-top: 5px;margin-right: 10px;"> Relay 2 </button>
            <button class="btn btn-warning pull-right text-center" style="margin-right: 15px; margin-top: 5px;"> Relay 1 </button>
          </div>
          <?php


          ?>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                    <th>Date</th>
                    <th>Event ID</th>
                    <th>Event</th>
                    <th>Ticket Open</th>
                    <th>Ticket Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if($sdetaisl != null)
                {
                    foreach ($sdetaisl as $detail)
                    {
                        echo '<tr>';
                        echo '<td>'.$detail['notification_time'].'</td>';
                        echo '<td>'.$detail['event_id'].'</td>';
                        echo '<td>'.$detail['text'].'</td>';
                        echo '<td>'.$detail['repairshpr_ticket_number'].'</td>';
                        echo '<td>'.$detail['repairshpr_ticket_status'].'</td>';
                        echo '</tr>';
                    }
                }
                ?>
              </tbody>
                </tfoot>
              
            </table>
          </div>
          <!-- /.box-body --> 
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  <div class="pull-right hidden-xs"> <b>Release</b> 1.0 </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://aapower.com">Aapower.com</a>.</strong> All rights
  reserved. | Designed with love by <a href="http://greywindit.com">Greywind IT</a> </footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark"> 
  <!-- Create the tabs -->
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
  </ul>
  <!-- Tab panes --> 
  
</aside>
<!-- /.control-sidebar --> 
<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>

<!-- ./wrapper -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<!-- Morris.js charts -->
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- chart js -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- date range picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

<script>
  $(function () {
    var table = $("#example1").DataTable();

      $.fn.dataTable.ext.search.push(
          function( settings, data, dataIndex ) {

            var eventname =  $("#topsearch").val();
              var ed = data[1];
              var isfound = false;
  /*
              if  (( ed.indexOf(eventname) > -1 ))
              {
                 isfound = true;
              }
*/
              var eid = data[1];
              $.each(selected , function (i, val) {

                  if ( parseInt(eid) == parseInt(val,10)) {isfound =  true ; console.log("xxxx")}
              });
               console.log(isfound);
              return isfound;

          }

      );
      $('#topsearch').keyup( function() {
          table.draw();
      } );
      var selected = new Array();
      selected = new Array();
      $(".eventcheckbox:checked").each(function(){
          selected.push($(this).val());
      });
      $(".eventcheckbox").change(function() {
          selected = new Array();
          $(".eventcheckbox:checked").each(function(){
              selected.push($(this).val());
          });
          table.draw();
      });
  }); 
</script>

<!-- Page script --> 
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    //Date range picker
    $('#date-range').daterangepicker({
        startDate: moment().subtract(1, 'month'),
        endDate: moment()
    });
  });
</script>

</body>
</html>