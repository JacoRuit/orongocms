<?php
/**
 * @author Jaco Ruit
 */


require 'globals.php';
require '../config.php';

if(isset($_POST['username']) && isset($_POST['password']) && !isset($_SESSION['orongo-id']) && !(isset($_SESSION['orongo-session-id']))){
    $username = Security::escape($_POST['username']);
    $password = Security::hash($_POST['password']);
    if(User::usernameExists($username)){
        $userID = User::getUserID($username);
        $goodLogin = User::isGoodPassword($userID, $password);
        if($goodLogin){
            if(User::userIsActivated($userID) == false){
                header("Location: ../orongo-login.php?msg=7");
                exit;
            }else{
                $_SESSION['orongo-id'] = $userID;
                $_SESSION['orongo-session-id'] = Session::createSession($userID);
                header("Location: ../orongo-admin/");
                exit;
            }
        }else{
            header("Location: ../orongo-login.php?msg=0");
            exit;
        }
    }else{
        header("Location: ../orongo-login.php?msg=0");
        exit;
    }       
}else{
    header("Location: ../orongo-login.php");
    exit;
}
?>
