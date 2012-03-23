<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo('orongo-register');
getDisplay()->setTemplateDir("orongo-admin/style/"); 

$msg = null;
$msgtype = null;
if(isset($_GET['msg'])){
    $msgCode = Security::escape($_GET['msg']);
    switch($msgCode){
        case 0:
            $msg = l("REG_MSG_PASS_NO_MATCH");
            $msgtype = "error";
            break; 
        case 1:
            $msg = l("REG_MSG_USERNAME_EXISTS");
            $msgtype = "warning";
            break;
        case 2:
            $msg = l("REG_MSG_USERNAME_TOO_SHORT");
            $msgtype = "error";
            break;
        case 3:
            $msg = l("REG_MSG_PASSWORD_TOO_SHORT");
            $msgtype = "error";
            break;
        case 4:
            $msg = l("REG_MSG_FILL_IN_USERNAME");
            $msgtype = "error";
            break;
        default:
            break;
    }
}

$register = new AdminFrontend();
$register->main(array("time" => time(), "page_title" => "Register", "page_template" => "ndashboard"));
$form = new AdminFrontendForm(100, "Register", "POST", orongoURL("actions/action_Register.php"));
$form->addInput("Username", "username", "text", "", true);
$form->addInput("Password", "password", "password", "", true);
$form->addInput("Password again", "password_again", "password", "", true);
$form->addInput("Email", "email", "email", "", true);
$form->addButton("Register", true);
$register->addObject($form);
if($msg != null) $register->addMessage($msg,$msgtype);
$register->render();
?>
