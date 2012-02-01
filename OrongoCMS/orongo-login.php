<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();

$msg = null;

if(getUser() != null)
    header("Location: orongo-admin");

if(isset($_GET['msg'])){
    $msgCode = Security::escape($_GET['msg']);
    switch($msgCode){
        case 0:
            $msg = 'Wrong username or password.';
            break;
        case 1:
            $msg = 'You have been successfully logged out!';
            break;
        case 2:
            $msg = 'Registration succesful. We have sent you an activation email.';
            break;
        case 3:
            $msg = 'An error occured while processing your registration. Please try again.';
            break;
        case 4:
            $msg = 'Malformed activation URL!';
            break;
        case 5:
            $msg = 'Your account has already been activated! You may login now.';
            break;
        case 6:
            $msg = 'Activation succesful. You may login now.';
            break;
        case 7:
            $msg = 'You need to activate your account first.';
            break;
        default:
            break;
    }
}

$login = new LoginFrontend();
$login->main(array("time" => time(), "msg" => $msg));
$login->render();

?>
