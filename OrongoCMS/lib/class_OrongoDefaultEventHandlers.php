<?php

/**
 * OrongoDefaultEventHandlers Class
 *
 * @author Jaco Ruit
 */
class OrongoDefaultEventHandlers {
    
    /**
     * Inits all the event handlers 
     */
    public static function init(){
        OrongoEventManager::addEventHandlers(array(
            "article_edit" => "onArticleEdit",
            "article_created" => "onArticleCreated",
            "article_deleted" => "onArticleDeleted",
            "user_edit" => "onUserEdit",
            "user_created" => "onUserCreated",
            "user_deleted" => "onUserDeleted",
            "page_edit" => "onPageEdit",
            "page_created" => "onPageCreated",
            "page_deleted" => "onPageDeleted",
            "comment_created" => "onCommentCreated",
            "comment_deleted" => "onCommentDeleted",
        ), new OrongoDefaultEventHandlers());
    }
    
    public function onArticleEdit(){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Article has been edited!"), l("Was edit by"));
        }
    }
    
    public function onArticleDeleted(){
        
    }
    
    public function onArticleCreated(){
        
    }
    
    public function onPageEdit(){
        
    }
    
    public function onPageDeleted(){
        
    }
    
    public function onPageCreated(){
        
    }
    
    public function onUserEdit(){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("User has been edited!"), l("Was edit by"));
        }
    }
    
    public function onUserDeleted(){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("User has been deleted"), l("Was edit by"));
        }
    }
    
    public function onUserCreated(){
        
    }
    
    public function onCommentDeleted(){
        
    }
    
    public function onCommentCreated(){
        
    }
}

?>
