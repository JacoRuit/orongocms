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
            $msg = l("LOGIN_MSG_WRONG_DETAILS");
            $msgtype = "error";
            break;
        case 1:
            $msg = l("LOGIN_MSG_LOGGED_OUT");
            $msgtype = "success";
            break;
        case 2:
            $msg = l("LOGIN_MSG_REG_SUCCESS");
            $msgtype = "info";
            break;
        case 3:
            $msg = l("LOGIN_MSG_REG_INTERNAL_ERROR");
            $msgtype = "warning";
            break;
        case 4:
            $msg = l("LOGIN_MSG_INVALID_ACTIVATION_URL");
            $msgtype= "warning";
            break;
        case 5:
            $msg = l('LOGIN_MSG_ALREADY_ACTIVATED');
            $msgtype = "info";
            break;
        case 6:
            $msg = l('LOGIN_MSG_ACTIVATION_OK');
            $msgtype = "success";
            break;
        case 7:
            $msg = l("LOGIN_MSG_PROMPT_ACTIVATION");
            $msgtype = "warning";
            break;
        default:
            break;
    }
}

$login = new AdminFrontend();
$login->main(array("time" => time(), "page_title" => "Login", "page_template" => "ndashboard"));
$form = new AdminFrontendForm(75, "Login", "POST", orongoURL("actions/action_Login.php"));
$form->addInput("Username", "username", "text");
$form->addInput("Password", "password", "password");
$form->addButton("Login", true);
$login->addObject($form);
$login->addObject(new AdminFrontendObject(25, "", '<h4>' . l("New here") . '</h4><p>' . l("Register text",array('<a href="' . orongoURL("orongo-register.php#") . '">', '</a>') ) . '</p><br /><hr /><h4>' . l("Forgot password") . '</h4><p>' . l("Forgot password text",array('<a href="' . orongoURL("orongo-lost-pw.php#") . '">', '</a>')) . '</p><br /><br />'));
if($msg != null) $login->addMessage($msg, $msgtype);
$login->render();

?>
