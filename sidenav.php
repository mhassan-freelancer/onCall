<?php
require 'includes/config.php';
?>
<li><a href="<?php echo BASE_URL ?>"><i class="fa fa-circle-o text-red"></i> <span>Home</span></a></li>
<?php
if($_SESSION['on_call_is_super_admin'] == 1)
{
    ?>
    <li><a href="system_users.php"><i class="fa fa-circle-o text-yellow"></i> <span>User Manager</span></a></li>
    <li><a href="system_details.php"><i class="fa fa-circle-o text-red"></i> <span>Alarm</span></a></li>
    <li><a href="parameters.php"><i class="fa fa-circle-o text-yellow"></i> <span>Parameter</span></a></li>
    <li><a href="config.php"><i class="fa fa-circle-o text-yellow"></i> <span>Config</span></a></li>
    <li><a href="events.php"><i class="fa fa-circle-o text-yellow"></i> <span>Events</span></a></li>
<!--    <li><a href="account_settings.php"><i class="fa fa-circle-o text-yellow"></i> <span>My Account</span></a></li>-->
    <li><a href="update_page.php"><i class="fa fa-circle-o text-yellow"></i> <span>Settings</span></a></li>
    <?php
}
else if ($_SESSION['on_call_is_admin'] == 1)
{
    ?>
    <li><a href="system_users.php"><i class="fa fa-circle-o text-yellow"></i> <span>User Manager</span></a></li>
    <li><a href="system_details.php"><i class="fa fa-circle-o text-red"></i> <span>Alarm</span></a></li>
<!--    <li><a href="account_settings.php"><i class="fa fa-circle-o text-yellow"></i> <span>My Account</span></a></li>-->

    <?php
}
else if ($_SESSION['on_call_is_oncall'] == 1)
{
    ?>
    <li><a href="system_details.php"><i class="fa fa-circle-o text-red"></i> <span>Alarm</span></a></li>
<!--    <li><a href="account_settings.php"><i class="fa fa-circle-o text-yellow"></i> <span>My Account</span></a></li>-->
    <?php
}?>
<li><a href="account_settings.php"><i class="fa fa-circle-o text-yellow"></i> <span>My Account</span></a></li>
