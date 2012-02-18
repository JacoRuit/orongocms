<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();

setCurrentPage('admin_index');

Security::promptAuth();
$n = new OrongoNotification("This is notification", "Hello World!", "http://code.google.com/p/orongocms/logo?cct=1325506665");
$n->dispatch(getUser());
$index = new AdminFrontend();
$index->main(array("time" => time(), "page_title" => "Dashboard", "page_template" => "dashboard"));

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case 0:
            $index->addMessage(l("No Permission"), "error");
            break;
        case 1:
            $index->addMessage(l("Invalid Query Arg"), "warning");
            break;
        case 2:
            $index->addMessage(l("Internal Error"), "warning");
            break;
    }
}

$form = new AdminFrontendForm(100, "test", "POST", orongoURL("ajax/fetchNotifications.php"));

for($i = 0; $i < 10; $i++){
    $index->addObject(new AdminFrontendObject(100,"test", "blabala<br/>sdfsdf<br/>"));
}
$index->render();
?>
