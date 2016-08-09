<?php session_start();
include 'header.php';

?>
<body class="hold-transition skin-green sidebar-mini">
<div class="container">



<div class="row loginform" >
    <div class="col-md-3"></div>
    <div class=" col-md-6">
                <div class="box-header with-border  text-center">
                  <h3 class="box-title">A &amp; A Power :: OnCall Login</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="username" class="col-sm-2 control-label">Username</label>

                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" placeholder="Username" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                      <div class="col-sm-10">
                        <input id="password" type="password" class="form-control" id="inputPassword3" placeholder="Password" >
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox" style="float: left">
                          <label>
                            <input type="checkbox"> Remember me
                          </label>
                        </div>
                        <a style="float: right" href="forget.php">Forget Password?</a>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-default">Cancel</button>
                    <button id="userLogin" type="button" class="btn btn-success pull-right">Login</button>
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



<script src="bootstrap/js/bootstrap.min.js"></script>




</body>
</html>
