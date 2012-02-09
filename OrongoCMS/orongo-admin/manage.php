<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_view');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$manage = new AdminFrontend();
$manage->main(array("time" => time(), "page_title" => "Manage", "page_template" => "dashboard"));

if(!isset($_SERVER['QUERY_STRING'])){ header("Location: " . orongoURL("orongo-admin/index.php?msg=1")); exit; }

$object = $_SERVER['QUERY_STRING'];

if(isset($_GET['msg']) && isset($_GET['obj'])){
    switch($_GET['msg']){
        case 0:
            $manage->addMessage(l("Object not exists"), "error");
            $object = $_GET['obj'];
            break;
    }
}

switch($object){  
    case "articles":
        $objs = null;
        $manage->setTitle("Manage Articles");
        try{
            $objs = orongo_query("action=fetch&object=article&max=1000000&order=article.id,desc");
        }catch(Exception $e){
            $manage->addMessage($e, "error");
            $manage->render(); 
            exit;
        }
        $manager = new AdminFrontendContentManager(100, "Articles");
        $manager->createTab("Articles", array("ID", "Title", "Date", "Author", "Comments"));
        foreach($objs as $obj){
            if(($obj instanceof Article) == false) continue;
            $manager->addItem("Articles", array(
                $obj->getID(), 
                '<a href="' . orongoURL('orongo-admin/view.php?article.'. $obj->getID()) . '">' . $obj->getTitle() . '</a>', 
                $obj->getDate(), 
                '<a href="' . orongoURL("orongo-admin/view.php?user." . $obj->getAuthorID()) . '">' . $obj->getAuthorName() . '</a>', 
                $obj->getCommentCount()
            ), "", "");
        }
        $manage->addObject($manager);
        $manage->render();
        break;
        
        
    case "users":
        if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }
        $objs = null;
        $manage->setTitle("Manage Users");
        try{
            $objs = orongo_query("action=fetch&object=user&max=1000000&order=user.id,asc");
        }catch(Exception $e){
            $manage->addMessage($e, "error");
            $manage->render(); 
            exit;
        }
        $manager = new AdminFrontendContentManager(100, "Users");
        $manager->createTab("Users", array("ID", "Username", "Email", "Rank", "Activated"));
        foreach($objs as $obj){
            if(($obj instanceof User) == false) continue;
            $name = $obj->getName() == getUser()->getName() ? '<strong>' . $obj->getName() . '</strong>' : $obj->getName();
            $activated = $obj->isActivated() ? l("Yes") : l("No");
            $manager->addItem("Users", array(
                $obj->getID(), 
                '<a href="' . orongoURL("orongo-admin/view.php?user." . $obj->getID()) . '">' . $name . '</a>', 
                $obj->getEmail(), 
                $obj->getRankString(), 
                $activated
            ), "","");
        }
        $manage->addObject($manager);
        $manage->render();
        break;
        
        
    case "pages":
        $objs = null;
        $manage->setTitle("Manage Pages");
        try{
            $objs = orongo_query("action=fetch&object=page&max=1000000&order=page.id,desc");
        }catch(Exception $e){
            $manage->addMessage($e, "error");
            $manage->render(); 
            exit;
        }
        $manager = new AdminFrontendContentManager(100, "Pages");
        $manager->createTab("Pages", array("ID", "Title"));
        foreach($objs as $obj){
            if(($obj instanceof Page) == false) continue;
            $manager->addItem("Pages", array(
                $obj->getID(), 
                '<a href="' . orongoURL("orongo-admin/view.php?page." . $obj->getID()) . '">' . $obj->getTitle() . '</a>'
            ), "","");
        }
        $manage->addObject($manager);
        $manage->render();
        break;
        
        
    case "comments":
        $objs = null;
        $manage->setTitle("Manage Comments");
        try{
            $objs = orongo_query("action=fetch&object=comment&max=1000000&order=comment.id,desc");
        }catch(Exception $e){
            $manage->addMessage($e, "error");
            $manage->render(); 
            exit;
        }
        $manager = new AdminFrontendContentManager(100, "Comments");
        $manager->createTab("Comments", array("ID", "Comment", "Commenter", "Date", "Article ID", "Article Title"));
        foreach($objs as $obj){
            if(($obj instanceof Comment) == false) continue;
            $articleName = "?";
            if(Cache::isStored('article_name_' . $obj->getArticleID())){
                $articleName = Cache::get('article_name_' . $obj->getArticleID());
            }else{
                try{
                    $article = new Article($obj->getArticleID());
                    $articleName = $article->getTitle();
                }catch(Exception $e){ }
            }
            $commentText = strlen($obj->getContent()) > 20 ? substr($obj->getContent(), 0, 20) . "..." : $obj->getContent();
            $manager->addItem("Comments", array(
                $obj->getID(), 
                '<a href="' . orongoURL('orongo-admin/view.php?comment.'. $obj->getID()) . '">'  . $commentText . '</a>',
                '<a href="' . orongoURL("orongo-admin/view.php?user." . $obj->getAuthorID()) . '">' . $obj->getAuthorName() . '</a>', 
                date("Y-m-d H:i:s", $obj->getTimestamp()), 
                $obj->getArticleID(), 
                '<a href="' . orongoURL('orongo-admin/view.php?article.'. $obj->getArticleID()) . '">' . $articleName . '</a>'
            ), "","");
        }
        $manage->addObject($manager);
        $manage->render();
        break;
    
    
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
        break;
}
?>