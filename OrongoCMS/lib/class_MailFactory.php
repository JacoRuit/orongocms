<?php
/**
 * MailFactory Class
 *
 * @author Jaco Ruit
 */
class MailFactory {
   
    /**
     * Generates an activation email
     * @param String $paramUserName Name of the user 
     * @param String $paramActivationURL Activation URL 
     * @return array Generated Email
     */
    public static function generateActivationEmail( $paramUserName, $paramActivationURL){
        $websiteName = Settings::getWebsiteName();
    
        $msgArray = array();
        $msgArray['subject'] = $websiteName . ' - Activate your account';
        $msgArray['message'] = 
$paramUserName . ',
        
Thank you for registering at ' . $websiteName .'
     
In order to activate your new account please visit this URL: 
' . $paramActivationURL . '


';  
        $msgArray['headers'] = 'From: ' . $websiteName . ' <' . Settings::getEmail() . '>';
        return $msgArray;
    }
}

?>
