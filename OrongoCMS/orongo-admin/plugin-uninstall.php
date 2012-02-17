<?php

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_plugin-uninstall');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(!isset($_GET['xml_path'])){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$xmlPath = ADMIN  . '/plugins' . urldecode($_GET['xml_path']);

$install = new AdminFrontend();
$install->main(array("time" => time(), "page_title" => "Uninstall", "page_template" => "dashboard"));

if(!file_exists($xmlPath)){
  $install->addMessage(l("Plugin not found"), "error");
  $install->render();
  exit;
}

$installed = false;
foreach(getPlugins() as $plugin){
    if(($plugin instanceof OrongoPluggableObject) == false) continue;
    if($plugin->getInfoPath() == $xmlPath){
        $installed = true;
    }
}

if(!$installed){
    $install->addMessage(l("Plugin not installed"), "warning");
    $install->render();
    exit;
}

try{
    Plugin::deinstall($xmlPath);
}catch(Exception $e){
    $install->addMessage($e->getMessage(), "error");
    $install->render();
    exit;
}

header("Location: " . orongoURL("orongo-admin/manage.php?obj=plugins&msg=4"));
exit;

?>