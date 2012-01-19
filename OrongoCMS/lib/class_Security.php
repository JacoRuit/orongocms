<?php

/**
 * Security Class
 *
 * @author Jaco Ruit
 */
class Security {
    
    /**
     * SHA1 Hash with Salts
     * @param String $paramString String to Hash
     * @return String Hashed String
     */
    public static function hash($paramString){
        if(CRYPT_SHA512 != 1){
            throw new Exception('SHA-512 Hashing not supported!');
        }else{
            return crypt($_CONFIG['security']['salt_2']. $paramString . $_CONFIG['security']['salt_3'],'$6$rounds=5000$' . $_CONFIG['security']['salt_1'] . '$');
        }
    }
    
    /**
     * Filter the string passed in the argument (For SQL Queries)
     * @param String $paramString String to escape
     * @return String Escaped String 
     */
    public static function escapeSQL($paramString){
        return urlencode(str_replace("*", "",str_replace("`" , "",mysql_escape_string(stripslashes(str_replace("#","",str_replace("UNION", "", $paramString)))))));
    }
    
    /**
     * Escapes the string passed in the argument
     * @param String $paramString String to escape
     * @return String Escaped String
     */
    public static function escape($paramString){
        return htmlspecialchars($paramString);
    }
    
    /**
     * Redirects user to login and if he logged he will be returned to where this was calles
     * @param String $paramTo To what should he be redirected? (OPTIONAL)
     */
    public static function promptAuth($paramTo = null){
        if(getUser() != null) return;
        if($paramTo != null){
            header("Location: " . orongoURL('orongo-login.php?redirect=' . $paramTo));
            exit;
        }
        if(!function_exists('getCurrentPage')){
            header("Location: " . orongoURL('orongo-login.php'));
            exit;
        }
        $currentPage = str_replace("admin_", "orongo-admin/", getCurrentPage()) . '.php';
        header("Location: " . orongoURL('orongo-login.php?redirect=' . $currentPage));
        exit;
    }
 
}

?>
