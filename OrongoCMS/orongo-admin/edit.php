<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_edit');

Security::promptAuth();


if(!isset($_SERVER['QUERY_STRING'])){ header("Location: " . orongoURL("orongo-admin/index.php?msg=1")); exit; }

$query = explode(".", trim($_SERVER['QUERY_STRING']));
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$object = trim($query[0]);
$id = trim($query[1]);

$create = new AdminFrontend();
$create->main(array("time" => time(), "page_title" => "Edit", "page_template" => "dashboard"));


switch($object){
    case "article":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $create->setTitle("Edit Article");
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
        $form = new AdminFrontendForm(100, l("Edit Article") ." (" . $article->getID() . ")", "POST", orongoURL("actions/action_Edit.php?article." . $article->getID()), false);
        $form->addInput("Article Title", "title", "text", $article->getTitle(), true);
        $form->addInput("Article Content", "content", "ckeditor", $article->getContent(), true);
        $form->addInput("Tags", "tags", "text", $article->getTagsString());
        $form->addButton("Edit", true);
        $create->addObject($form);
        $create->render();
        break;
    case "user":
        $create->setTitle("Edit User");
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
        if($user->getID() != getUser()->getID() && getUser()->getRank() != RANK_ADMIN){
            header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); 
            exit; 
        }
        $titletext = getUser()->getID() == $user->getID() ? l("You") : $user->getID();
        $form = new AdminFrontendForm(100, l("Edit User") . " (" . $titletext . ")" , "POST", orongoURL("actions/action_Edit.php?user." . $user->getID()), false);
        if(getUser()->getRank() == RANK_ADMIN)
            $form->addInput("Username", "new_name", "text", $user->getName(),true);
        $form->addInput("Password", "new_password", "password", "", true);
        $form->addInput("Email", "new_email", "email", $user->getEmail(), true);
        if(getUser()->getRank() < RANK_ADMIN)
            $form->addInput("Current Password", "password", "password", "blaat123", true);
        if(getUser()->getRank() >= RANK_ADMIN){
            if($user->getRank() == RANK_ADMIN) $form->addSelect("rank", array(l("Admin") => 3, l("User") => 1, l("Writer") => 2));
            if($user->getRank() == RANK_WRITER) $form->addSelect("rank", array(l("Writer") => 2, l("User") => 1, l("Admin") => 3));
            if($user->getRank() == RANK_USER) $form->addSelect("rank", array(l("User") => 1, l("Writer") => 2, l("Admin") => 3));
        }
        $form->addButton("Edit", true);
        $create->addObject($form);
        $create->render();
        break;
    case "page":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $create->setTitle("Edit Page");
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
        $form = new AdminFrontendForm(100, l("Edit Page") . " (" . $page->getID() . ")", "POST", orongoURL("actions/action_Edit.php?page." . $page->getID()), false);
        $form->addInput("Page Title", "title", "text", $page->getTitle(), true);
        $form->addInput("Page Content", "content", "ckeditor", $page->getContent(), true);
        $form->addButton("Edit", true);
        $create->addObject($form);
        $create->render();
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
}


?>
