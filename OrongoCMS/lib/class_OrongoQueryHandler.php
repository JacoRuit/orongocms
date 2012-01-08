<?php
/**
 * OrongoQueryHandler Class
 *
 * @author Jaco Ruit
 */

class OrongoQueryHandler {
    
    private static $object = array ('user', 'page', 'article', 'comment');   
    
    private static $order = array ('user' => array('user.id','user.name','user.rank', 'user.activated'), 'article' => array('article.id','article.title','article.date','author.id'), 'page' => array('page.id','page.title'), 'comment' => array('article.id', 'comment.id', 'comment.timestamp', 'author.id'));
    private static $orderc = array ('asc', 'desc');
        
    private static $where0 = array ('article' => array('author','article'), 'user' => array('user'), 'page' => array('page'), 'comment' => array('author', 'comment', 'article'));
    private static $where1 = array ('author' => array('id','name'), 'user' => array('id','name','activated','rank'), 'article' => array('id','title','date'), 'page' => array('id','title'), 'comment' => array('id', 'timestamp'));
    
    private static $bool = array('true', 'false');
    private static $ranks = array('admin', 'writer', 'user');
    /**
     * Executes an OrongoQuery
     * @param OrongoQuery $paramQuery OrongoQuery object
     * @return array Result set
     */
    public static function exec($paramQuery){
        if(($paramQuery instanceof OrongoQuery) == false) throw new IllegalArgumentException("Invalid parameter, OrongoQuery expected.");
        $query = $paramQuery->getQueryArray();
        if(!is_array($query)) throw new QueryException("The query was not initialized, please report this!");
        
        
        #   Required
        if(!isset($query['action']) || ($query['action'] != 'fetch' && $query['action'] != 'count')) throw new QueryException("Invalid query string: invalid action.");
        if(!isset($query['object']) || !in_array($query['object'], self::$object)) throw new QueryException("Invalid query string: invalid object.");  
        $from = $query['object'];
        if(!isset($query['max']) || !is_numeric($query['max'])) throw new QueryException("Invalid query string: invalid max.");
        $limit = " LIMIT " . $query['max'];
                
        #   Optional
                
        #       order
        $order = " ORDER BY `id` ";
        $orderc = " ASC ";
        
            #       FORMAT: order = {order}  _OR_   order = {order},{orderc}
        if(isset($query['order'])){ 
            if(strstr($query['order'], ",")){
                $orders = explode(",", $query['order']);
                if(count($orders) > 2 || count($orders) < 1) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[0], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[1], self::$orderc)) throw new QueryException ("Invalid query string: invalid orderc.");
                $orders[0] = str_replace("author.id", "authorID", $orders[0]);
                if($from == 'comment' && $orders[0] == 'article.id')
                    $orders[0] = str_replace("article.id", "articleID", $orders[0]);
                $order = " ORDER BY `" . str_replace($from . '.', "", $orders[0])."` ";
                $orderc = " " . strtoupper($orders[1]) . " ";
            }else{
                if(!in_array($query['order'], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                $query['order'] = str_replace("author.id", "authorID", $query['order']);
                $order = " ORDER BY `" . str_replace($from . '.', "", $query['order']) . "` ";
            }   
        }
        
        
        #       offset
        $offset = "";
        
            #       FORMAT: offset = {number}
        if(isset($query['offset'])){
            if(!is_numeric($query['offset'])) throw new QueryException("Invalid query string: invalid offset.");
            $offset = " OFFSET " . $query['offset'];
        }
        
        
        #       where
        $where = "";
            
            #       FORMAT: where = {obj}.{member}:{value}
        if(isset($query['where'])){
            if(!strstr($query['where'], ".")) throw new QueryException ("Invalid query string: invalid where.");
            $where = explode(".", $query['where']);
            if(count($where) > 2 || count($where) < 1) throw new QueryException ("Invalid query string: invalid where.");
            if(!strstr($where[1], ":")) throw new QueryException ("Invalid query string: invalid where.");
            $where1 = explode(":", $where[1]);
            if(!in_array($where[0], self::$where0[$query['object']])) throw new QueryException ("Invalid query string: invalid where.");
            if(!in_array($where1[0], self::$where1[$where[0]])) throw new QueryException ("Invalid query string: invalid where.");
            if(!is_string($where1[1]) && ($where1[0] == 'title' || $where1[0] == 'name' || $where1[0] == 'activated' || $where1[0] == 'rank')) throw new QueryException ("Invalid query string: invalid where.");
            if(!is_numeric($where1[1]) && ($where1[0] == 'id' || $where1[0] == 'timestamp'))throw new QueryException ("Invalid query string: invalid where.");
            if($where1[0] == 'activated' && !in_array($where1[1], self::$bool)) throw new QueryException ("Invalid query string: invalid where.");
            if($where1[0] == 'rank' && !in_array($where1[1], self::$ranks)) throw new QueryException ("Invalid query string: invalid where.");
            if($where[0] == 'author'){
                switch($where1[0]){
                    case 'id':
                        $where = " WHERE `authorID` = '" . $where1[1] . "' ";
                        break;
                    case 'name':
                        $uid = User::getUserID($where1[1]);
                        if($uid == "") throw new Exception("User doesnot exist!", USER_NOT_EXIST);
                        $where = " WHERE `authorID` = '" . $uid . "' ";
                        break;
                    default:
                        break;
                        
                }
            }
            else if($where[0] == 'user'){
                switch($where1[0]){
                    case 'id':
                        $where = " WHERE `id` = '" . $where1[1] . "' ";
                        break;
                    case 'name':
                        $uid = User::getUserID($where1[1]);
                        if($uid == "") throw new Exception("User doesnot exist!", USER_NOT_EXIST);
                        $where = " WHERE `id` = '" . $uid . "' ";
                        break;
                    case 'activated':
                        $act = 0;
                        if($where1[1] == 'true') $act = 1;
                        $where = " WHERE `activated` = '" . $act ."' ";
                    case 'rank':
                        $rank = 0;
                        if($where1[1] == "admin") $rank = RANK_ADMIN;
                        if($where1[1] == "writer") $rank = RANK_WRITER;
                        if($where1[1] == "user") $rank = RANK_USER;
                        $where = " WHERE `rank` = '" . $rank . "' ";
                    default:
                        break;
                        
                }
            }
            else if($where[0] == 'article'){
                switch($where1[0]){
                    case 'id':
                        if($from == 'comment') $where = " WHERE `articleID` = '" . $where1[1] . "' ";
                        else $where = " WHERE `id` = '" . $where1[1] . "' ";
                        break;
                    case 'title':
                        $aid = Article::getArticleID($where1[1]);
                        if($aid == "") throw new Exception("Article doesnot exist!", ARTICLE_NOT_EXIST);
                        if($from == 'comment') $where = " WHERE `articleID` = '" . $aid . "' ";
                        else $where = " WHERE `id` = '" . $aid . "' ";
                        break;
                    case 'date':
                        if($from == 'comment') throw new QueryException("Invalid query string: invalid where.");
                        if($where1[1] == 'now()') $where1[1] = "CURDATE()";
                        else $where1[1] = "'".$where1[1]."'";
                        $where = " WHERE `date` = " . $where1[1] . " ";
                        break;
                    default:
                        break;
                        
                }
            }
           else if($where[0] == 'page'){
                switch($where1[0]){
                    case 'id':
                        $where = " WHERE `id` = '" . $where1[1] . "' ";
                        break;
                    case 'title':
                        $pid = Page::getPageID($where1[1]);
                        if($pid == "") throw new Exception("Page doesnot exist!", PAGE_NOT_EXIST);
                        $where = " WHERE `id` = '" . $pid . "' ";
                        break;
                    default:
                        break;
                        
                }
            }
           else if($where[0] == 'comment'){
               switch($where1[0]){
                   case 'id':
                       $where = " WHERE `id` = '" . $where1[1] . "' ";
                       break;
                   case 'timestamp':
                       $where1[1] = str_replace("now()", time(), $where1[1]);
                       $where = " WHERE `timestamp` = '" . $where1[1] . "' ";
               }
           }
        }
        
        
        $q  =  "SELECT `id` FROM `" . $from . 's`' . $where  . $order . $orderc . $limit . $offset;
        $result = @mysql_query($q);
        if($query['action'] == 'fetch'){
            $resultset = array();
            $c = 0;
            while($row = mysql_fetch_assoc($result)){
                $obj = null;
                try{
                    switch($from){
                        case 'user':
                            $obj = new User($row['id']);
                            break;
                        case 'page':
                            $obj = new Page($row['id']);
                            break;
                        case 'article':
                            $obj = new Article($row['id']);
                            break;
                        case 'comment':
                            $obj = new Comment($row['id']);
                            break;
                        default:
                            break;
                    }
                }catch(Exception $e) { }
                $resultset[$c] = $obj;
                $c++;
            }
            return $resultset;
        }else if($query['action'] == 'count')
            return mysql_num_rows($result);
    }
}

?>
