<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

        <title></title>
    </head>
    <body>
        <?php
        require 'globals.php';
        $script = file_get_contents("lib/OrongoScript/Tests/test.osc");
        $parser = new OrongoScriptParser($script);
        $parser->startParser();
        ?>
    </body>
</html>
