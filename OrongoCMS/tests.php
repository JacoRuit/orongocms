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
        class Test{
            public static function testStatic(){
                Security::promptAuth();
            }
            public function test(){
                Security::promptAuth();
            }
        }
        echo '<br/><p>Static</p><br/>';
        Test::testStatic();
        
        echo '<br /><br /><p>Inside class</p><br />';
        $t = new Test(); $t->test();
        
        echo '<br /><br /><p>No Scope</p><br />';
        Security::promptAuth();
        ?>
    </body>
</html>
