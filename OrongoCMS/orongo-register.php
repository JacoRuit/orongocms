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
            $msg = 'Passwords do not match.';
            break; 
        case 1:
            $msg = 'Username exists already.';
            break;
        case 2:
            $msg = 'Invalid username length. Username must be min. 4 characters max. 20 characters long.';
            break;
        case 3:
            $msg = 'Invalid password length. Password must be min. 6 characters long.';
            break;
        case 4:
            $msg = 'Please fill in an username!';
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

$smarty->assign("head_title", $website_name . " - Register");
$smarty->assign("website_url", Settings::getWebsiteURL());
$smarty->assign("website_name", $website_name);
$smarty->assign("document_ready", '$("#register_form").validationEngine(\'attach\'); ' . $msgJQuery);
$smarty->assign("register_msg", '<div id="prettyAlert"></div>');
$smarty->assign("style", "style.login");
$smarty->display("header.orongo");
$smarty->display("register.orongo");
$smarty->display("footer.orongo");
?>
