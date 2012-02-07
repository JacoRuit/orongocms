<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_view');

Security::promptAuth();

if(getUser()->getRank() < RANK_WRITER){ header("Location: index.php"); exit; }

$query = explode(".", $_SERVER['QUERY_STRING']);
if(count($query) != 2){
    header("Location: " . orongoURL("orongo-admin/index.php"));
    exit;
}

$object = $query[0];
$id = $query[1];

$view = new AdminFrontend();

switch($object){
    case "page":
        break;
    case "user":
        break;
    case "article":
        break;
    default:
        header("Location: " . orongoURL("orongo-admin/index.php"));
        exit;
        break;
}


?>