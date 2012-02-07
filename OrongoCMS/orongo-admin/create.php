<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_create');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: index.php"); exit; }

if(!isset($_SERVER['QUERY_STRING'])){ header("Location: index.php"); exit; }

$object = $_SERVER['QUERY_STRING'];


$create = new AdminFrontend();

if(isset($_GET['msg'])){
    if(isset($_GET['obj'])) $object = $_GET['obj'];
    switch($_GET['msg']){
        case 0:
            $create->addMessage(l("Object post error"), "error");
            break;
        case 1:
            $create->addMessage(l("Object post success"), "success");
            break;
        default:
            break;
    }
}

switch($object){
    case "article":
        $create->main(array("time" => time(), "page_title" => "Create Article", "page_template" => "dashboard"));
        $form = new AdminFrontendForm(100, "New Article", "POST", orongoURL("actions/action_Create.php?article"));
        $form->addInput("Article Title", "title", "text", "", true);
        $form->addInput("Article Content", "content", "ckeditor", "", true);
        $form->addInput("Tags", "tags", "text", "tag1, tag2");
        $form->addButton("Post", true);
        $create->addObject($form);
        $create->render();
        exit;
        break;
    case "user":
        break;
    case "page":
        $create->main(array("time" => time(), "page_title" => "Create Page", "page_template" => "dashboard"));
        $form = new AdminFrontendForm(100, "New Page", "POST", orongoURL("actions/action_Create.php?page"));
        $form->addInput("Page Title", "title", "text", "", true);
        $form->addInput("Page Content", "content", "ckeditor", "", true);
        $form->addButton("Post", true);
        $create->addObject($form);
        $create->render();
        break;
    default:
        header("Location: index.php");
        exit;
}


?>
