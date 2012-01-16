<?php
//TODO RENAME OR SOMETHING OR GET A MORE OOP SOLUTION FOR THIZ LIKE CREATING MENULINKS CLASS OR STH
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
        $generatedHTML = " <li><a href=\"". $websiteURL . "index.php\">Home</a></li>";
        if(is_array($paramPages) == false) throw new IllegalArgumentException("Invalid paramater, array expected.");
        foreach($paramPages as $page){
            if(!is_object($page)) continue;
            if($page instanceof Page){
                //TODO page URL fix
                $generatedHTML .= " <li><a href=\"". $websiteURL . "page.php?id=" . $page->getID() . "\">" . $page->getTitle() . "</a></li>";
            }else continue;
        }
        $generatedHTML .= " <li><a href=\"". $websiteURL . "archive.php\">Archive</a></li>";
        return $generatedHTML;
    }
}

?>
