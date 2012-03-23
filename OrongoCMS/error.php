<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo('error');

$errorCodes = array(
    400,
    401,
    403,
    404, 
    500,
    503
);

if(!isset($_GET['error_code']) || !in_array($_GET['error_code'], $errorCodes)){ header("Location: " . orongoURL("index.php")); exit; }


$articleFO = new ErrorFrontend();
$articleFO->main(array("time" => time(), "error_code" => $_GET['error_code']));
$articleFO->render();


?>