<?php

/**
 * OrongoStyle Interface
 * @author Jaco Ruit
 */
interface IOrongoStyle {
    
    //No params for construct.
    public function __construct();
    
    /**
     * Runs the style, do you things here.
     * Smarty object will be given by the CMS.
     * @param Smarty $paramSmarty Smarty Object given by CMS
     */
    public function run(&$paramSmarty);
    
    /**
    /*
     * Checks if the style generates the HTML for page
     * @return boolean indicating if the style generates page HTML
     
    public function doPageHTML();
    
    /**
     * Checks if the style generates the HTML for article
     * @return boolean indicating if the style generates article HTML
     
    public function doArticleHTML();
    */
    
    /**
     * Generates the HTML for an article array shown in archive etc.
     * @param array $paramArticles Array of articles
     */
    public function getArticlesHTML($paramArticles);
    
    /**
     * Generates the HTML for an article 
     * @param Article $paramArticle Article object to generate HTML from
     */
    public function getArticleHTML($paramArticle);
    
    /**
     * Generates the HTML for a page
     * @param Page $paramPage Page object to generate HTML from
     */
    public function getPageHTML($paramPage);
}
?>
