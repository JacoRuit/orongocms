<?php
/**
 * @author Jaco Ruit
 */
$startOrongoFile = "../../../../startOrongo.php";
if(!file_exists($startOrongoFile)) die("500");
require $startOrongoFile;

if(!isset($_POST['app_name']) || !isset($_POST['app_']))
?>
