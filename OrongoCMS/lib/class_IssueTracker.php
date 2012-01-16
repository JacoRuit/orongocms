<?php

/**
 * IssueTracker Class
 *
 * Google Code Issue Tracker API: https://code.google.com/p/support/wiki/IssueTrackerAPI
 * 
 * @author Jaco Ruit
 */
class IssueTracker {
    
    private $token;
    
    /**
     * Constructs a new Issue Tracker object
     * @param String $paramToken Auth Sub Token
     */
    public function __construct($paramToken){
        $this->token = $paramToken;
        
        
    }
    
    /**
     * Posts the Issue
     * @param Issue $paramIssue Issue object
     * @return boolean indicating if issue was created succesfully
     */
    public function postIssue($paramIssue){
        $req = curl_init("https://code.google.com/feeds/issues/p/orongocms/issues/full");
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_HEADER, true);
        
        //cURL, stop outputting the data! 
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        //Ignore: SSL certificate problem, verify that the CA cert is OK.  error
        curl_setopt ($req, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($req, CURLOPT_SSL_VERIFYPEER, false); 
        
        $headers = array("Content-Type: application/atom+xml", "Authorization: AuthSub token=\"" . $this->token . "\"", "Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2", "Connection: keep-alive");
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        
        $xml = $paramIssue->toXML();
        curl_setopt($req, CURLOPT_POSTFIELDS , $xml);
        
     
        curl_exec($req);
        $info = curl_getinfo($req);
        curl_close($req);
        
        if($info["http_code"] == '201') return true;
        else{
            switch($info["http_code"]){
                case '400':
                    throw new Exception("Invalid request URI or header, or unsupported nonstandard parameter.", 400);
                    break;
                case '401':
                    throw new Exception("Authorization required.", 401);
                    break;
                case '403':
                    throw new Exception("Unsupported standard parameter, or authentication or authorization failed.", 403);
                    break;
                case '404': 
                    throw new Exception("Resource (such as a feed or entry) not found.", 404);
                    break;
                case '409':
                    throw new Exception("Specified version number doesn't match resource's latest version number.", 409);
                    break;
                case '500':
                    throw new Exception("Internal error. This is the default code that is used for all unrecognized server errors.", 500);
                    break;
                default:
                    throw new Exception($info["http_code"] . ": Don't know why this happened - please update the bug manually (including this error)", $info["http_code"]);
                    break;
            }
        }
    }  
    
    /**
     * @param String $paramNext next
     * @return String AuthSubRequest URL
     */
    public static function getAuthSubRequestURL($paramNext){
        $url = "https://www.google.com/accounts/AuthSubRequest?";
        $url .= "scope=https%3A%2F%2Fcode.google.com%2Ffeeds%2Fissues%2F&amp;";
        $url .= "session=1&amp;";
        $url .= "secure=0&amp;";
        $url .= "next=" . urlencode($paramNext) . "&amp;";
        return $url;
    }
}

?>
