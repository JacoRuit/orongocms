<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_view');

Security::promptAuth();


$query = explode(".", trim($_SERVER['QUERY_STRING']));
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$object = trim($query[0]);
$id = trim($query[1]);

$view = new AdminFrontend();
$view->main(array("time" => time(), "page_template" => "dashboard", "page_title" => "Viewing"));

switch($object){
    case "page":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $page = null;
        $view->setTitle("Viewing Page");
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
        $form = new AdminFrontendForm(100, l("Page") . ": " . $page->getTitle(), "GET","", false);
        $form->addInput("ID", "id", "text", $page->getID(), false, true);
        $form->addInput("Page Title", "title", "text", $page->getTitle(), false, true);
        $form->addInput("Page Content", "content", "ckeditor", $page->getContent() , false, true);
        $form->addButton("Delete", false, orongoURL("orongo-admin/delete.php?page." . $id));
        $form->addButton("Edit", false, orongoURL("orongo-admin/edit.php?page." . $id));
        $view->addObject($form);
        $view->render();
        break;
        
    case "user":
        if($id != getUser()->getID() && getUser()->getRank() != RANK_ADMIN){
            header("Location: " . orongoURL("orongo-admin/index.php?msg=0"));
            exit;
        }
        $user = null;
        $view->setTitle("Viewing User");
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
        $form = new AdminFrontendForm(100, l("User") . ": " . $user->getName(), "GET","", false);
        $form->addInput("ID", "id", "text", $user->getID(), false, true);
        $form->addInput("Username", "name", "text", $user->getName(), false, true);
        $form->addInput("Email", "email", "text", $user->getEmail(), false, true);
        if(getUser()->getRank() == RANK_ADMIN)
            $form->addButton("Delete", false, orongoURL("orongo-admin/delete.php?user." . $id));
        $form->addButton("Edit", false, orongoURL("orongo-admin/edit.php?user." . $id));
        $view->addObject($form);
        $view->render();
        break;
        
    case "article":
        if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $article = null;
        $view->setTitle("Viewing Article");
        try{
            $article = new Article($id);
        }catch(Exception $e){
            if($e->getCode() == ARTICLE_NOT_EXIST){
                header("Location: " . orongoURL("orongo-admin/manage.php?msg=0&obj=pages"));
                exit;
            }else{
                header("Location: " . orongoURL("orongo-admin/index.php?msg=2"));
                exit;
            }
        }
        $form = new AdminFrontendForm(100, l("Article") . ": " . $article->getTitle(), "GET","", false);
        $form->addInput("ID", "id", "text", $article->getID(), false, true);
        $form->addInput("Date", "date", "text", $article->getDate(), false, true);
        $form->addInput("Author", "author", "text", $article->getAuthorName() , false, true);
        $form->addInput("Article Title", "title", "text", $article->getTitle(), false, true);
        $form->addInput("Article Content", "content", "ckeditor", $article->getContent() , false, true);
        $form->addButton("Delete", false, orongoURL("orongo-admin/delete.php?page." . $id));
        $form->addButton("Edit", false, orongoURL("orongo-admin/edit.php?page." . $id));
        $view->addObject($form);
        if($article->getCommentCount() > 0){
            $comments = new AdminFrontendContentManager(100, "Comments");
            $comments->createTab("Comments", array("ID", "Comment", "Commenter", "Date"));
            foreach($article->getComments() as $comment){
                $commentText = strlen($comment->getContent()) > 20 ? substr($comment->getContent(), 0, 20) . "..." : $comment->getContent();
                $comments->addItem("Comments", array(
                    $comment->getID(), 
                    '<a href="' . orongoURL('orongo-admin/view.php?comment.'. $comment->getID()) . '">'  . $commentText . '</a>',
                    '<a href="' . orongoURL("orongo-admin/view.php?user." . $comment->getAuthorID()) . '">' . $comment->getAuthorName() . '</a>', 
                    date("Y-m-d H:i:s", $comment->getTimestamp())
                ), "","");
            }
            $view->addObject($comments);
        }
        $view->render();
        break;
        
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
        break;
}


?>