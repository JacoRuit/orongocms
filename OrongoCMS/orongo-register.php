<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();
getDisplay()->setTemplateDir("orongo-admin/style/"); 

$msg = null;
$msgtype = null;
if(isset($_GET['msg'])){
    $msgCode = Security::escape($_GET['msg']);
    switch($msgCode){
        case 0:
            $msg = 'Passwords do not match.';
            $msgtype = "error";
            break; 
        case 1:
            $msg = 'Username exists already.';
            $msgtype = "warning";
            break;
        case 2:
            $msg = 'Invalid username length. Username must be min. 4 characters max. 20 characters long.';
            $msgtype = "error";
            break;
        case 3:
            $msg = 'Invalid password length. Password must be min. 6 characters long.';
            $msgtype = "error";
            break;
        case 4:
            $msg = 'Please fill in an username!';
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
