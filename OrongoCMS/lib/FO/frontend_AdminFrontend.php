<?php
/**
 * Admin frontend.
 *  Loads of classes! :)
 * 
 * @author Jaco Ruit
 */
class AdminFrontend extends OrongoFrontendObject {
    
    private $body;
    private $pageTemplate;
    private $pageTitle;
    private $msgs;
    private $objects;
    
    private static $msgTypes = array("warning","success","info","error");
    
    public function __construct(){
        $this->msgs = array();
    }
    
    public function main($args){
        getDisplay()->setTemplateDir(ROOT . "/orongo-admin/theme/");
        if(!isset($args['page_title'])){
            $msgbox = new MessageBox("Can't render admin frontend: missing argument 'page_title'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(!isset($args['page_template'])){
           $msgbox = new MessageBox("Can't render admin frontend: missing argument 'page_template!");
           die($msgbox->getImports() . $msgbox->toHTML()); 
        }
        if(!is_string($args['page_template'])){
            $msgbox = new MessageBox("Can't render admin frontend: wrong argument 'page_template'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(!is_string($args['page_title'])){
            $msgbox = new MessageBox("Can't render admin frontend: wrong argument 'page_title'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->pageTemplate = $args['page_template'];
        $this->objects = array();
        $this->pageTitle = l($args['page_title']);
    }
    

    
    /**
     * Adds a message to the admin board
     * @param String $paramMsg Message string
     * @param String $paramMsgType Msg type must be warning, info, success or error
     */
    public function addMessage($paramMsg, $paramMsgType){
        if(!in_array($paramMsgType, self::$msgTypes)) throw new IllegalArgumentException("Invalid message type!");
        $newmsg = array(
            "msg" => $paramMsg,
            "msgtype" => $paramMsgType
        );
        $this->msgs[count($this->msgs) -1 ] = $newmsg;
    }
    
    /**
     * Adds object to the admin board
     * @param AdminFrontendObject $paramObject object to add
     */
    public function addObject($paramObject){
        if(($paramObject instanceof AdminFrontendObject) == false) throw new IllegalArgumentException("Invalid argument, AdminFrontendObject expected!");
        $this->objects[count($this->objects) - 1] = $paramObject;
    }
    
    /**
     * Deletes object from admin board
     * @param AdminFrontendObject $paramObject object to delete 
     * @return boolean indicating if delete was successful
     */
    public function deleteObject($paramObject){
        if(($paramObject instanceof AdminFrontendObject) == false) throw new IllegalArgumentException("Invalid argument, AdminFrontendObject expected!");
        foreach($this->objects as &$object){
            if($object == $paramObject){
                unset($object);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Set page title
     * @param String $paramTitle new title 
     * @param boolean $paramTranslate indicating if title should be translated (default true)
     */
    public function setTitle($paramTitle, $paramTranslate = true){
        if(!$paramTranslate){
            $this->pageTitle = $paramTitle;
            return;
        }
        $this->pageTitle = l($paramTitle);
    }
    
    
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . $this->pageTitle );
        getDisplay()->setTemplateVariable("body", $this->body);
    
        if(count($this->msgs) > 0){
        $msgstring = "";
            foreach($this->msgs as $msg){
                if(!is_array($msg)) continue;
                $msgstring .= '<h4 class="alert_' . $msg['msgtype'] . '">' . $msg['msg'] . "</h4>";
            }
            getDisplay()->setTemplateVariable("msgs", $msgstring);
        }
        
        $objectshtml = "";
        foreach($this->objects as $object){
            if(($object instanceof AdminFrontendObject) == false)continue;
            $objectshtml .= $object->toHTML();
        }
        
        getDisplay()->setTemplateVariable("objects", $objectshtml);
        getDisplay()->setTemplateVariable("current_page", $this->pageTitle);
        
        getDisplay()->setTemplateVariable("style_url", Settings::getWebsiteURL() ."orongo-admin/theme/");
        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add($this->pageTemplate);
        getDisplay()->render();
    }
}


class AdminFrontendObject implements IHTMLConvertable{
    
    private $header;
    private $footer;
    private $content;
    private $size;
    
    private static $sizes = array("75" => "3_quarter", "50" => "half", "25" => "quarter", "100" => "full");
    /**
     *Init admin frontend object
     * @param int $paramSize 25,50,75 or 100
     * @param String $paramTitle Title of object
     * @param String $paramContent Content of object
     * @param String $paramFooter footer of object (optional)
     * @param boolean $paramTranslate indicating if title should be translated (Default true)
     */
    public function __construct($paramSize, $paramTitle, $paramContent, $paramFooter = null, $paramTranslate = true){
        if(!array_key_exists($paramSize, self::$sizes)) throw new IllegalArgumentException("Invalid size!");
        if($paramTranslate && !empty($paramTitle)) $paramTitle = l($paramTitle);
        $this->header = '<h3>' . $paramTitle . '</h3>';
        $this->content = '<div class="module_content">' . $paramContent . '</div><div class="clear"></div>';
        $this->footer =  $paramFooter;
        $this->size = self::$sizes[$paramSize];
    }
    
    /**
     * @return String title of object 
     */
    public function getTitle(){
        if(!stristr("</h3>", $this->header)) return "";
        $strippedH = explode("</h3>", $this->header);
        $title = $strippedH[0];
        if(!stristr("<h3>", $title)) return "";
        $title = explode("<h3>", $this->header);
        return end($title);
    }
    
    /**
     * Sets title of object
     * @param String $paramTitle new title 
     */
    public function setTitle($paramTitle){
        $this->header = str_replace("<h3>" .$this->getTitle() ."</h3>", "<h3>" . l($paramTitle) . "</h3>", $this->header);
    }
    
    /**
     * Gets content of object 
     * @return String content
     */
    public function getContent(){
        $str = str_replace("<div class=\"module_content\">", "", $this->content);
        $str = strrev($str);
        $str = str_replace(strrev('</div><div class="clear"></div>'), "", $str);
        return strrev($str);
    }
    
    /**
     * Sets the content of object 
     * @param String $paramContent new content
     */
    public function setContent($paramContent){
        $this->content = "<div class=\"module_content\">" . $paramContent . '</div><div class="clear"></div>';
    }
    
    /**
     * Gets raw content 
     * @return String content
     */
    public function getRawContent(){
        return $this->content;
    }
    
    /**
     * Sets raw content
     * @param String $paramContent new content 
     */
    public function setRawContent($paramContent){
        $this->content = $paramContent;
    }
    
    /**
     * Gets the footer
     * @return String footer 
     */
    public function getFooter(){
        return $this->footer;
    }
    
    /**
     * Sets the footer
     * @param String $paramFooter new footer 
     */
    public function setFooter($paramFooter){
        $this->footer = $paramFooter;
    }
    
    /**
     * Sets the raw header 
     * @param String $paramHeader new header
     */
    public function setHeader($paramHeader){
        $this->header = $paramHeader;
    }
    
    /**
     * Gets raw header
     * @return String raw header
     */
    public function getHeader(){
        return $this->header;
    }

    public function toHTML() {
        $rt = "<header>". $this->header . "</header>" . $this->content;
        if($this->footer != null) $rt .= "<footer>" . $this->footer . "</footer>";
        return "<article class=\"module width_". $this->size . "\">" . $rt . "</article>";
    }
}

class AdminFrontendForm extends AdminFrontendObject{
    
    private $method;
    private $action;
    private $buttons;
    private $inputs;
    private $selects;
    
    private static $methods = array("get", "post");
    
    /**
     * Inits the form
     * @param int $paramSize 25,50,75 or 100
     * @param String $paramTitle title of form
     * @param String $paramMethod POST or GET
     * @param String $paramAction action of the form
     * @param boolean $paramTranslate indicating if title should be translated (default true)
     */
    public function __construct($paramSize, $paramTitle, $paramMethod, $paramAction, $paramTranslate=true){
        $this->method = strtolower($paramMethod);
        $this->action = $paramAction;
        $this->buttons = array();
        $this->inputs = array();
        $this->selects = array();
        if(!in_array($this->method, self::$methods)) throw new IllegalArgumentException("Invalid method!");
        parent::__construct($paramSize, $paramTitle, "", null, $paramTranslate);
    }
    
    /**
     * Gets the action
     * @return String action 
     */
    public function getAction(){
        return $this->action;
    }
    
    /**
     * Sets the action
     * @param String $paramAction new action
     */
    public function setAction($paramAction){
        $this->action = $paramAction;
        $this->updateHTML();
    }
    
    /**
     * Adds an input
     * 
     * @param String $paramLabel label for the input
     * @param String $paramName name of the input
     * @param String $paramType HTML form type
     * @param String $paramValue value of the form (default nothing)
     * @param boolean $paramRequired indicating if this is required (default false)
     * @param boolean $paramReadOnly indicating if input should be read only
     * @param boolean $paramTranslate indicating if label should be translated (default true)
     */
    public function addInput($paramLabel, $paramName, $paramType, $paramValue = "", $paramRequired = false, $paramReadOnly = false, $paramTranslate = true){
        $label = $paramTranslate ? l($paramLabel) : $paramLabel;
        $input = array(
           "type" => $paramType,  
           "label" => $label,
           "name" => $paramName,
           "value" => $paramValue,
           "required" => $paramRequired,
           "readonly" => $paramReadOnly
        );
        $this->inputs[count($this->inputs)] = $input;
        $this->updateHTML();
    }
    
    /**
     * Adds a button
     * @param String $paramText text of the button
     * @param boolean $paramBlue indicating if this buttons has to be blue
     * @param String $paramURL the location the button should redirect to (default: act as submit button)
     */
    public function addButton($paramText, $paramBlue, $paramURL = null){
        $button = array(
            "text" => l($paramText),
            "blue" => $paramBlue,
            "url" => $paramURL
        );
        $this->buttons[count($this->buttons)] = $button;
        $this->updateHTML();
    }
    
    /**
     * Adds a select dropdown box
     * @param String $paramName name of the select (name attr)
     * @param array $paramThings Things to add (KEY = option name, STR=option value) 
     */
    public function addSelect($paramName, $paramThings){
        $this->selects[$paramName] = $paramThings;
        $this->updateHTML();
    }

    /**
     * Updates the AdminFrontendObject 
     */
    private function updateHTML(){
        $content = "<form action=\"" . $this->action . "\" method = \"" . $this->method . "\">";
        foreach($this->inputs as $input){
            $content .= "<fieldset>";
            $content .= "<label>" . $input['label'] . "</label>";
            if($input['type'] == "ckeditor"){
                $content .= "<br/><br/>";
                $ckeditor = new CKEditor();
                if($input['readonly']) $ckeditor->config['readOnly'] = true;
                $ckeditor->basePath  =  '../lib/ckeditor/';
                $ckfinder = new CKFinder();
                $ckfinder->BasePath = '../lib/ckfinder/'; 
                $ckfinder->SetupCKEditorObject($ckeditor);
                $ckeditor->returnOutput = true;
                $content .= $ckeditor->editor($input['name'], $input['value']);
            }else if($input['type'] == "textarea"){
                $content .= "<textarea rows=\"20\" name=\"" . $input['name'] ."\" ";
                if($input['readonly']) $content.= 'disabled="disabled" ';
                if($input['required']) $content .= 'required="required" ';
                $content .= ">" . $input['value'] ."</textarea>";
            }else{
                $content .= "<input type=\"" . $input['type'] . "\" name=\"" . $input['name'] . "\" value=\"" . $input['value'] . "\" ";
                if($input['required']) $content .= " required=\"required\" ";
                if($input['readonly']) $content.= 'disabled="disabled" ';
                $content .= '>';
            }
            $content .= "</fieldset>";
        }
        parent::setContent($content);
        
        if(count($this->buttons) > 0){
            $footer = "<div class=\"submit_link\">";
            foreach($this->selects as $name=>$items){
                if(!is_array($items)) continue;
                $footer .= '<select name="' . $name . '">';
                foreach($items as $optionname=>$optionvalue){
                    $footer .= '<option value="' . $optionvalue . '">' . $optionname . '</option>';
                }
                $footer .= '</select>';
            }
            foreach($this->buttons as $button){
                if($button['url'] != null){ 
                    $footer .= '<a href="' . $button['url'] . '">';
                    $footer .= "<input type=\"button\" value=\"" . $button['text'] . "\" ";
                    if($button['blue']) $footer .= 'class="alt_btn" >';
                    else $footer .= ">";
                    $footer .= '</a>';
                }else{
                    $footer .= "<input type=\"submit\" value=\"" . $button['text'] . "\" ";
                    if($button['blue']) $footer .= 'class="alt_btn" >';
                    else $footer .= ">";
                }
            }
            
            $footer .= "</div></form>";
            parent::setFooter($footer);
        }else{
            parent::setContent(parent::getContent() . "</form>");
        }
        
    }
}

class AdminFrontendContentManager extends AdminFrontendObject{
    
    private $tabs;
    private $tabheads;
    private $title;
    private $hideEditButton;
    private $hideTrashButton;
    
    /**
     * Init the Content Manager
     * @param int $paramSize 25,50,75 or 100
     * @param String $paramTitle title of content manager  
     */
    public function __construct($paramSize, $paramTitle){
        $this->tabs = array();
        $this->tabheads = array();
        $this->title = l($paramTitle);
        parent::__construct($paramSize, $paramTitle, "");
        $this->updateHTML();
        $this->hideEditButton = false;
        $this->hideTrashButton = false;
    }
    
    /**
     * setTitle override
     * @param String $paramTitle new Title 
     */
    public function setTitle($paramTitle){
        $this->title = l($paramTitle);
    }
    
    /**
     * geTitle override
     * @return String title of content manager 
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
     * Creates a tab
     * @param String $paramName name of the tab
     * @param array $paramHead Things on head of tab 
     */
    public function createTab($paramName, $paramHead){
        $this->tabs[$paramName] = array();
        $paramHead[count($paramHead) + 1] = "Actions";
        $this->tabheads[$paramName] = $paramHead ;
        $this->updateHTML();
    }
    
    /**
     * Deletes a tab
     * @para, String $paramName name of the tab
     * @return boolean indicating if it was deleted
     */
    public function deleteTab($paramName){
        if(!isset($this->tabs[$paramName])) return false;
        unset($this->tabs[$paramName]);
        if(isset($this->tabheads[$paramName])) unset($this->tabheads[$paramName]);      
        $this->updateHTML();
        return true;
    }
    
    /**
     * Add item to tab
     * @param String $paramName name of the tab
     * @param array  $paramItems Items to add
     * @param String $paramDeleteURL Delete URL
     * @param String $paramEditURL Edit URL
     * @return boolean indicating if was added
     */
    public function addItem($paramName, $paramItems, $paramDeleteURL, $paramEditURL){
        if(!isset($this->tabs[$paramName])) return false;
        if(!is_array($paramItems)) return false;
        $goodaction = array(
            "__actions" => array(
                "delete" => $paramDeleteURL,
                "edit" => $paramEditURL
            )
        );
        if(isset($paramItems['override_actions']))
            $this->tabs[$paramName][count($this->tabs[$paramName])] = $paramItems;
        else
            $this->tabs[$paramName][count($this->tabs[$paramName])] = $paramItems + $goodaction;
        $this->updateHTML();
        return true;
    }
    
    /**
     * Hides the edit button 
     */
    public function hideEditButton(){
        $this->hideEditButton = true;
        $this->updateHTML();
    }
    
    /**
     * Hides the trash button 
     */
    public function hideTrashButton(){
        $this->hideTrashButton = true;
        $this->updateHTML();
    }
    

    
    /**
     * Updates the AdminFrontendObject 
     */
    public function updateHTML(){
        $head = "<ul class=\"tabs\">";
        $content = "<div class=\"tab_container\">";
        $currentTab = 1;
        foreach($this->tabs as $name=>$items){
            if(!is_array($items)) continue;
            $head .= '<li><a href="#tab' . $currentTab . '">' . l($name) . '</a></li>';
            $content .= '<div id="tab'  . $currentTab . '" class="tab_content">';
            $content .= '<table class="tablesorter" cellspacing="0"> <thead><tr> ';
            if(isset($this->tabheads[$name])){
                if(is_array($this->tabheads[$name])){
                    foreach($this->tabheads[$name] as $tabhead){
                        $content .= '<th>'  . l($tabhead) . '</th>';
                    }
                }
            }
            $content .= '</tr> </thead><tbody>';
            if(is_array($items)){
                foreach($items as $itemarray){
                    $content .= '<tr>';
                    foreach($itemarray as $itemname => $item){
                        if(is_array($item) && $itemname == '__actions' && !isset($itemarray['override_actions'])){ 
                            $content .= '<td>';
                            if(!$this->hideEditButton) 
                                $content .= '<a href="' . $item['edit'] . '"><input type="image" src="' . orongoURL('orongo-admin/theme/images/icn_edit.png') . '" title="Edit"></a>';
                            if(!$this->hideTrashButton)
                                $content .= '<a href="' . $item['delete'] . '"><input type="image" src="' . orongoURL('orongo-admin/theme/images/icn_trash.png') . '" title="Trash"></a>';
                            $content .= '</td>';
                        }else if(is_array($item) && $itemname == 'override_actions'){
                            $content .= '<td>';
                            foreach($item as $html){
                                if(!is_string($html)) continue;
                                $content .= $html . "  ";
                            }
                            $content .= '</td>';
                        }
                        else $content .= '<td>' . $item . '</td>';  
                    }
                    $content .= '</tr>';
                }
            }
            $content .= '</tbody></table></div>';
            $currentTab++;
        }
        $head .= "</ul>";
        $content .= "</div>";
        parent::setRawContent($content);
        parent::setHeader('<h3 class="tabs_involved">' . $this->title .'</h3>' . $head);
    }
}

?>
