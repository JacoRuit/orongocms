<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_delete');

Security::promptAuth();

if(!isset($_SERVER['QUERY_STRING'])){ header("Location: " . orongoURL("orongo-admin/index.php?msg=1")); exit; }

$query = explode(".", trim($_SERVER['QUERY_STRING']));
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$object = trim($query[0]);
$id = trim($query[1]);

$delete = new AdminFrontend();
$delete->main(array("time" => time(), "page_title" => "Delete", "page_template" => "dashboard"));

switch($object){
    case "article":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $delete->setTitle("Delete Article");
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
        $form = new AdminFrontendForm(100, l("Delete Article") ." (" . $article->getID() . ")", "POST","", false);
        $form->addButton("Yes", true, orongoURL("actions/action_Delete.php?article." . $article->getID()));
        $form->addButton("No", false, orongoURL("orongo-admin/manage.php?articles"));
        $form->setContent(l("Sure delete article",$article->getTitle())); //The AdminFrontendForm isn't a form anymore (updateHTML() wasn't called, how epic.)
        $delete->addObject($form);
        $delete->render();
        break;
    case "user":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $delete->setTitle("Delete User");
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
            $delete->addMessage(l("Can not delete yourself"), "error");
            $delete->render();
            exit;
        }
        $form = new AdminFrontendForm(100, l("Delete User") . " (" . $user->getID() . ")" , "POST", "", false);
        $form->addButton("Yes", true, orongoURL("actions/action_Delete.php?user." . $user->getID()));
        $form->addButton("No", false, orongoURL("orongo-admin/manage.php?users"));
        $form->setContent(l("Sure delete user",$user->getName()));
        $delete->addObject($form);
        $delete->render();
        break;
    case "page":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $delete->setTitle("Delete Page");
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
        $form = new AdminFrontendForm(100, l("Delete Page") . " (" . $page->getID() . ")", "POST", "", false);
        $form->addButton("Yes", true, orongoURL("actions/action_Delete.php?page." . $page->getID()));
        $form->addButton("No", false, orongoURL("orongo-admin/manage.php?pages"));
        $form->setContent(l("Sure delete page",$page->getTitle()));
        $delete->addObject($form);
        $delete->render();
        break;
    case "comment":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $delete->setTitle("Delete Comment");
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
        $form = new AdminFrontendForm(100, l("Delete Comment") . " (" . $comment->getID() . ")", "POST", "", false);
        $form->addButton("Yes", true, orongoURL("actions/action_Delete.php?comment." . $comment->getID()));
        $form->addButton("No", false, orongoURL("orongo-admin/manage.php?comments"));
        $form->setContent(l("Sure delete comment",$comment->getAuthorName()));
        $delete->addObject($form);
        $delete->render();
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
}



?>
