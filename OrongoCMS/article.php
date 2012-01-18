<?php
/**
 * @author Jaco Ruit
 */

//Debug line
//TODO remove on release
$time_start = microtime(true);

require 'globals.php';

setCurrentPage('article');

$article = null;

if(!isset($_GET['id'])){
    header("Location: 404.php");
    exit;
}else{
    try{
        $article = new Article(mysql_escape_string($_GET['id']));
    }catch(Exception $e){
        if($e->getCode() == ARTICLE_NOT_EXIST){
            header("Location: 404.php");
        }else{
            header("Location: 503.php");
        }
    }
}

$articleFO = new ArticleFrontend();
$articleFO->main(array("time" => time(), "article" => &$article));
$articleFO->render();

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
