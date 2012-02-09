<?php

/**
 * Menu Class
 *
 * @author Jaco Ruit
 */

class Menu implements IHTMLConvertable{
    
    private $URLs;
    private $flip;
    
    /**
     * Init Menu 
     */
    public function __construct(){
        $this->flip = false;
        $this->URLs = array();
        $this->hookURL(orongoURL("index.php"), l("Home"), 0);
        if(Settings::showArchive()){
            $this->hookURL(orongoURL("archive.php"), l("Archive"), 10000) ;
        }
        $pages = @orongo_query('action=fetch&object=page&max=10000&order=page.id');
        if($pages != false){
            $c = 1;
            foreach($pages as $page){
                if(($page instanceof Page) == false) continue;
                $this->hookURL(orongoURL("page.php?id=" . $page->getID()), $page->getTitle(), $c);
                //C++, SOOO HARDCORE :D
                $c++;
            }
        }
    }
    
    /**
     * Hook URL into the menu 
     * @param String $paramURL URL
     * @param String $paramURLName Name of URL (Placed between the <a>'s)
     * @param int $paramURLPlace Place of the URL in menu (1031 to add to end)
     */
    public function hookURL($paramURL, $paramURLName, $paramURLPlace = 1031){
        if($this->isPlaceTaken($paramURLPlace)) $paramURLPlace = 1031;
        if($paramURLPlace == 1031) $paramURLPlace = $this->getLatestPlace() + 1;
        $this->URLs[count($this->URLs)] = array(
            "url" => $paramURL,
            "url_name" => $paramURLName,
            "url_place" => $paramURLPlace
        );
    }
    
    /**
     * Deletes URL from menu
     * @param String $paramURLName Name of URL 
     * @return boolean indicating if was deleted succesfully
     */
    public function deleteURL($paramURLName){
        foreach($this->URLs as &$url){
            if(!is_array($url)) continue;
            if($url['url_name'] == $paramURLName){
                unset($url);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets place of URL in menu
     * @param String $paramURLName Name of URL
     * @param int $paramURLPlace Place of the URL in menu (1031 to add to end)
     * @return boolean indicating if place was changed
     */
    public function setURLPlace($paramURLName, $paramURLPlace = 1031){
        if($paramURLPlace == 1031) $paramURLPlace = $this->getLatestPlace() + 1;
        if($this->isPlaceTaken($paramURLPlace)) $paramURLPlace = 1031;
        foreach($this->URLs as &$url){
            if(!is_array($url)) continue;
            if($url['url_name'] == $paramURLName){
                $url['url_place'] = $paramURLPlace;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Checks if URL place is taken
     * @param int $paramURLPlace Place of the URL in menu 
     */
    public function isPlaceTaken($paramURLPlace){
        if($paramURLPlace == 1031) return false;
        foreach($this->URLs as &$url){
            if(!is_array($url)) continue;
            if($url['url_place'] == $paramURLPlace) return true;
        }
        return false;
    }
    
    
    /**
     * Get last place in menu
     * @return int last place in menu 
     */
    private function getLatestPlace(){
        $last = -100000000;
        foreach($this->URLs as $url){
            if($url['url_place'] > $last) $last = $url['url_place'];
        }
        if($last == -100000000) $last = 0;
        return $last;
    }
    
    /**
     * Flips the menu 
     * You will probably use this if you have right float on the menu 
     */
    public function flip(){
        $this->flip = !$this->flip;
    }
    
    
    
    public function toHTML() {
        $goodOrder = array();
        foreach($this->URLs as &$url){
            if(!is_array($url)) continue;
            if(isset($goodOrder[$url['url_place']])) continue;
            $goodOrder[$url['url_place']] = $url;
        }
        ksort($goodOrder, SORT_NUMERIC);
        if($this->flip){
            $goodOrder = array_reverse($goodOrder);
        }
        $html = "";
        foreach($goodOrder as $url){
            $html .= "<li><a href=\"" . $url['url'] . "\">" . $url['url_name'] . "</a></li>";
        }
        return $html;
    }
}

?>
