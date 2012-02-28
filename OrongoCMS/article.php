<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();

setCurrentPage('article');

$article = null;

if(!isset($_GET['id'])){
    header("Location: 404.php");
    exit;
}else{
    try{
        $article = new Article($_GET['id']);
    }catch(Exception $e){
        if($e->getCode() == ARTICLE_NOT_EXIST){
            header("Location: 404.php");
        }else{
            header("Location: 503.php");
        }
    }
}

$articleFO = new ArticleFrontend();
$articleFO->main(array("time" => time(), "article" => &$article));
$articleFO->render();


?>
