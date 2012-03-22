<?php
/**
 * Articles OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptArticles extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(
            new FuncArticleGetID(), 
            new FuncArticleSetTitle(), 
            new FuncArticleSetContent(), 
            new FuncArticleGetCommentCount(),
            new FuncArticleGetCommentIDs()
        );
    }
}



/**
 * GetID OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncArticleGetID extends OrongoFunction {
   
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Articles.GetID()");
        $id = Article::getArticleID($args[0]);
        if($id == null) throw new Exception("Article not found!");
        return new OrongoVariable($id);
    }

    public function getShortname() {
        return "GetID";
    }
    
    public function getSpace(){
        return "Articles";
    }
}

/**
 * SetTitle OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleSetTitle extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Articles.SetTitle()"); 
        $article = new Article($args[0]);   
        $article->setTitle($args[1]);
    }

    public function getShortname() {
        return "SetTitle";
    }
    
    public function getSpace(){
        return "Articles";
    }
}

/**
 * GetTitle OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetTitle extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Articles.GetTitle()"); 
        $article = new Article($args[0]);   
        return new OrongoVariable($article->getTitle());
    }

    public function getShortname() {
        return "GetTitle";
    }
    
    public function getSpace(){
        return "Articles";
    }
}

/**
 * SetContent OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleSetContent extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Articles.SetContent()"); 
        $article = new Article($args[0]);   
        $article->setContent($args[1]);
    }

    public function getShortname() {
        return "SetContent";
    }
    
    public function getSpace(){
        return "Articles";
    }
}


/**
 * GetContent OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetContent extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Articles.GetContent()"); 
        $article = new Article($args[0]);   
        return new OrongoVariable($article->getContent());
    }

    public function getShortname() {
        return "GetContent";
    }
    
    public function getSpace(){
        return "Articles";
    }
}

/**
 * SetTags OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleSetTags extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Articles.SetTags()");
        $article = new Article($args[0]);   
        unset($args[0]);
        $article->setTags($args);
    }

    public function getShortname() {
        return "SetTags";
    }
    
    public function getSpace(){
        return "Articles";
    }

}

/**
 * GetTags OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetTags extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Articles.GetTags()"); 
        $article = new Article($args[0]);  
        $tags = $article->getTags();
        foreach($tags as &$tag){  $tag = new OrongoVariable($tag); }
        return $tags;
    }

    public function getShortname() {
        return "GetTags";
    }
    
    public function getSpace(){
        return "Articles";
    }
}

/**
 * GetAuthorID OrongoScript function 
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetAuthorID extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for Articles.GetAuthorID()");
        $article = new Article($args[0]);   
        return new OrongoVariable($article->getAuthorID());
    }

    public function getShortname() {
        return "GetAuthorID";
    }
    
    public function getSpace(){
        return "Articles";
    }

}

/**
 * GetCommentCount OrongoScript function
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetCommentCount extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for Articles.GetCommentCount()");
        $article = new Article($args[0]);   
        return new OrongoVariable($article->getCommentCount());
    }

    public function getShortname() {
        return "GetCommentCount";
    }
    
    public function getSpace(){
        return "Articles";
    }

}

/**
 * GetCommentIDs OrongoScript function
 * 
 * @author Jaco Ruit
 */
class FuncArticleGetCommentIDs extends OrongoFunction {
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for Articles.GetCommentCount()");
        $article = new Article($args[0]);   
        $comments = $article->getComments();
        $ids = array();
        foreach($comments as $comment){
            if(($comment instanceof Comment) == false) continue;
            $ids[count($ids)] = new OrongoVariable($comment->getID());
        }
        return $ids;
    }

    public function getShortname() {
        return "GetCommentIDs";
    }
    
    public function getSpace(){
        return "Articles";
    }

}
?>
