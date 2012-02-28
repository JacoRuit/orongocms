<?php
/**
 * @author Jaco Ruit
 */
require 'startOrongo.php';
startOrongo();

setCurrentPage('page');

$page = null;

if(!isset($_GET['id'])){
    header("Location: 404.php");
    exit;
}else{
    try{
        $page = new Page(mysql_escape_string($_GET['id']));
    }catch(Exception $e){
        if($e->getCode() == PAGE_NOT_EXIST){
            header("Location: 404.php");
        }else{
            header("Location: 503.php");
        }
    }
}

$pageFO = new PageFrontend();
$pageFO->main(array("time" => time(), "page" => &$page));
$pageFO->render();


?>