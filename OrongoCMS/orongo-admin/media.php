<?php

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_create');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: index.php"); exit; }

if(isset($_SERVER['QUERY_STRING'])) 
    $type = $_SERVER['QUERY_STRING'];
else $type = null;

$types = array(
    "files",
    "images"
);


if($type != null){
    if(!in_array($type, $types)) $type = null;
    $type = strtoupper(substr($type,0,1)) . substr($type, 1);
    $pageTitle = "Gallery";
}

if($type == null){
    $pageTitle = "Media";
}


$media = new AdminFrontend();
$media->main(array("time" => time(), "page_title" => $pageTitle, "page_template" => "dashboard"));
$ckfinder = new CKFinder(orongoURL("lib/ckfinder/"));
if($type != null)
    $ckfinder->ResourceType = $type;
$media->addObject(new AdminFrontendObject(100, l("Media") . " " .  l("Manager") . " - " . l("Powered by") . " CKFinder", $ckfinder->CreateHTML()));
$media->render();
?>
