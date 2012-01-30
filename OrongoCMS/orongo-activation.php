<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();

if(isset($_GET['code']) && !isset($_SESSION['orongo-id']) && !(isset($_SESSION['orongo-session-id']))){
    $code = Security::escapeSQL($_GET['code']);
    if(User::isGoodActivationCode($code)){
        $uid = User::getUserIDByActivationCode($code);
        if(!User::userIsActivated($uid)){
            User::activateUser($uid);
            User::deleteActivationCode($code);
            header("Location: orongo-login.php?msg=6");
            exit;
        }else{
            @User::deleteActivationCode($code);
            header("Location: orongo-login.php?msg=5");
            exit;
        }  
    }else{
        header("Location: orongo-login.php?msg=4");
        exit;
    }
}else{
    header("Location: orongo-login.php");
    exit;
}
?>
