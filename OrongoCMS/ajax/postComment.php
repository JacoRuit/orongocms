<?php
/**
 * postComment AJAX 
 * 
 * @author Jaco Ruit
 */

require 'globals.php';

//TODO IP check or some kinda spam prevention thing
define("NO_ARTICLE", 1);

define("NO_CONTENT", 11);
define("TOO_SHORT", 12);

define("NOT_LOGGED_IN", 21);

define("OK", 31);

function errorDie($paramError, $paramErrorCode){
    $arrayToJs = array();
    $arrayToJs["response"] = $paramError;
    $arrayToJs["response_code"] = $paramErrorCode; 
    die(json_encode($arrayToJs));
}

if(!isset($_POST['article']) || !is_numeric($_POST['article'])){
    errorDie("No article!", NO_ARTICLE);
    exit;
}

if(!isset($_POST['content'])){
    errorDie("Comment has no content!", NO_CONTENT);
    exit;
}

if(strlen($_POST['content']) < 5){
   errorDie("Content is too short!", TOO_SHORT);
   exit;
}

$user = handleSessions();

if($user == null){
    errorDie("You need to be logged in in order to post comments.", NOT_LOGGED_IN);
    exit;
}

$comment = Comment::createComment(Security::escape($_POST['article']), $user);
$comment->setContent(Security::escape($_POST['content']));

$succesArray = array();
$succesArray["response"] = "Comment posted!";
$succesArray["response_code"] = OK;
die(json_encode($succesArray));
?>
