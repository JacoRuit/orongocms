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
            $result = OrongoQueryHandler::exec(new OrongoQuery('action=fetch&object=article&max=1&order=title,asc&where=author.name:jaco'));
            var_dump($result);
        }catch(Exception $e){
            echo get_class($e) . " (" . $e->getMessage() . ")";
        }
        ?>
    </body>
</html>
