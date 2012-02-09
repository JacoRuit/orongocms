<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_create');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

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

if(isset($_GET['msg'])){
    if(isset($_GET['obj'])) $object = $_GET['obj'];
    switch($_GET['msg']){
        case 0:
            $create->addMessage(l("Object edit error"), "error");
            break;
        case 1:
            $create->addMessage(l("Object edit success"), "success");
            break;
        default:
            break;
    }
}

switch($object){
    case "article":
        $create->setTitle("Edit Article");
        try{
            $article = new Article($id);
        }catch(Exception $e){
            if($e->getCode() == PAGE_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=articles"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        $form = new AdminFrontendForm(100, "Edit Article", "POST", orongoURL("actions/action_Edit.php?article"));
        $form->addInput("Article Title", "title", "text", "", true);
        $form->addInput("Article Content", "content", "ckeditor", "", true);
        $form->addInput("Tags", "tags", "text", "tag1, tag2");
        $form->addButton("Post", true);
        $create->addObject($form);
        $create->render();
        break;
    case "user":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $create->setTitle("Create User");
        $form = new AdminFrontendForm(100, "New User", "POST", orongoURL("actions/action_Create.php?user"));
        $form->addInput("Username", "name", "text","",true);
        $form->addInput("Password", "password", "password", "blaat123", true);
        $form->addInput("Email", "email", "email", "email@address.com", true);
        $form->addSelect("rank", array(l("User") => 1, l("Writer") => 2, l("Admin") => 3));
        $form->addButton("Create", true);
        $create->addObject($form);
        $create->render();
        break;
    case "page":
        $create->setTitle("Create Page");
        $form = new AdminFrontendForm(100, "New Page", "POST", orongoURL("actions/action_Create.php?page"));
        $form->addInput("Page Title", "title", "text", "", true);
        $form->addInput("Page Content", "content", "ckeditor", "", true);
        $form->addButton("Post", true);
        $create->addObject($form);
        $create->render();
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
}


?>
