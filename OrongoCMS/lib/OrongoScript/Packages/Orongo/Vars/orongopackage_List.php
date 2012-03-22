<?php
/**
 * List OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptList extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(
            new FuncListCreate(),
            new FuncListClear(),
            new FuncListDelete(),
            new FuncListGet(),
            new FuncListSet(),
            new FuncListIsSet()
       );
    }
}



/**
 * Create OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncListCreate extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoList();
    }

    public function getShortname() {
        return "Create";
    }
    
    public function getSpace(){
        return "List";
    }
}


/**
 * Clear OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncListClear extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for List.Clear()");
        if(($args[0] instanceof OrongoList) == false)
            throw new Exception("Invalid argument, OrongoList expected!");
        $args[0]->clear();
    }

    public function getShortname() {
        return "Clear";
    }
    
    public function getSpace(){
        return "List";
    }
}

/**
 * Get OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncListGet extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for List.Get()");
        if(($args[0] instanceof OrongoList) == false)
            throw new Exception("Invalid argument, OrongoList expected!");
        return $args[0]->getVar($args[1]);
    }

    public function getShortname() {
        return "Get";
    }
    
    public function getSpace(){
        return "List";
    }
}

/**
 * Set OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncListSet extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 3) throw new OrongoScriptParseException("Arguments missing for List.Set()");
        if(($args[0] instanceof OrongoList) == false)
            throw new Exception("Invalid argument, OrongoList expected!");
        $args[0]->setVar($args[1], $args[2]);
    }

    public function getShortname() {
        return "Set";
    }
    
    public function getSpace(){
        return "List";
    }
}

/**
 * Delete OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncListDelete extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for List.Delete()");
        if(($args[0] instanceof OrongoList) == false)
            throw new Exception("Invalid argument, OrongoList expected!");
        $args[0]->delete($args[1]);
    }

    public function getShortname() {
        return "Delete";
    }
    
    public function getSpace(){
        return "List";
    }
}

/**
 * IsSet OrongoScript function
 * 
 * @author Jaco Ruit 
 */
class FuncListIsSet extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for List.IsSet()");
        if(($args[0] instanceof OrongoList) == false)
            throw new Exception("Invalid argument, OrongoList expected!");
        return new OrongoVariable($args[0]->varIsSet($args[1]));
    }

    public function getShortname() {
        return "IsSet";
    }
    
    public function getSpace(){
        return "List";
    }
}


?>
