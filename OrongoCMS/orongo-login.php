<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';

getDisplay()->setTemplateDir("orongo-admin/style/"); 

$msg = '';

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



#handle orongo-id, orongo-session-id
$user = getUser();
if($user != null){ header("Location: orongo-admin"); exit; }


if($msg != "")
{
    getDisplay()->addObject(new MessageBox($msg));
}

#   Template

getDisplay()->setTemplateVariable("head_title", Settings::getWebsiteName() . " - Login");

getDisplay()->setTemplateVariable("document_ready", "");
getDisplay()->setTemplateVariable("style", "style.login");

getDisplay()->add("header.orongo");
getDisplay()->add("login.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();
?>
