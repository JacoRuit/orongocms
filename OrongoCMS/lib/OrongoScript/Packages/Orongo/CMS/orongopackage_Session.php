<?php
/**
 * Session OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptSession extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(
            new FuncSessionGet(),
            new FuncSessionSet(),
            new FuncSessionGetData(),
            new FuncSessionIsLoggedIn(),
            new FuncSessionGetLoggedInUserID(),
            new FuncSessionGetOrongoSessionID(),
            new FuncSessionGetCurrentPage()
        );
    }
}



/**
 * IsLoggedIn OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionIsLoggedIn extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoVariable(getUser() != null);
    }

    public function getShortname() {
        return "IsLoggedIn";
    }
    
    public function getSpace(){
        return "Session";
    }
}

/**
 * GetLoggedInUserID OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionGetLoggedInUserID extends OrongoFunction {
    

    public function __invoke($args) {
        if(getUser() == null)
            throw new Exception("No user logged in!");
        return new OrongoVariable(getUser()->getID());
    }

    public function getShortname() {
        return "GetLoggedInUserID";
    }
    
    public function getSpace(){
        return "Session";
    }
}

/**
 * GetData OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionGetData extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoList($_SESSION);
    }

    public function getShortname() {
        return "GetData";
    }
    
    public function getSpace(){
        return "Session";
    }
}

/**
 * Get OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionGet extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Session.Get()");
        if(!isset($_SESSION[$args[0]]))
            throw new Exception($args[0] ." not set!");
        return new OrongoVariable($_SESSION[$args[0]]);
    }

    public function getShortname() {
        return "Get";
    }
    
    public function getSpace(){
        return "Session";
    }
}
/**
 * Set OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionSet extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Session.Set()");
        $_SESSION[$args[0]] = $args[1];
    }

    public function getShortname() {
        return "Set";
    }
    
    public function getSpace(){
        return "Session";
    }
}

/**
 * GetOrongoSessionID OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionGetOrongoSessionID extends OrongoFunction {
    

    public function __invoke($args) {
        if(!isset($_SESSION['orongo-session-id']))
            throw new Exception("orongo-session-id not set!");
        return new OrongoVariable($_SESSION['orongo-session-id']);
    }

    public function getShortname() {
        return "GetOrongoSessionID";
    }
    
    public function getSpace(){
        return "Session";
    }
}

/**
 * GetCurrentPage OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSessionGetCurrentPage extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoVariable(getCurrentPage());
    }

    public function getShortname() {
        return "GetCurrentPage";
    }
    
    public function getSpace(){
        return "Session";
    }
}

?>
