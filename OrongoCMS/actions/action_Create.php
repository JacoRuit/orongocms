<?php
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_create');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php")); exit; }

if(!isset($_SERVER['QUERY_STRING'])){ header("Location: " . orongoURL("orongo-admin/create.php")); exit; }

$object = $_SERVER['QUERY_STRING'];

switch($object){
    case "article":
        //title,content,tags
        if(!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['tags'])){
            header("Location: " . orongoURL("orongo-admin/create.php?article"));
            exit;
        }
        try{
            $article = Article::createArticle($_POST['title'], getUser());
            $article->setContent($_POST['content']);
        }catch(Exception $e){
            header("Location: ". orongoURL("orongo-admin/index.php?msg=0-ERROR"));
        }
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1-GOED"));
        exit;
        break;
    case "page":
        break;
    case "user":
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/create.php"));
}


?>
