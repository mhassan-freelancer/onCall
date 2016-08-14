<?php session_start();
include 'header.php';
?>

<body class="hold-transition skin-green sidebar-mini">
<div class="container">
<div class="row loginform" >
    <div class="col-md-3"></div>
    <div class=" col-md-6">
                <div class="box-header  text-center">
                  <h3 class="box-title">A &amp; A Power :: OnCall Login</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form id="loginForm" class="form-horizontal" onsubmit="return processform();">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="username" class="col-sm-2 control-label">Username</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password" class="col-sm-2 control-label">Password</label>

                      <div class="col-sm-10">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" >
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
                    <button id="userLogin" type="submit" class="btn btn-success pull-right">Login</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
    </div>
    <div class="col-md-3"></div>
</div>

</div>
</div>

  <script type="application/javascript">

      function processform() {
        if($("#loginForm").valid()) {
          var username = $.trim($("#username").val());
          var password = $.trim($("#password").val());

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
        return false;
      }

  $("#loginForm").validate({
    rules: {
      username: {
        required: true, normalizer: function( value ) { return $.trim( value ); }
      },
      password : {
        required: true, normalizer: function( value ) { return $.trim( value ); }
      }
    },
    messages: {
      username: "Please enter a username",
      password: "Please provide a password",
    }
  });

</script>
</body>
</html>
