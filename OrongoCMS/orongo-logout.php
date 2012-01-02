<?php
/**
 * @author Jaco Ruit
 */
require 'globals.php';

if(isset($_SESSION['orongo-id']) || isset($_SESSION['orongo-session-id'])){
    $sessionID = Security::escapeSQL($_SESSION['orongo-session-id']);
    Session::delete($sessionID);
    session_destroy();
    header("Location: orongo-login.php?msg=1");
}else{
     header("Location: orongo-login.php");
}
?>
