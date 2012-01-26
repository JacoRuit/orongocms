<?php

/**
 * FuncMySQLQuery Class
 *
 * @author Jaco Ruit
 */
class FuncMySQLQuery extends OrongoFunction {

    public function __invoke($args) {
        echo $args[0];
        echo "<br />";
        return new OrongoVariable($args[0]);
    }
    public function getShortname() {
        return "echo";
    }
}

?>
