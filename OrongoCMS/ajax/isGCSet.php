<?php
/**
 * isCookieSet AJAX
 * @author Jaco Ruit 
 */
session_start();

$JSON = array();

$JSON["bool"] = isset($_SESSION['auth-sub-token']);

die(json_encode($JSON));

?>
