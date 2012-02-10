<?php
/**
 * @author Jaco Ruit 
 */
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_edit');

Security::promptAuth();



if(!isset($_SERVER['QUERY_STRING'])){ header("Location: " . orongoURL("orongo-admin/create.php")); exit; }

$query = explode(".", trim($_SERVER['QUERY_STRING']));
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$object = trim($query[0]);
$id = trim($query[1]);

switch($object){
    case "article":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        if(!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['tags'])){
            header("Location: " . orongoURL("orongo-admin/create.php?article"));
            exit;
        }
        try{
            $article = new Article($id);
        }catch(Exception $e){
            if($e->getCode() == ARTICLE_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=articles"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        header("Location: " . orongoURL("orongo-admin/create.php?msg=1&obj=article"));
        exit;
        break;
    case "page":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit;
        if(!isset($_POST['title']) || !isset($_POST['content'])){
            header("Location: " . orongoURL("orongo-admin/edit.php?page." . $id));
            exit;
        }
        try{
            $page = new Page($id);
        }catch(Exception $e){
            if($e->getCode() == PAGE_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=pages"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        $page->setTitle($_POST['title']);
        $page->setContent($_POST['content']);
        header("Location: " . orongoURL("orongo-admin/view.php?msg=1&obj=page&id=" . $page->getID()));
        exit;
        break;
    case "user":
        if(getUser()->getRank() < RANK_ADMIN && getUser()->getID() != $id){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        if(!isset($_POST['new_name']) || !isset($_POST['new_password']) || !isset($_POST['new_email']))){
            header("Location: " . orongoURL("orongo-admin/edit.php?user." . $id));
            exit;
        }
        if(!isset($_POST['password']) && getUser()->getRank() < RANK_ADMIN){
            header("Location: " . orongoURL("orongo-admin/edit.php?user." . $id));
            exit; 
        }
        try{
            $user = new User($id);
        }catch(Exception $e){
            if($e->getCode() == USER_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=users"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        if(getUser()->getRank() < RANK_ADMIN){
            if(!User::isGoodPassword($user->getID(), Security::hash('password'))){
                header("Location: " . orongoURL("orongo-admin/view.php?msg=0&obj=user&id=" . $user->getID()));
                exit;
            }
        }
        if(!empty($_POST['new_name']) && getUser()->getRank() == RANK_ADMIN){
            $user->setName($_POST['new_name']);
        }
        if(!empty($_POST['new_password'])){
            User::setPassword($user->getID(), Security::hash($_POST['new_password']));
        }
        header("Location: " . orongoURL("orongo-admin/view.php?msg=1&obj=user&id=" . $user->getID()));
        exit;
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/create.php"));
        exit;
        break;
}


?>
