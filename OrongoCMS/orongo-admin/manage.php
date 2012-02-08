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
            $manager->addItem("Articles", array($obj->getID(), $obj->getTitle(), $obj->getDate(), $obj->getAuthorName(), $obj->getCommentCount()), "","");
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
            switch($obj->getRank()){
                case 0:
                    $rank = l("Banned");
                    break;
                case 1:
                    $rank = l("User");
                    break;
                case 2:
                    $rank = l("Writer");
                    break;
                case 3:
                    $rank = l("Admin");
                    break;
                default:
                    $rank = "Unkown";
                    break;
            }
            $manager->addItem("Users", array($obj->getID(), $name, $obj->getEmail(), $rank, $activated), "","");
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
            $manager->addItem("Pages", array($obj->getID(), $obj->getTitle()), "","");
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
        $manager->createTab("Comments", array("ID", "Commenter", "Date", "Article ID", "Article Name"));
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
            $manager->addItem("Comments", array($obj->getID(), $obj->getAuthorName(), date("Y-m-d H:i:s", $obj->getTimestamp()), $obj->getArticleID(), $articleName), "","");
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