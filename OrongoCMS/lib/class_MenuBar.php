<?php

/**
 * MenuBar class
 *
 * @author Jaco Ruit
 */
class MenuBar implements IHTMLConvertable {
    
    private $user;
    
    /**
     * Construct MessageBox Object
     * 
     * @param User $paramUser  User object
     */
    public function __construct($paramUser){
        if(($paramUser instanceof User) == false) throw new IllegalArgumentException("Invalid argument, user object expected");
        $this->user = $paramUser;
    }
    
    public function toHTML(){
        $website_url = Settings::getWebsiteURL();
        $generatedHTML = '<script src="'. $website_url . 'js/interface.menu_effects.js"  type="text/javascript" charset="utf-8"></script>';
        $generatedHTML .= '<link rel="stylesheet" href="'. $website_url . 'orongo-admin/style/style.menu.css" type="text/css"/>';
        $generatedHTML .= '<div class="orongo_menu fixed hide"><div class="seperator right hide" style="padding-right: 100px"></div><div class="menu_text right hide">Settings</div><div class="icon_settings_small right hide"></div><div class="seperator right hide"></div><div class="menu_text right hide">Notifications</div><div class="icon_messages_small right hide"></div><div class="seperator right hide"></div><div class="menu_text right hide">Pages</div><div class="icon_pages_small right hide"></div><div class="seperator right hide"></div><div class="menu_text left hide" style="padding-left: 200px"><div class="icon_account_small left"></div> Logged in as ' . $this->user->getName() . ' | <a href="'. $website_url . 'orongo-logout.php">Logout</a></div></div>';
        return $generatedHTML;
    }
}

?>
