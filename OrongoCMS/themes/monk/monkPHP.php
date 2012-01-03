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
        try{
            $vars = $smarty->getTemplateVars();
            $menu = $vars['menu'];
            //$count = Utils::stringTimesContains($menu, '<li>');
            //if($count > 5){
            //    $smarty->assign('menu',str_replace('<li>', '<li style="font-size:1px;">', $menu));
            //}
        }catch(Exception $e){ }
        $settings = Style::getSettings();
        foreach($settings as $setting=>$value){
            $smarty->assign($settings,$value);
        }
    }
    
    public function getArticlesHTML($paramArticles){ 
        $generatedHTML = "";
        
        if(is_array($paramArticles) == false) return null; //Sup, Orongo? U nooo pass me an array :(
        
        $count = count($paramArticles);
        
        $generatedCount = 0;
        foreach($paramArticles as $article){
            $last = false;
            if(($article instanceof Article) == false) continue;
            $generatedCount++;
            if($generatedCount == $count) $last = true; 
            $generatedHTML .= '<div class="one_fourth ';
            if($last) $generatedHTML .= 'column-last';
            $generatedHTML .= ' ">';
            $generatedHTML .= '<a href="'. Settings::getWebsiteURL() . 'article.php?id=' . $article->getID() . '"><h3>' . $article->getTitle() . '</h3></a>';
            $generatedHTML .= '<p>' . substr($article->getContent(), 0 ,500) . '</p>';
            $generatedHTML .= '</div>';
        }
        
        return $generatedHTML;
    }
    

    public function getArticleHTML($paramArticle){ 
        if(($paramArticle instanceof Article) == false) return null;
        $author = $paramArticle->getAuthor();
        if($author == null && ($author instanceof User) == false) $author_name = "Unknown"; else $author_name = $author->getName();
        $generatedHTML = "<div class=\"box\"><h1>" . $paramArticle->getTitle() . "</h1><p>Written by " . $author_name . "  on  " . $paramArticle->getDate() ."</p></div>";
        $generatedHTML .= $paramArticle->getContent();
        $generatedHTML .= "<br /><br /><br />";
        return $generatedHTML;
    }
    

    public function getPageHTML($paramPage){ return null; }
}
?>
