<?php
/**
 * fetchNotifications AJAX 
 * 
 * @author Jaco Ruit
 */
require '../startOrongo.php';
startOrongo();

define("NOT_LOGGED_IN", 1);

function errorDie($paramError, $paramErrorCode){
    $arrayToJs = array();
    $arrayToJs["response"] = $paramError;
    $arrayToJs["response_code"] = $paramErrorCode; 
    die(json_encode($arrayToJs));
}

if(getUser() == null){
    errorDie("Not logged in!", NOT_LOGGED_IN);
}

$arrayToJs = array();
$arrayToJs["notifications"] = array();
$count = 0;
foreach(getUser()->getNotifications() as $notification){
    if(($notification instanceof OrongoNotification) == false) continue;
    $arrayToJs["notifications"][$count] = array(
        "title" => $notification->getTitle(),
        "text" => $notification->getText(),
        "time" => $notification->getTime(),
        "image" => $notification->getImage()
    );
    $count++;
}
$arrayToJs["newNotifications"] = $count > 0 ? true : false;
die(json_encode($arrayToJs));
?>
