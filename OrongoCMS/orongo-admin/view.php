<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_view');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$query = explode(".", $_SERVER['QUERY_STRING']);
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$object = $query[0];
$id = $query[1];

$view = new AdminFrontend();

switch($object){
    case "page":
        $view->main(array("time" => time(), "page_template" => "dashboard", "page_title" => "Viewing User"));
        break;
    case "user":
        break;
    case "article":
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
        exit;
        break;
}


?>