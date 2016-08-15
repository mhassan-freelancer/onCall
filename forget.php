<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'header.php';
require  'vendor/autoload.php';
use Respect\Validation\Validator as v;

if($_SERVER['REQUEST_METHOD']=="POST")
{

  $recoveryEmail = $_POST['email'];
  if(!v::email()->validate($recoveryEmail))
  {
    ?>
      <script>alert("Invalid Email");</script>
<?php
  } 

  require 'includes/PasswordStorage.php';
  require ('includes/functions.php');
  include 'includes/config.php';
  $db = new DB();
  $password = generateStrongPassword(8);
  $hash = PasswordStorage::create_hash($password);
  $db->bind("email",$recoveryEmail );
  $person = $db->row("select * from user where email = :email ");
  if($person)
  {
    $db->bind("email",$recoveryEmail );
    $db->bind("password",$hash );
    $db->query("update user set password =:password where email = :email");

    require __DIR__.PHP_MAILER;
    $mail = new PHPMailer;
    $mail->isSMTP();
    //$mail->SMTPDebug = 2;
    // $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "asimoncall@gmail.com";
    $mail->Password = "asim12345";
    $mail->setFrom('asimizb@gmail.com', 'OnCall Support');
    $mail->addAddress($recoveryEmail, $person['first_name']." ".$person['last_name']);
    $mail->Subject = 'OnCall Password Recover';
    $mail->msgHTML("Your new Password is ".$password);
    if (!$mail->send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
      ?>
      <script>alert("Something went wrong Contact us support@oncall.com");location.replace("login.php");</script>
      <?php

    } else {
      ?>
      <script>alert("Check your email"); location.replace("login.php");</script>

      <?php
    }

  }
  else
  {
    ?>
    <script>alert("Your email not found" ); </script>
    <?php
  }


}
?>
<body class="hold-transition skin-green sidebar-mini">
<div class="container">



<div class="row loginform" >
    <div class="col-md-3"></div>
    <div class=" col-md-6">
                <div class="box-header  text-center">
                  <h3 class="box-title">OnCall Password Recovery</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                      <div class="col-sm-10">
                        <input name="email" id="username" type="email" class="form-control"  placeholder="Email" >
                      </div>
                    </div>

                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">

                    <button id="passwordRecover" type="submit" class="btn btn-success pull-right">Recover My Password</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
    </div>
    <div class="col-md-3"></div>
</div>

</div>
</div>
  <!-- /.content-wrapper -->
 

  <!-- Control Sidebar -->
 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->








<!-- ./wrapper -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

  $(document).ready(function () {
    $("#userLogin").click(function () {

      $(this).prop('disabled', true)

      var username = $("#username").val();
      var password = $("#password").val();
      var error = false;
      if(username  == "" || username == null)
      {
        error = true;
      }
      if(password == "" || password == null)
      {
        error = true;
      }
      if(error)
      {
        alert("Kindly fill the username and password");
        return false;
      }
      else
      {

        $(this).prop('disabled', false)
        $.ajax({
          url:"bll/login.php",
          type:"POST",
          data:{username:username,password:password},
          success:function (data) {
            console.log(data);
            data = JSON.parse(data);
            if(data.message == "success")
            {
              window.location.href = "index.php";


            }
            else {
              alert(data.message);
            }
          }
        });
      }
    });
  })
</script>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>



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

<!-- jQuery 2.2.3 -->
 <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>


<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>


<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>

<script src="dist/js/app.min.js"></script>

<script src="plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>


<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

<script src="plugins/chartjs/Chart.min.js"></script>

<script src="dist/js/pages/dashboard2.js"></script>

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

<script src="dist/js/app.min.js"></script>

<script src="dist/js/pages/dashboard.js"></script>

<script src="dist/js/demo.js"> </script>


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

</body>
</html>
