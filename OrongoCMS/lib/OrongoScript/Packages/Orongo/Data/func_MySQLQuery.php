<?php

/**
 * FuncMySQLQuery Class
 *
 * @author Jaco Ruit
 */
class FuncMySQLQuery extends OrongoFunction {

    public function __invoke($args) {
        return new OrongoVariable($args[0] . $args[1]);
    }
    public function getShortname() {
        return "echo";
    }
}

?>
