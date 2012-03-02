<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_orongo-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$settings = new AdminFrontend();

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case 0:
            $settings->addMessage(l("Settings saved"), "success");
        default:
            break;
    }
}

$settings->main(array('time' => time(), 'page_title' => 'Orongo Settings', 'page_template' => 'dashboard'));


$settingForm = new AdminFrontendForm(100, "Orongo Settings", "POST", orongoURL("actions/action_SaveOrongoSettings.php"));

$settingForm->addInput("Website Name", "website_name", "text", Settings::getWebsiteName());
$settingForm->addInput("Website URL", "website_url", "text", Settings::getWebsiteURL());
$settingForm->addInput("Admin Email", "admin_email", "text", Settings::getEmail());
$currentShowArchiveString = Settings::showArchive() ? l("Yes") : l("No");
$settingForm->addRadios("Show archive", "show_archive", array( 
    l("Yes") => "true",
    l("No") => "false"
), $currentShowArchiveString);
$languages = array(Settings::getLanguageName() => "nl_NL");

$files = @scandir(ADMIN . '/lang/');
if(is_array($files)){
    foreach($files as $file){
        if($file == Settings::getLanguageName() || stristr($file, ".")) continue;
        $languages[$file] = $file;
    }
}
$styles = array(getStyle()->getStyleName() => getStyle()->getStyleFolder());
$files = @scandir(ROOT . '/themes');
if(is_array($files)){
    foreach($files as $file){
        if(is_dir(ROOT .'/themes/' . $file) && getStyle()->getStyleFolder() != ROOT .'/themes/' . $file . "/"){
            $xmlFile = ROOT . '/themes/' . $file . '/info.xml';
            if(!file_exists($xmlFile)) continue;
            $xml = @simplexml_load_file($xmlFile);
            $json = @json_encode($xml);
            $info = @json_decode($json, true);
            $styles[$info['style']['name']] = $file; 
        }
    }
}

$settingForm->addSelect("website_style", $styles);
$settingForm->addSelect("website_lang", $languages);
$settingForm->addButton("Save", true);        
$settings->addObject($settingForm);

$xml = @simplexml_load_file(getStyle()->getStylePath() . "info.xml");
$json = @json_encode($xml);
$info = @json_decode($json, true);
if(is_array($info['style']['settings']) && getStyle()->isUsingPHP()){
    $styleForm = new AdminFrontendForm(100, "Style Settings", "POST", orongoURL("actions/action_SaveStyleSettings.php"));
    $styleSettings = getDatabase()->query("SELECT `setting`, `setting_value` FROM `style_data` WHERE `style_main_class` = %s", $info['style']['main_class']);
    foreach($styleSettings as $setting){
        if(!isset($info['style']['settings'][$setting['setting']])) continue;
        $settingInfo = $info['style']['settings'][$setting['setting']];
        if($settingInfo['type'] == 'boolean'){
        $selected = $setting['setting_value'] ? l("Yes") : l("No");
        $styleForm->addRadios($settingInfo['description'], $setting['setting'], array(
            l("Yes") => "true",
            l("No") => "false"
        ), $selected, false);
        }else
            $styleForm->addInput($settingInfo['description'], $setting['setting'], "text", $setting['setting_value'], false, false, false);
    }
    $styleForm->addButton("Save", true);
    $settings->addObject($styleForm);
}
    

$settings->render();
?>
