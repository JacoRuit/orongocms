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
        ?>
        <script type="text/javascript" src="<?php echo Settings::getWebsiteURL(); ?>js/ajax.postComment.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
               //postComment("<?php echo Settings::getWebsiteURL(); ?>ajax/postComment.php",1,"test xd"); 
            });
        </script>
        <form method='post' action="<?php echo Settings::getWebsiteURL(); ?>ajax/postComment.php">
            <input type="hidden" name="content" value="balabala" />
            <input  type="hidden" name="article" value="1" />
            <input type="hidden" name="offset" value="19" />
            <input type="submit"/>
        </form>
        <?php

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
