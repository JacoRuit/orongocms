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
        if(!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['tags'])){
            header("Location: " . orongoURL("orongo-admin/create.php?article"));
            exit;
        }
        try{
            $article = Article::createArticle($_POST['title'], getUser());
            $article->setContent($_POST['content']);
        }catch(Exception $e){
            header("Location: ". orongoURL("orongo-admin/create.php?msg=0&obj=article"));
        }
        header("Location: " . orongoURL("orongo-admin/create.php?msg=1&obj=article"));
        exit;
        break;
    case "page":
        if(!isset($_POST['title']) || !isset($_POST['content'])){
            header("Location: " . orongoURL("orongo-admin/create.php?page"));
            exit;
        }
        try{
            $page = Page::createPage($_POST['title']);
            $page->setContent($_POST['content']);
        }catch(Exception $e){
            header("Location: ". orongoURL("orongo-admin/create.php?msg=0&obj=page"));
        }
        header("Location: " . orongoURL("orongo-admin/create.php?msg=1&obj=page"));
        exit;
        break;
    case "user":
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/create.php"));
        break;
}


?>
