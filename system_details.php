<?php
include 'session_protect.php';
include 'role_check_oncall_user.php';
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';
$sdetaisl = null;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
   echo $query  = $_POST['query'];
    if($query != "")
    $sdetaisl = getRadioEventBySearialNumber($query);
    else
    $sdetaisl = (getSystemDetails());
}
else
{
    $sdetaisl = (getSystemDetails());
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
                    <input id="topsearch" type="text" name="query" class="form-control" placeholder="Search">

                </div>

                <button type="submit" style="width:100px" class="btn btn-success mt-50 text-center pull-left">
                    Search
                </button>
            </form>

            
           </div>
           <div class="col-md-4">
              <div class="form-group">
                <label>Date range</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation">
                </div>
                <!-- /.input group -->
              </div>
           </div>
           <div class="col-md-4">

          
         
              <label>Event Types</label>
           
            <div class="box-body">
              <!-- Minimal style -->

              <!-- checkbox -->
              <div class="form-group">

                  <?php
                  $events = getEventLists();
                  foreach ($events as $event)
                  {
                      ?>
                      <label style="font-weight: normal">
                          <input  type="checkbox" name="event[]" value="<?php echo $event['id'];?> " class=" eventcheckbox" checked>  <?php echo $event['text'];?>
                      </label>
                  <?php
                  }
                  ?>

              </div>

          

          <div class="clear-fix"></div>
          <div class="col-md-4">
           
          </div>

          </div>
         
      </div>

       <!--End Controls -->






      <div class="row  mt-50">
      <div class="col-xs-12">
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">Radio Events</h3>
               <button style="width:60px; margin-left:15px" class="btn btn-danger text-center pull-right">
              Relay 2
            </button>
            <button style="width:60px;margin-left:15px" class="btn btn-warning text-center pull-right">
              Relay 1
            </button>
            </div>
          <?php


          ?>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Event ID</th>
                  <th>Event</th>
                  <th>Ticket Open</th>
                  <th>Date</th>
                  <th>Ticket Status</th>
                  
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($sdetaisl as $detail)
                {
                    echo '<tr>';
                    echo '<td>'.$detail['event_id'].'</td>';
                    echo '<td>'.$detail['text'].'</td>';
                    echo '<td>'.$detail['repairshpr_ticket_number'].'</td>';
                    echo '<td>'.$detail['notification_time'].'</td>';
                    echo '<td>'.$detail['repairshpr_ticket_status'].'</td>';
                    echo '</tr>';
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
    <div class="pull-right hidden-xs">
      <b>Release</b> 1.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://aapower.com">Aapower.com</a>.</strong> All rights
    reserved. | Designed with love by <a href="http://greywindit.com">Greywind IT</a>
  </footer>

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





<!-- jQuery 2.2.3 -->
 <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>




<script src="bootstrap/js/bootstrap.min.js"></script>


<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<script src="plugins/knob/jquery.knob.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>




<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>


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
              var eid = data[0];


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

      $(".eventcheckbox").change(function() {
          selected = new Array();
          $(".eventcheckbox:checked").each(function(){
              selected.push($(this).val());
          });

          table.draw();
      });
  });
</script>


<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>

<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();


    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );



    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>


</body>
</html>
