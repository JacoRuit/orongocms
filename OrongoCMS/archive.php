<?php
/**
 * @author Jaco Ruit
 */
require 'startOrongo.php';
startOrongo('archive');


$user = getUser();
$date = false;
$username = false;
$userid = false;
if(isset($_GET['date'])){
    if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_GET['date']))           
            $date = $_GET['date'];
    else{
        getDisplay()->addObject(new MessageBox("Invalid date."));      
    }
}
else if(isset($_GET['user']))
    $username = mysql_escape_string($_GET['user']);
else if(isset($_GET['userid']))
    $userid = mysql_escape_string($_GET['userid']);


$articles = array();
$c = 0;
$q = "action=fetch&object=article&max=1000000&order=article.id,desc";
if($date != false) $q .= "&where=article.date:" . $date;
if($username != false && is_string($username)) $q .= "&where=author.name:" . $username;
if($userid != false && is_numeric($userid)) $q .= "&where=author.id:" . $userid;
try{
    $articles = orongo_query($q);
}catch(Exception $e){
    $msgbox = new MessageBox(l("FETCH_ERROR", strtolower(l("ARTICLES"))));
    $msgbox->bindException($e);
    getDisplay()->addObject($msgbox);
    $articles = null;
}
$archive = new ArchiveFrontend();
$archive->main(array("time" => time(), "articles" => $articles));
$archive->render();

?>
