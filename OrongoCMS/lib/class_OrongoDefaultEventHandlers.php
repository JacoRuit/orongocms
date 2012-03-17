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
       /** OrongoEventManager::addEventHandlers(array(
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
        ), new OrongoDefaultEventHandlers());**/
        Article::$CreateEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Article created"), l("Article was created by", User::getUserName($args['by'])));
            }
        });
        Article::$EditEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Article edited"), l("Article was edited by", User::getUserName($args['by'])));
            }
        });
        Article::$DeleteEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Article deleted"), l("Article was deleted by", User::getUserName($args['by'])));
            }
        });
        
        Page::$CreateEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Page created"), l("Page was created by", User::getUserName($args['by'])));
            }
        });
        Page::$EditEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Page edited"), l("Page was edited by", User::getUserName($args['by'])));
            }
        });
        Page::$DeleteEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Page deleted"), l("Page was deleted by", User::getUserName($args['by'])));
            }
        });
        
        User::$CreateEvent->subscribe(function($args){
            $newUser = new User($args['user_id']);
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("User created"), l("Just registered", $newUser->getName()));
            }
        });
        User::$EditEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("User edited"), l("User was edited by", User::getUserName($args['by'])));
            }
        });
        User::$DeleteEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("User deleted"), l("User was deleted by", User::getUserName($args['by'])));
            }
        });
        
        Comment::$CreateEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Comment posted"), l("Comment was posted by", User::getUserName($args['by'])));
            }
        });
        Comment::$DeleteEvent->subscribe(function($args){
            $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
            foreach($admins as $user){
                if(($user instanceof User) == false) continue;
                $user->notify(l("Comment deleted"), l("Comment was deleted by", User::getUserName($args['by'])));
            }
        });
    }
    
    /**
    public function onArticleEdit($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Article edited"), l("Article was edited by", User::getUserName($args['by'])));
        }
    }
    
    public function onArticleDeleted($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Article deleted"), l("Article was deleted by", User::getUserName($args['by'])));
        }
    }
    
    public function onArticleCreated($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Article created"), l("Article was created by", User::getUserName($args['by'])));
        }
    }**/
    
   /** public function onPageEdit($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Page edited"), l("Page was edited by", User::getUserName($args['by'])));
        }
    }
    
    public function onPageDeleted($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Page deleted"), l("Page was deleted by", User::getUserName($args['by'])));
        }
    }
    
    public function onPageCreated($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Page created"), l("Page was created by", User::getUserName($args['by'])));
        }
    }**/
  /**  
    public function onUserEdit($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("User edited"), l("User was edited by", User::getUserName($args['by'])));
        }
    }
    
    public function onUserDeleted($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("User deleted"), l("User was deleted by", User::getUserName($args['by'])));
        }
    }
    
    public function onUserCreated($args){
        $newUser = new User($args['user_id']);
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("User created"), l("Just registered", $newUser->getName()));
        }
    }
    
    public function onCommentDeleted($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Comment deleted"), l("Comment was deleted by", User::getUserName($args['by'])));
        }
    }
    
    public function onCommentCreated($args){
        $admins = orongo_query("action=fetch&object=user&max=100000&where=user.rank:admin");
        foreach($admins as $user){
            if(($user instanceof User) == false) continue;
            $user->notify(l("Comment posted"), l("Comment was posted by", User::getUserName($args['by'])));
        }
    }**/
}

?>
