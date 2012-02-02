<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();

$msg = null;
$msgtype = null;
if(getUser() != null)
    header("Location: orongo-admin");

if(isset($_GET['msg'])){
    $msgCode = Security::escape($_GET['msg']);
    switch($msgCode){
        case 0:
            $msg = 'Wrong username or password.';
            $msgtype = "error";
            break;
        case 1:
            $msg = 'You have been successfully logged out!';
            $msgtype = "success";
            break;
        case 2:
            $msg = 'Registration succesful. We have sent you an activation email.';
            $msgtype = "info";
            break;
        case 3:
            $msg = 'An error occured while processing your registration. Please try again.';
            $msgtype = "warning";
            break;
        case 4:
            $msg = 'Malformed activation URL!';
            $msgtype= "warning";
            break;
        case 5:
            $msg = 'Your account has already been activated! You may login now.';
            $msgtype = "info";
            break;
        case 6:
            $msg = 'Activation succesful. You may login now.';
            $msgtype = "success";
            break;
        case 7:
            $msg = 'You need to activate your account first.';
            $msgtype = "warning";
            break;
        default:
            break;
    }
}

$login = new AdminFrontend();
$login->main(array("time" => time(), "page" => "login"));
if($msg != null) $login->addMessage($msg, $msgtype);
$login->render();

?>
