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

switch($object){
    case "article":
        $create->main(array("time" => time(), "page_title" => "Create Article", "page_template" => "dashboard"));
        $form = new AdminFrontendForm(100, "New Article", "POST", orongoURL("actions/action_CreateArticle.php"));
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
        break;
    default:
        header("Location: index.php");
        exit;
}


?>
