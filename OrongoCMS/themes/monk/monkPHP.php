<?php
/**
 * http://www.makimyers.co.uk/monk-free-html-template/
 * 
 * Monk Style needed to generate own Article HTML ;)
 * @author Jaco Ruit
 */

class MonkStyle implements IOrongoStyle{
    
    public function __construct(){}
    
    public function run(&$smarty){}
    
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
            $generatedHTML .= '<h3>' . $article->getTitle() . '</h3>';
            $generatedHTML .= '<p>' . substr($article->getContent(), 0 ,500) . '</p>';
            $generatedHTML .= '</div>';
        }
        
        return $generatedHTML;
    }
    

    public function getArticleHTML($paramArticle){ return null; }
    

    public function getPageHTML($paramPage){ return null; }
}
?>
