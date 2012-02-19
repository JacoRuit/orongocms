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
        /**OrongoEventManager::addEventHandler('article_edit', $eventhandlers, 'onArticleEdit');
        OrongoEventManager::addEventHandler('article_created', $eventhandlers, 'onArticleCreated');
        OrongoEventManager::addEventHandler('article_deleted', $eventhandlers, 'onArticleDeleted');
        OrongoEventManager::addEventHandler('user_edit', $eventhandlers, 'onUserEdit');
        OrongoEventManager::addEventHandler('user_created', $eventhandlers, 'onUserCreated');
        OrongoEventManager::addEventHandler('user_deleted', $eventhandlers, 'onArticleDeleted');
        OrongoEventManager::addEventHandler('page_edit', $eventhandlers, 'onPageEdit');
        OrongoEventManager::addEventHandler('page_created', $eventhandlers, 'onPageCreated');
        OrongoEventManager::addEventHandler('page_deleted', $eventhandlers, 'onPageDeleted');
        OrongoEventManager::addEventHandler('comment_created', $eventhandlers, 'onCommentCreated');
        OrongoEventManager::addEventHandler('comment_deleted', $eventhandlers, 'onCommentDeleted');**/
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
        
    }
    
    public function onUserDeleted(){
        
    }
    
    public function onUserCreated(){
        
    }
    
    public function onCommentDeleted(){
        
    }
    
    public function onCommentCreated(){
        
    }
}

?>
