<?php

/**
 * HandleSession function
 *
 * @author Jaco Ruit
 */

/**
* Handles the session
* @return User/null if null, then no user is logged in
*/
function handleSessions() {
    if(isset($_SESSION['orongo-id']) && isset($_SESSION['orongo-session-id'])){
    $externID = Security::escapeSQL($_SESSION['orongo-id']);
    $externSession = Security::escapeSQL($_SESSION['orongo-session-id']);
    if(Session::isGoodSessionID($externSession)){
        $sessionUserID = Session::getUserID($externSession);
        if($sessionUserID == $externID){
            try{
                $user = new User($externID);
                return $user;
            }catch(Exception $e){
                if($e->getCode() == USER_NOT_EXIST){
                    header("Location: orongo-logout.php");
                    exit;
                }else{
                    header('HTTP/1.1 500 Internal Server Error');
                    exit;
                }
            }  
        }else{
            Session::delete($externSession);
            session_destroy();
            header("Location: orongo-logout.php");
            exit;
        }
    }else{
        Session::delete($externSession);
        session_destroy();
        header("Location: orongo-logout.php");
        exit;
    }   
    }else return null; 
}

?>
