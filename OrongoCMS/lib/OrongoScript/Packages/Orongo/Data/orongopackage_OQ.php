<?php
/**
 * OQ OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptOQ extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncOQQuery());
    }
}



/**
 * Query OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncOQQuery extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for OQ.Query()");  
        $query = new OrongoQuery($args[0]);
        $arr = $query->getQueryArray();
        $objs = array();
        if(isset($arr['action']) && $arr['action'] == 'count')
            return new OrongoVariable(OrongoQueryHandler::exec($query));
        else{
            $objs = OrongoQueryHandler::exec($query);
            foreach($objs as &$obj){ $obj = $obj->getID(); }
        }
        return new OrongoList($objs);
    }

    public function getShortname() {
        return "Query";
    }
    
    public function getSpace(){
        return "OQ";
    }

}

?>
