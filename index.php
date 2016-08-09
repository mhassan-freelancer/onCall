<?php
include 'session_protect.php';
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

          <div class="col-lg-3 col-xs-6">
              <?php
              $totalUnits = getTotalUnits();
              ?>
              <!-- small box -->
              <div class="small-box bg-blue">
                  <div class="inner">
                      <h3><?php echo $totalUnits ?></h3>

                      <p>Total Systems</p>
                  </div>
                  <div class="icon">
                      <i class="ion ion-gear-a"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <?php
                $criticalSystems = getCriticalUnits();
            ?>
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $criticalSystems ?></h3>

              <p>Critical Systems</p>
            </div>
            <div class="icon">
              <i class="ion ion-gear-a"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53</h3>

              <p>Systems Registered</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-list"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>Open Tickets</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-open"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
    
      <!-- /.row (main row) -->

    </section>

      

      <div class="container-fluid">
      <div class="row">
      <div class="col-xs-12">
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">Critical Systems</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>System ID</th>
                  <th>Days Down</th>
                  <th>Ticket Open</th>
                  <th>Last Alert</th>
                  
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>2593 Giardano</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2813 Herron</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>4160 Bertot</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>3423 Alferdo Gilbert</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2593 Giardano</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2813 Herron</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>4160 Bertot</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>3423 Alferdo Gilbert</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr> <tr>
                  <td>2593 Giardano</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2813 Herron</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>4160 Bertot</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>3423 Alferdo Gilbert</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr> <tr>
                  <td>2593 Giardano</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2813 Herron</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>4160 Bertot</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>3423 Alferdo Gilbert</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr> <tr>
                  <td>2593 Giardano</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>2813 Herron</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>4160 Bertot</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
                 <tr>
                  <td>3423 Alferdo Gilbert</td>
                  <td>8
                  </td>
                  <td>2570075</td>
                  <td> 04/18/2016</td>
                  
                </tr>
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

<!-- ./wrapper -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
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
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script src="dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Charts -->
<script src="plugins/chartjs/Chart.min.js"></script>

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

</body>
</html>

