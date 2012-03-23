<?php
/**
 * @author Jaco Ruit
 */
define('HACK_PLUGINS', true);
require '../startOrongo.php';
startOrongo('admin_plugin-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(!isset($_GET['xml_path'])){
    header("Location: " . orongoURL("orongo-admin/index.php?msg=1"));
    exit;
}


$xmlPath = ADMIN  . '/plugins' . urldecode($_GET['xml_path']);

$settings = new AdminFrontend();
$settings->main(array("time" => time(), "page_title" => "Plugin Setting", "page_template" => "dashboard"));

if(!file_exists($xmlPath)){
  $settings->addMessage(l("Plugin not found"), "error");
  $settings->render();
  exit;
}

$installed = false;
foreach(getPlugins() as $plugin){
    if(($plugin instanceof OrongoPluggableObject) == false) continue;
    if($plugin->getInfoPath() == $xmlPath) $installed = true;    
}

if(!$installed){
    $settings->addMessage(l("Plugin not installed"), "warning");
    $settings->render();
    exit;
}
$xml = @simplexml_load_file($xmlPath);
$json = @json_encode($xml);
$info = @json_decode($json, true);
$pluginName = $info['plugin']['name'];
if(empty($info['plugin']['settings']) || !is_array($info['plugin']['settings'])){
  $settings->addMessage(l("No settings found"), "warning");
  $settings->render();
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
  $settings->addMessage(l("Plugin not found"), "error");
  $settings->render();
  exit;
}
$settings->setTitle(l("Plugin Settings") . " (" . $pluginName . ") ", false);

//That's how you hack an auth key :P
$pSettings = Plugin::getSettings($authKey);


$settingForm = new AdminFrontendForm(100, l("Plugin Settings") . " (" . $pluginName . ") ", "POST",orongoURL("actions/action_SavePluginSettings.php?xml_path=" . $_GET['xml_path']), false);

foreach($pSettings as $settingName => $value){
    if(!isset($info['plugin']['settings'][$settingName])) continue;
    $setting = $info['plugin']['settings'][$settingName];
    if($setting['type'] == 'boolean'){
        $selected = $value ? l("Yes") : l("No");
        $settingForm->addRadios($setting['description'], $settingName, array(
            l("Yes") => "true",
            l("No") => "false"
        ), $selected, false);
    }else
        $settingForm->addInput($setting['description'], $settingName, "text", $value, false, false, false);
}

$settingForm->addButton("Save", true);
$settings->addObject($settingForm);
$settings->render();    

?>
