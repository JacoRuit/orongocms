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
        echo orongo_query("action=fetch&object=comment&max=10000&where=article.id:1");
        if(!isset($_GET['token']))
        echo '<a href="'. IssueTracker::getAuthSubRequestURL(Settings::getWebsiteURL() . 'tests.php') . '">open</a>';
        if(isset($_GET['token'])){
            $it = new IssueTracker($_GET['token']);
            $i = new Issue("Test API Call");
            $i->setAuthor("Jaco Ruit");
            $i->setContent("This is an test. Does this work? :)");
            $i->setLabels(array('testlabel', 'label2'));
            $r = $it->postIssue($i);
            if($r) echo 'SUCCESSFUL!';
        }
        ?>
    </body>
</html>
