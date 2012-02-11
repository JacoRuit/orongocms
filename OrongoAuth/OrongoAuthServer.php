<?php

/**
 * OrongoAuthServer Class
 *
 * @author Jaco Ruit
 */
class OrongoAuthServer {
    
    public static $responseCodes = array(
      "ERROR" => 500,
      "KEY_ERROR" => 400,
      "KEY_EXPIRED" => 300,
      "CONNECTION_CLOSED" => 0
    );
    
    /**
     * Init the server
     * @param OrongoAuthKey $paramKey  
     */
    public function __construct($paramKey){
        
    }
    
    /**
     * Throw server error
     * @param Exception $paramException 
     */
    public function throwError($paramException){
        if(($paramException instanceof Exception) == false) die("500");
        $error = array();
        $error['error_message'] = $paramException->getMessage();
        $error['error_code'] = $paramException->getCode();
        $this->doResponse(self::$responseCodes['ERROR'], $error);
        $this->dispose();
    }
    
    /**
     * Show response & close server
     * @param int $paramResponseCode a response code
     * @param array $paramResponse Response
     */
    public function doResponse($paramResponseCode, $paramResponse){
        if(!in_array($paramResponseCode, self::$responseCodes)) die("500");
        $paramResponse['response_code'] = $paramResponseCode;
        $response = @json_encode($paramResponse);
        if(!$response) die ("500");
        header('Content-Type: application/json');
        echo $response;
        exit;
    }
    
    /**
     * Close connection
     */
    public function close(){
        $this->doResponse(self::$responseCodes['CONNECTION_CLOSED'], array());
        $this->dispose();
    }
    
    /**
     * Shut down server, handle everything
     */
    public function dispose(){
        
    }
    
    public function __destruct(){
        $this->dispose();
    }
}

?>
