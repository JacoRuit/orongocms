<?php
/**
 * loadComments AJAX 
 * 
 * @author Jaco Ruit
 */

require 'globals.php';

//TODO IP check or some kinda spam prevention thing
define("NO_ARTICLE", 1);

define("NO_OFFSET", 11);

define("NO_LAST_COMMENT_ID", 21);

define("NO_NEW_COMMENTS", 30);

define("OK", 31);

function errorDie($paramError, $paramErrorCode){
    $arrayToJs = array();
    $arrayToJs["response"] = $paramError;
    $arrayToJs["response_code"] = $paramErrorCode; 
    die(json_encode($arrayToJs));
}

if(!isset($_POST['last_comment_id']) || !is_numeric($_POST['last_comment_id'])){
    errorDie("No last comment id!", NO_LAST_COMMENT_ID);
    exit;
}

if(!isset($_POST['offset']) || !is_numeric($_POST['offset'])){
    errorDie("No offset!", NO_OFFSET);
    exit;
}

if(!isset($_POST['article']) || !is_numeric($_POST['article'])){
    errorDie("No article!", NO_ARTICLE);
    exit;
}

$lastCommentArr = null;
try{
    $lastCommentArr = orongo_query("action=fetch&object=comment&max=1&order=comment.id,desc&where=article.id:" . Security::escape($_POST['article']));
}catch(Exception $e){
    die("500");
}

if(count($lastCommentArr) == 0){
    errorDie("No new comments!", NO_NEW_COMMENTS);
    exit;
}

foreach($lastCommentArr as $comment){
    if(($comment instanceof Comment) == false) continue;
    if($comment->getID() <= $_POST['last_comment_id'] ){
       errorDie("No new comments! ", NO_NEW_COMMENTS);
       exit;
    }else $newLCID = $comment->getID();
}

$newComments = null;
try{
    $newComments = orongo_query("action=fetch&object=comment&max=1000000&offset=". Security::escape($_POST['offset']) . "&order=comment.id,asc&where=article.id:" . Security::escape($_POST['article']));
}catch(Exception $e){
    die("500");
}

$newComments = array_reverse($newComments);

$html = "";
$style = null;
try{
    $style = Settings::getStyle('../');
}catch(Exception $e){
    die("500");
}

if($style->doCommentHTML()){
    try{
        $html = $style->getCommentsHTML($newComments);
    }catch(Exception $e){
        foreach($newComments as $comment){
            $html .= $comment->toHTML();
        }
    }
}else{
    foreach($newComments as $comment){
        $html .= $comment->toHTML();
    }
}

$succesArray = array();
$succesArray["response"] = "Ok!";
$succesArray["response_code"] = OK;
$succesArray["count"] = count($newComments);
$succesArray["html"] = $html;
$succesArray["newLastCommentID"] = $newLCID;
die(json_encode($succesArray));
?>
