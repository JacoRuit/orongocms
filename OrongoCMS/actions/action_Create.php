<?php
/**
 * @author Jaco Ruit 
 */
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_create');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

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
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        if(!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_POST['rank'])){
            header("Location: " . orongoURL("orongo-admin/create.php?user"));
            exit;
        }
        if(User::usernameExists($_POST['name'])){
           header("Location: ". orongoURL("orongo-admin/create.php?msg=0&obj=user"));
           exit;
        }
        try{
           $user = User::registerUser($_POST['name'], $_POST['email'], Security::hash($_POST['password']), $_POST['rank']);
           User::activateUser($user->getID());
        }catch(Exception $e){
            header("Location: ". orongoURL("orongo-admin/create.php?msg=0&obj=user"));
            exit;
        }
        header("Location: " . orongoURL("orongo-admin/create.php?msg=1&obj=user"));
        exit;
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/create.php"));
        exit;
        break;
}


?>
