<?php
/**
 * @author Jaco Ruit
 */
define('HACK_PLUGINS', true);
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_plugin-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(!isset($_GET['xml_path'])){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}

$xmlPath = ADMIN  . '/plugins' . urldecode($_GET['xml_path']);

if(!file_exists($xmlPath)){
  header("Location: " . orongoURL("orongo-admin/manage.php?plugins"));
  exit;
}

$installed = false;
foreach(getPlugins() as $plugin){
    if(($plugin instanceof OrongoPluggableObject) == false) continue;
    if($plugin->getInfoPath() == $xmlPath) $installed = true;
}

if(!$installed){ header("Location: " . orongoURL("orongo-admin/manage.php?plugins")); exit; }

$xml = @simplexml_load_file($xmlPath);
$json = @json_encode($xml);
$info = @json_decode($json, true);
if(empty($info['plugin']['settings']) || !is_array($info['plugin']['settings'])){
    header("Location: " . orongoURL("orongo-admin/manage.php?plugins"));
    exit;
}
$accessKey = $info['plugin']['access_key'];
$authKey = null;
foreach(Plugin::getAuthKeys() as $pAuthKey=>$pAccessKey){
    if($pAccessKey == $accessKey){
        $authKey = $pAuthKey;
    }
}
if($authKey == null){
    header("Location: " . orongoURL("orongo-admin/manage.php?plugins"));
    exit;
}

$pSettings = Plugin::getSettings($authKey);

foreach($pSettings as $settingName => $value){
    if(isset($_POST[$settingName]) && strval($value) != $_POST[$settingName]){
        Plugin::setSetting($authKey, $settingName, $_POST[$settingName]);
    }
}

header("Location: " . orongoURL("orongo-admin/manage.php?msg=5&obj=plugins"));
exit;
?>
