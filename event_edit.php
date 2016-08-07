<?php include 'session_protect.php';

$eventId= 0;
if(isset($_GET['id']))
{
    $eventId = $_GET['id'];
}
require  'vendor/autoload.php';
use Respect\Validation\Validator as v;

if(!v::numeric()->validate($eventId))
{
    $_SESSION['error'] ="Invalid Event ID.";
    header('location:/onCall/events.php');
}

if(!v::intVal()->min(1,true)->validate($eventId))
{
    $_SESSION['error'] ="Invalid Event ID.";
    header('location:/onCall/events.php');
}
if(isset($_SESSION['on_call_u_id']))
{

}
else {
    //header("location:/login.php?error=loginrequired");
}
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';
?>
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
    $eventInfo = getEventInfo($eventId);
    if($eventInfo == null)
    {
        $_SESSION['error'] ="Invalid Event ID.";
        header('location:/onCall/events.php');
    }
    ?>

    <section class="content-header">
        <h1>
            Update Event
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update Event Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="bll/events/event_edit.php">
                        <input type="hidden" id="eventId" name="eventId" value="<?php echo $eventId ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="event">Event</label>
                                <textarea class="form-control" id="event"  name="event" placeholder="Event Text">
                                    <?php echo $eventInfo['text'] ?>
                                </textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
