<?php
/**
 * @author Jaco Ruit 
 */
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_delete');

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
        $article->delete();
        header("Location: " . orongoURL("orongo-admin/manage.php?msg=1&obj=articles"));
        break;
    case "user":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
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
        if(getUser()->getID() == $user->getID()){
            header("Location: " . orongoURL("orongo-admin/manage.php?msg=2&obj=users"));
            exit;
        } 
        $user->delete();
        header("Location: " . orongoURL("orongo-admin/manage.php?msg=1&obj=users"));
        break;
    case "page":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
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
        $page->delete();
        header("Location: " . orongoURL("orongo-admin/manage.php?msg=1&obj=pages"));
        break;
    case "comment":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        try{
            $comment = new Comment($id);
        }catch(Exception $e){
            if($e->getCode() == COMMENT_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=pages"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        $comment->delete();
        header("Location: " . orongoURL("orongo-admin/manage.php?msg=1&obj=comments"));
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
}

?>
