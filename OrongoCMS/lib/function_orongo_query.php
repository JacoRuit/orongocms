<?php

/**
 * orongo_query function
 *
 * @author Jaco Ruit
 */

/**
* Executes a query string [equivalent to OrongoQueryHandler::exec(new OrongoQuery(QUERY)); ]
* @param String $paramQuery query string
* @return array resultset
*/
function orngo_query($paramQuery){
    return OrongoQueryHandler::exec(new OrongoQuery($paramQuery));
}
?>
