<?php
session_start();
session_unset();
session_destroy();
header("Location: master_login.php");
exit();
?>