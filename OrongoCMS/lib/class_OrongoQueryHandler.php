<?php
/**
 * OrongoQueryHandler Class
 *
 * @author Jaco Ruit
 */

class OrongoQueryHandler {
    
    private static $object = array ('user_activated', 'user', 'user_not_activated', 'page','article');
    private static $by array ('article' => 'by_author' )
    
    private static $order = array ('user' => array('id','name'), 'article' => array('id','title','date'), 'page' => array('id','title'));
    private static $orderc = array ('asc', 'desc');
    
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
        if(!isset($query['max']) || !is_numeric($query['max'])) throw new QueryException("Invalid query string: please set max or change it to an int.");
        if(!isset($query['by']) || !in_array($query['by'], self::$by[$query['object']])) throw new QueryException("Invalid query string: invalid by.");
                
        #   Optional
        $order = "rand";
        $oderc = "rand";
        
        #       order
            #       FORMAT: order = {order}  _OR_   order = {order},{orderc}
        if(isset($query['order'])){ 
            if(strstr($query['order'], ",")){
                $orders = explode(",", $query['order']);
                if(count($orders) > 2 || count($orders) < 1) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[0], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                if(!in_array($orders[1], self::$orderc)) throw new QueryException ("Invalid query string: invalid orderc.");
                $order = $orders[0];
                $orderc = $orders[1];
            }else{
                if(!in_array($query['order'], self::$order[$query['object']])) throw new QueryException ("Invalid query string: invalid order.");
                $order = $query['order'];
            }
              
        }
    }
}

?>
