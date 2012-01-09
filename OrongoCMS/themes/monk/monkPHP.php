<?php
/**
 * http://www.makimyers.co.uk/monk-free-html-template/
 * 
 * Monk Style needed to generate own Article HTML ;)
 * @author Jaco Ruit
 */

class MonkStyle implements IOrongoStyle{
    
    public function __construct(){}
    
    public function run(&$smarty){
        $settings = Style::getSettings();
        try{
           //reverse the menu because we have a righ float on menu
           $pages = array_reverse(orongo_query('action=fetch&object=page&max=10000&order=page.id'));
           $html="";
           $websiteURL = Settings::getWebsiteURL();
           if($settings['show_archive_in_menu']){ 
               $html .= "<li><a href=\"" . $websiteURL . "archive.php\">Archive</a></li>";
               $settings['show_archive_in_menu'] = null;
           }
           foreach($pages as $page){
            if($page instanceof Page){
                $html .= " <li><a href=\"". $websiteURL . "page.php?id=" . $page->getID() . "\">" . $page->getTitle() . "</a></li>";
            }else continue;
           }
           
           $html .= "<li><a href=\"" . $websiteURL . "index.php\">Home</a></li>";
           $smarty->assign("menu", $html);           
        }catch(Exception $e){}
        
        foreach($settings as $setting=>$value){
            if($value != null)
                $smarty->assign($settings,$value);
        }
    }
    
    public function getArticlesHTML($paramArticles){ 
        $generatedHTML = "";
        $curPage = getCurrentPage();
        if(is_array($paramArticles) == false) return null; //Sup, Orongo? U nooo pass me an array :(
        
        $count = count($paramArticles);
        if($count < 1) return "<p>No articles we're found</p>";
        $generatedCount = 0;
        foreach($paramArticles as $article){
            $last = false;
            if(($article instanceof Article) == false) continue;
            $generatedCount++;
            if($generatedCount == 4 && $curPage == 'index') $last = true; 
            if(is_int($generatedCount / 4) && $curPage == 'archive') $last = true;
            if($curPage == 'archive' && $last == false && $generatedCount == count($paramArticles)) $last = true;
            $generatedHTML .= '<div class="one_fourth ';
            if($last) $generatedHTML .= 'column-last';
            $generatedHTML .= ' ">';
            $generatedHTML .= '<a href="'. Settings::getWebsiteURL() . 'article.php?id=' . $article->getID() . '"><h3>' . $article->getTitle() . '</h3></a>';
            $generatedHTML .= '<p>' . substr($article->getContent(), 0 ,500) . '</p>';
            $generatedHTML .= '</div>';
            if($last && $curPage == 'index' ) break;
        }
        
        return $generatedHTML;
    }
    

    public function getArticleHTML($paramArticle){ 
        if(($paramArticle instanceof Article) == false) return null;
        $author = $paramArticle->getAuthor();
        if($author == null && ($author instanceof User) == false) $author_name = "Unknown"; else $author_name = $author->getName();
        $generatedHTML = "<div class=\"box\"><h1>" . $paramArticle->getTitle() . "</h1><p>Written by " . $author_name . "  on  " . $paramArticle->getDate() ."  //   " . $paramArticle->getCommentCount() . " comments</p></div>";
        $generatedHTML .= $paramArticle->getContent();
        $generatedHTML .= "<br /><br /><br />";
        return $generatedHTML;
    }
    

    public function getPageHTML($paramPage){ return null; }
    
    public function getCommentsHTML($paramComments) {
        if(count($paramComments) < 1) return "<p>No comments, be the first to comment!</p>";
        $generatedHTML = "";
        foreach($paramComments as $comment){
            if(($comment instanceof Comment) == false) continue;
            $generatedHTML .= '<div class="comment">';
            $generatedHTML .= '<p>Comment by ' . $comment->getAuthorName() . ' - ' . date("Y-m-d H:i:s", $comment->getTimestamp() ) . '</p>';
            $generatedHTML .= '<p>' . $comment->getContent() . '</p>';
            $generatedHTML .= '</div>';
        }
        return $generatedHTML;
    }
}
?>
