<?php

require 'globals.php';

$validateValue=mysql_escape_string($_GET['fieldValue']);
$validateId=$_GET['fieldId'];



$arrayToJs = array();
$arrayToJs[0] = $validateId;

if($validateValue == 'Username' && $validateId == 'username')
{
    $arrayToJs[1] = false; 
    $arrayToJs[2] = "";
    echo json_encode($arrayToJs); 
    exit;
}

$existBool = User::usernameExists($validateValue);

if($existBool){ 
    $existBool = false; 
}
else{ 
    $existBool = true;    
}
$arrayToJs[1] = $existBool; 
$arrayToJs[2] = "";
echo json_encode($arrayToJs); 


?>
