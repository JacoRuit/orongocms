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
            $resultset = OrongoQueryHandler::exec(new OrongoQuery('action=fetch&object=user&max=10000&order=user.activated'));
            echo '<br />';
            foreach($resultset as $user){
                if(($user instanceof User)==false) continue;
                echo 'id: ' . $user->getID() . ' rank: ' . $user->getRank() . ' activated: ' . $user->isActivated() . ' name: ' . $user->getName() . ' email: ' . $user->getEmail();
                echo '<br />';
            }
        }catch(Exception $e){
            echo get_class($e) . " (" . $e->getMessage() . ")";
        }
        ?>
    </body>
</html>
