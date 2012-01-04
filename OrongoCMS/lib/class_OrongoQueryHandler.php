<?php
/**
 * OrongoQueryHandler Class
 *
 * @author Jaco Ruit
 */

class OrongoQueryHandler {
    
    private static $object = array ('user_activated', 'user', 'user_not_activated', 'page','article');   
    
    private static $order = array ('user' => array('id','name'), 'article' => array('id','title','date'), 'page' => array('id','title'));
    private static $orderc = array ('asc', 'desc');
        
    private static $where0 = array ('article' => array('author','article'), 'user' => array('user'), 'page' => array('page'));
    private static $where1 = array ('author' => array('id','name'), 'user' => array('id','name'), 'article' => array('id','title'), 'page' => array('id','title'));

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
        if($query['action'] != 'fetch') throw new QueryException("Invalid query string: please set action to fetch.");
        if(!in_array($query['object'], self::$object)) throw new QueryException("Invalid query string: invalid object.");  
        $from = $query['object'];
        if(!isset($query['max']) || !is_numeric($query['max'])) throw new QueryException("Invalid query string: please set max or change it to an int.");
        $limit = " LIMIT " . $query['max'];
                
        #   Optional
                
        #       order
        $order = "";
        $oderc = "";
        
            #       FORMAT: order = {order}  _OR_   order = {order},{orderc}
        if(isset($query['order'])){ 
            if(strstr($query['order'], ",")){
                $orders = explode(",", $query['order']);
                if(count($orders) > 2 || count($orders) < 1) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[0], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[1], self::$orderc)) throw new QueryException ("Invalid query string: invalid orderc.");
                $order = "ORDER BY `" . $orders[0]."`";
                $orderc = $orders[1];
            }else{
                if(!in_array($query['order'], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                $order = "ORDER BY `" . $query['order'] . "`";
            }   
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
            if(!is_string($where1[1]) && ($where1[0] == 'title' || $where1[0] == 'name')) throw new QueryException ("Invalid query string: invalid where.");
            if(!is_numeric($where1[1]) && $where1[0] == 'id')throw new QueryException ("Invalid query string: invalid where.");
            if($where[0] == 'author'){
                switch($where1[0]){
                    case 'id':
                        $where = " WHERE `authorID` = '" . $where1[1] . "' ";
                        break;
                    case 'name':
                        $uid = User::getUserID($where1[1]);
                        if($uid == "") throw new Exception("User doesn't exist!", USER_NOT_EXIST);
                        $where = " WHERE `authorID` = '" . User::getUserID($where1[1]) . "' ";
                        break;
                    default:
                        break;
                        
                }
            }
            if($where[0] == 'user'){
                switch($where1[0]){
                    case 'id':
                        $where = " WHERE `id` = '" . $where1[1] . "' ";
                        break;
                    case 'name':
                        $uid = User::getUserID($where1[1]);
                        if($uid == "") throw new Exception("User doesn't exist!", USER_NOT_EXIST);
                        $where = " WHERE `id` = '" . User::getUserID($where1[1]) . "' ";
                        break;
                    default:
                        break;
                        
                }
            }
            //TODO article,page
           // if($where[])
            
        }
        
        $resultset = array();
        $q  =  "SELECT `id` FROM " . $from . 's' . $where  . $limit;
        $result = @mysql_query($q);
        $c = 0;
        while($row = mysql_fetch_assoc($result)){
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
                    default:
                        break;
                }
            }catch(Exception $e) { }
            $resultset[$c] = $obj;
            $c++;
        }
        return $resultset;
    }
}

?>
