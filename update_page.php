<?php 
include 'session_protect.php';
include 'role_check_administrator.php';
include 'header.php';
include 'header_loggedin.php';
require __DIR__.'/includes/functions.php';
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST['uplinkManager']))
    {

        if($_POST['uplinkManager'] == 1)
        {
            controlOncallDaemon("uplinkmgrd", "start");
        }
        else
        {
           (controlOncallDaemon("uplinkmgrd", "stop"));
        }
    }
    if(isset($_POST['fieldServicesManager']))
    {
        if($_POST['fieldServicesManager'] == 1)
        {
            controlOncallDaemon("fieldsvcmgrd", "start");
        }
        else
        {
            controlOncallDaemon("fieldsvcmgrd", "stop");
        }
    }
}

$moduleid = 0;
if(isset($_GET['moduleid']))
{
    $moduleid = $_GET['moduleid'];
}
?>


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->


      <?php
      if(isset($_SESSION['error']))
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

      }else if(isset($_SESSION['success']))
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
      

      <div class="container-fluid">

      <div class="box box-success mt-50">
            <div class="box-header">
              <h3 class="box-title">System Status</h3>
            </div>
        <div class="row" style="font-size: 18px; font-weight: bold;">
        <form method="post">
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        Uplink Interface:
                    </label>
                    <label style="color: green">
                        <input type='hidden' value='0' name='uplinkManager'>
                        <?php
                        $status =  checkDaemonProcess("uplinkManager");
                        if($status == 1){
                            echo ' <input class="systemtoggle1" type="checkbox" data-toggle="toggle" name="uplinkManager" value="1" checked="checked">';

                            echo "Running";
                        }

                        else{
                            echo ' <input class="systemtoggle1" type="checkbox" data-toggle="toggle" name="uplinkManager" value="1">';

                            echo "Stop";}
                        ?>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        Automation Daemon:
                    </label>
                    <label style="color: green">
                        <input type='hidden' value='0' name='fieldServicesManager'>
                        <?php
                        $status =  checkDaemonProcess("fieldServicesManager");
                        if($status == 1){
                            echo ' <input class="systemtoggle2" type="checkbox" data-toggle="toggle" name="fieldServicesManager" value="1" checked="checked">';
                            echo "On";}
                        else
                        {
                            echo ' <input class="systemtoggle2" type="checkbox" data-toggle="toggle" name="fieldServicesManager" value="1">';

                            echo "Off";}
                        ?>
                    </label>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>


          <div class="col-md-4"></div>
        </div>
      </div>

      <div class="box box-success" style="margin-top:25px">
            <div class="box-header">
              <h3 class="box-title">System Parameters</h3>
            </div>
        <div class="row" style="font-size: 18px; font-weight: bold;">


        <div class="col-md-12">
                 <div class="form-group" style="margin-left: 15px; font-weight:normal !important; width: 300px">
                <label>Module</label>
                     <?php
                     $modules = getModules();
                     ?>
                     <select name="module" class="form-control select2" style="width: 100%;">

                         <option value="0">Please select the module</option>
                         <?php
                         foreach ($modules as $module)
                         {
                             if($module['id']== $moduleid)
                             echo '<option selected="selected" value="'.$module['id'].'">'.$module['name'].'</option>';
                             else
                             echo '<option  value="'.$module['id'].'">'.$module['name'].'</option>';

                         }
                     ?>
                     </select>

              </div>
            <div class="col-md-12">
                <form method="post" action="bll/update_page.php">
                    <input type="hidden" name="moduleid" value="<?php echo $moduleid ?>" >
                    <div class="box-body">
                        <?php
                        $configurations = getModuleConfig($moduleid);
                        foreach ($configurations as $config)
                        {
                            ?>
                            <div class="form-group">
                                <label for="firstname"><?php echo $config['label']?></label>
                                <?php if($config['data_type'] == "TEXT")
                                {?>

                                    <input name="config-<?php echo $config['id'] ?>" type="text" class="form-control"  value="<?php echo $config['value']; ?>">
                                <?php
                                }  if($config['data_type'] == "DROP_DOWN")
                                {

                                    $range = $config['data_type_values'];

                                    $range = explode(',',$range );
                                    if(!$config['allow_decimal'])
                                    {
                                    ?>
                                    <input name="config-<?php echo $config['id'] ?>" class="form-control" type="number" value="<?php echo $range[0] ?>"
                                           min="<? echo $range[0] ?>" max="<?php echo $range[1] ?>" step="1"/>
                                        <?php
                                    }
                                   else
                                    {
                                    ?>
                                    <input name="config-<?php echo $config['id'] ?>" class="form-control" type="number" value="<?php echo $range[0] ?>"
                                           min="<?php echo $range[0] ?>" max="<?php echo $range[1] ?>" step="any"/>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                            <?php
                        }
                        ?>


                    </div>
                    <div class="box-footer">
                                <br/>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>

              


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
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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

<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>





<!-- jQuery 2.2.3 -->
 <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>


<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>

<script src="dist/js/app.min.js"></script>

<script src="plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>


<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

<script src="plugins/chartjs/Chart.min.js"></script>


<script src="dist/js/demo.js"></script>



<script src="bootstrap/js/bootstrap.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>

<script src="plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<script src="plugins/knob/jquery.knob.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>



<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>



<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>

<script src="dist/js/app.min.js"></script>

<script src="dist/js/demo.js"></script>

<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>

<script src="dist/js/app.min.js"></script>

<script src="dist/js/demo.js"></script>


</script>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

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
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script>
    $(function () {
        $('input').on('ifChecked', function(event){ alert(event.type + ' callback'); });

        $(".select2").select2().on("change", function(e) {
            if($(".select2").val()>0);
            window.location.href = "update_page.php?moduleid="+$(".select2").val();
        });
        $(".select3").select2();
    });
    $(".select2-search__field").keypress(function (e) {
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            alert("Digits Only");
            return false;
        }
    });
    $('.systemtoggle1').on('ifChanged', function (event) { $(event.target).trigger('change');

    });

    $(".systemtoggle1").change(function ()
    {
        window.location.href = "update_page.php?uplinkManager="+$(this).val();
    });
    $(".systemtoggle2").change(function ()
    {
        window.location.href = "update_page.php?fieldServicesManager="+$(this).val();
    });

</script>

</body>
</html>
