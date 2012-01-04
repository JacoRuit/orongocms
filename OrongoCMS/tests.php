<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require 'globals.php';
        try{
            OrongoQueryHandler::exec(new OrongoQuery('action=fetch&object=page&max=10&order=title,asc'));
        }catch(Exception $e){
            echo get_class($e) . " (" . $e->getMessage() . ")";
        }
        ?>
    </body>
</html>
