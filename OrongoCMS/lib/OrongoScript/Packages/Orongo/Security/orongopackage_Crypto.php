<?php
/**
 * Crypto OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptCrypto extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncMD5(), new FuncSHA1());
    }
}



/**
 * MD5 OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncMD5 extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Crypto.MD5()");     
        return new OrongoVariable(md5($args[0]));
    }

    public function getShortname() {
        return "MD5";
    }
    
    public function getSpace(){
        return "Crypto";
    }
}


/**
 * SHA1 OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSHA1 extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Crypto.SHA1()");     
        return new OrongoVariable(sha1($args[0]));
    }

    public function getShortname() {
        return "SHA1";
    }
    
    public function getSpace(){
        return "Crypto";
    }
}


?>
