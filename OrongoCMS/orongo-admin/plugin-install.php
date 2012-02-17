<?php

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_plugin-install');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(!isset($_GET['xml_path'])){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$xmlPath = ADMIN  . '/plugins' . urldecode($_GET['xml_path']);

$install = new AdminFrontend();
$install->main(array("time" => time(), "page_title" => "Install", "page_template" => "dashboard"));

if(!file_exists($xmlPath)){
  $install->addMessage(l("Plugin not found"), "error");
  $install->render();
  exit;
}

foreach(getPlugins() as $plugin){
    if(($plugin instanceof OrongoPluggableObject) == false) continue;
    if($plugin->getInfoPath() == $xmlPath){
        $install->addMessage(l("Plugin already installed"), "warning");
        $install->render();
        exit;
    }
}

try{
    Plugin::install($xmlPath);
}catch(Exception $e){
    try{ Plugin::deinstall($xmlPath); }catch(Exception $ex){}
    $install->addMessage($e->getMessage(), "error");
    $install->render();
    exit;
}

header("Location: " . orongoURL("orongo-admin/manage.php?obj=plugins&msg=3"));
exit;

?>