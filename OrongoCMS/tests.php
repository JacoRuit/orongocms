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
            $result = OrongoQueryHandler::exec(new OrongoQuery('action=fetch&object=article&max=1000&offset=0&order=article.id,desc&where=article.title:test article 0'));
            echo '<br />';
            foreach($result as $article){
                echo 'id: ' . $article->getID() . ' title: ' . $article->getTitle() . ' date: ' . $article->getDate() . ' author id: ' . $article->getAuthorID();
                echo '<br />';
            }
        }catch(Exception $e){
            echo get_class($e) . " (" . $e->getMessage() . ")";
        }
        ?>
    </body>
</html>
