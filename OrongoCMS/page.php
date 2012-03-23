<?php
/**
 * @author Jaco Ruit
 */
require 'startOrongo.php';
startOrongo('page');

$page = null;

if(!isset($_GET['id'])){
    header('Location: ' . orongoURL("error.php?error_code=404"));
    exit;
}else{
    try{
        $page = new Page(mysql_escape_string($_GET['id']));
    }catch(Exception $e){
        if($e->getCode() == PAGE_NOT_EXIST){
            header('Location: ' . orongoURL("error.php?error_code=404"));
            exit;
        }else{
            header('Location: ' . orongoURL("error.php?error_code=500"));
            exit;
        }
    }
}

$pageFO = new PageFrontend();
$pageFO->main(array("time" => time(), "page" => &$page));
$pageFO->render();


?>