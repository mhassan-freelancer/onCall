<?php
include 'session_protect.php';
include 'role_check_administrator.php';
include 'header.php';
include 'header_loggedin.php';
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <?php
    if(isset($_SESSION['error']))
    {
      if(is_array($_SESSION['error']))
      {
        ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
          <?php
          foreach ($_SESSION['error'] as $val)
          {
            echo $val."<br/>";
          }
          $_SESSION['error'] = null;
          ?>
        </div>
        <?php
      }
      else
      {
        ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
          <?php
          echo $_SESSION['error'];
          $_SESSION['error'] = null;
          ?>
        </div>
        <?php
      }
    }
    else if(isset($_SESSION['success']))
    {
      ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        <?php
        echo $_SESSION['success'];
        $_SESSION['success'] = null;
        ?>
      </div>
      <?php
    }
    ?>

    <?php
    require "includes/functions.php";
    $parameters = getParameters();
    ?>
    <div class="container-fluid">
      <div class="row mt-50">
        <div class="col-xs-12">
          <div class="box">
              <div class="box-header">
                <h3 class="box-title">Parameters</h3>
              </div>

              <!-- /.box-header -->
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Parameter</th>
                      <th>Label</th>
                      <th>DataType</th>
                      <th>Value</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($parameters as $parameter)
                    {
                      echo '<tr>';
                      echo '<td>'.$parameter['parameter'].'</td>';
                      echo '<td>'.$parameter['label'].'</td>';
                      echo '<td>'.$parameter['data_type'].'</td>';
                      echo '<td>'.$parameter['data_type_values'].'</td>';
                      echo '<td><a class="btn btn-danger" href="parameter_edit.php?id='.$parameter['parameter_id'].'">Update</a> 
                      <a href="bll/parameter_delete.php?id='.$parameter['parameter_id'].'" class="btn btn-default">Delete</a></td></tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
          <!-- /.box-body -->
          </div>
          <a style="width: 150px; height: 50px; font-size: 16px;" class="btn btn-success" href="parameter_add.php">Add Parameter</a>
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


</body>
</html>
