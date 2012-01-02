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
        require 'TestStorable.php';
        $testStorable = new TestStorable(array("privateString" => 'Hey, this is a string var!'));
        Storage::store('storable', $testStorable, true);
        
        var_dump(Storage::get('storable'));
        ?>
    </body>
</html>
