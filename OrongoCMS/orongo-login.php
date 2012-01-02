<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';
$smarty->template_dir = "orongo-admin/style/"; 

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
$user = handleSessions();
if($user != null){ header("Location: orongo-admin"); exit; }


$website_name = Settings::getWebsiteName();
$msgJQuery = "";
if($msg != "")
{
    $msgJQuery = "prettyAlert('#prettyAlert', '" . $msg . "', '" . $website_name . "');";
}

#   Template

$smarty->assign("head_title", $website_name . " - Login");
$smarty->assign("website_name", $website_name);
$smarty->assign("website_url", Settings::getWebsiteURL());
$smarty->assign("document_ready", $msgJQuery);
$smarty->assign("style", "style.login");
$smarty->assign("login_msg", '<div id="prettyAlert"></div>');
$smarty->display("header.orongo");
$smarty->display("login.orongo");
$smarty->display("footer.orongo");
?>
