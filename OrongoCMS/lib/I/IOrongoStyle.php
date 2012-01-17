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
     */
    public function run();
    
    
    /**
     * Generates the HTML for an article array shown in archive etc.
     * @param array $paramArticles Array of articles
     */
    public function getArticlesHTML($paramArticles);
    
        
    
    /**
     * Generates the HTML for a comment array
     * @param array $paramComments Array of comments
     */
    public function getCommentsHTML($paramComments);
}
?>
