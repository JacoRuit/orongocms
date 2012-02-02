<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();
getDisplay()->setTemplateDir("orongo-admin/style/"); 

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
$user = getUser();
if($user != null){ header("Location: orongo-admin"); exit; }


if($msg != "")
{
    getDisplay()->addObject(new MessageBox($msg));
}


#   Template

getDisplay()->setTemplateVariable("head_title", Settings::getWebsiteName() . " - Register");
getDisplay()->addJS('$("#register_form").validationEngine(\'attach\');', "document.ready");

getDisplay()->setTemplateVariable("style", "style.login");

getDisplay()->add("header.orongo");
getDisplay()->add("register.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();
?>
