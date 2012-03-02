<?php
/**
 * @author Jaco Ruit
 */
require '../startOrongo.php';
startOrongo();

setCurrentPage('admin_orongo-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(getStyle()->isUsingPHP() == false){ header("Location: " . orongoURL("orongo-admin/index.php?msg=2")); exit;}

$xml = @simplexml_load_file(getStyle()->getStylePath() . "info.xml");
$json = @json_encode($xml);
$info = @json_decode($json, true);
if(!is_array($info['style']['settings'])){ header("Location: " . orongoURL("orongo-admin/index.php?msg=2")); exit;}
$styleSettings = getDatabase()->query("SELECT `setting` FROM `style_data` WHERE `style_main_class` = %s", $info['style']['main_class']);
foreach($styleSettings as $setting){
    if(isset($_POST[$setting['setting']])){
        getDatabase()->update("style_data", array(
            "setting_value" => $_POST[$setting['setting']]
        ), "setting = %s", $setting['setting']);
    }
}

header("Location: " . orongoURL("orongo-admin/orongo-settings.php?msg=0"));
exit;
?>