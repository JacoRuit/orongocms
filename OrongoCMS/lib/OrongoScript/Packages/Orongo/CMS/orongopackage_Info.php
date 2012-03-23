<?php
/**
 * CMSInfo OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptInfo extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncInfoGetCMSVersion(), new FuncInfoGetOSCVersion());
    }
}



/**
 * GetCMSVersion OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncInfoGetCMSVersion extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoVariable(REVISION);
    }

    public function getShortname() {
        return "GetCMSVersion";
    }
    
    public function getSpace(){
        return "Info";
    }
}

/**
 * GetOSCVersion OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncInfoGetOSCVersion extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoVariable(OSC_VERSION);
    }

    public function getShortname() {
        return "GetOSCVersion";
    }
    
    public function getSpace(){
        return "Info";
    }
}


?>
