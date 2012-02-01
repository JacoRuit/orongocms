<?php
/**
 * Configuration Array
 * @var array
 * @author Jaco Ruit
 */

global $_CONFIG;

$_CONFIG = array();


#   Database
/**
 * Configuration Array -> Database
 * @var array
 * @author Jaco Ruit
 */
$_CONFIG['db']= array();

/**
 * Database Server
 */
$_CONFIG['db']['server'] = 'localhost';

/**
 * Database Username
 */
$_CONFIG['db']['username'] = 'root';

/**
 * Database Password
 */
$_CONFIG['db']['password'] = 'password';

/**
 * Database Name
 */
$_CONFIG['db']['name'] = 'orongodb';


#   Security
/**
 * Configuration Array -> Security
 * @var array
 * @author Jaco Ruit
 */
$_CONFIG['security'] = array();

/**
 * Salt 1
 */
$_CONFIG['security']['salt_1'] = 'abcdefghijklmnop';

/**
 * Salt 2
 */
$_CONFIG['security']['salt_2'] = 'qrstuvwxyz';

/**
 * Salt 3
 */
$_CONFIG['security']['salt_3'] = '1234abcd5678efgh';
?>
