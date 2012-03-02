<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();

setCurrentPage('article');

$article = null;

if(!isset($_GET['id'])){
    header('Location: ' . orongoURL("error.php?error_code=404"));
    exit;
}else{
    try{
        $article = new Article($_GET['id']);
    }catch(Exception $e){
        if($e->getCode() == ARTICLE_NOT_EXIST){
            header('Location: ' . orongoURL("error.php?error_code=404"));
            exit;
        }else{
            header('Location: ' . orongoURL("error.php?error_code=500"));
            exit;
        }
    }
}

$articleFO = new ArticleFrontend();
$articleFO->main(array("time" => time(), "article" => &$article));
$articleFO->render();


?>
