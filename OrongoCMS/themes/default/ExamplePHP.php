<?php


/**
 * ExamplePHP for styles
 * @author Jaco Ruit
 */
class DefaultStylePHP implements IOrongoStyle{
    
    public function __construct(){
        //NO PARAMS
    }
    
    public function run(&$smarty){
    }
    
    public function getArticlesHTML($paramArticles){ return null; }
    

    public function getArticleHTML($paramArticle){ return null; }
    

    public function getPageHTML($paramPage){ return null; }
}
?>
