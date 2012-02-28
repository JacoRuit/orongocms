<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password_again']) && isset($_POST['password']) && !isset($_SESSION['orongo-id']) && !(isset($_SESSION['orongo-session-id']))){
    if(strtolower($_POST['username']) == 'username' ){
        header("Location: " . orongoURL("orongo-register.php?msg=4"));
        exit;
    }
    if($_POST['password'] != $_POST['password_again']){
        header("Location: " . orongoURL("orongo-register.php?msg=0"));
        exit;
    }
    if(strlen($_POST['username']) < 4 || strlen($_POST['username']) > 20 ){
        header("Location: " . orongoURL("orongo-register.php?msg=2"));
        exit;
    }
    if(strlen($_POST['password']) < 6){
        header("Location: " . orongoURL("orongo-register.php?msg=3"));
        exit;
    }
   
    $name = Security::escape($_POST['username']);
    $email = Security::escape($_POST['email']);
    $password = Security::hash($_POST['password']);
    if(User::usernameExists($name) == false){
        $user = null;
        try{
            $user = User::registerUser($name, $email, $password , RANK_USER);
        }catch(Exception $e){
            header("Location: " . orongoURL("orongo-login.php?msg=3"));
            exit;
        }
        $activationLink = User::generateActivationURL($user->getID());
        $mail = MailFactory::generateActivationEmail($user->getName() , $activationLink);
        $sendEmail = mail($user->getEmail(), $mail['subject'], $mail['headers']);
        if(!$sendEmail){
            header("Location: " . orongoURL("orongo-login.php?msg=3"));
            exit;
        }
        header("Location: " . orongoURL("orongo-login.php?msg=2"));
        exit;
    }else{
        echo $name;
        header("Location:" . orongoURL("orongo-login.php?msg=1"));
        exit;
    }
}else{
    header("Location: " . orongoURL("orongo-register.php?msg=3"));
    exit;
}
?>
