<?php
/**
 * HTMLGenerator Class
 *
 * @author Jaco Ruit
 */
class HTMLFactory {
    
    /**
     * Generates HTML menu code from a page array
     * @param array $paramPages Pages array from Page::getPages()
     * @return String Generated HTML Code
     */
    public static function getMenuCode($paramPages){
        $websiteURL = Settings::getWebsiteURL();
        if(is_array($paramPages) == false) throw new IllegalArgumentException("Invalid paramater, array expected.");
        $generatedHTML = "<ul>";
        $generatedHTML .= " <li><a href=\"". $websiteURL . "index.php\">Home</a></li>";
        foreach($paramPages as $page){
            if(!is_object($page)) continue;
            if($page instanceof Page){
                //TODO page URL fix
                $generatedHTML .= " <li><a href=\"". $websiteURL . "page.php?id=" . $page->getID() . "\">" . $page->getTitle() . "</a></li>";
            }else continue;
        }
        $generatedHTML .= "</ul>";
        
        return $generatedHTML;
    }
}

?>
