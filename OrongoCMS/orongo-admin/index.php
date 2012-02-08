<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_index');

Security::promptAuth();

$index = new AdminFrontend();
$index->main(array("time" => time(), "page_title" => "Dashboard", "page_template" => "dashboard"));

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case 0:
            $index->addMessage(l("No Permission"), "error");
            break;
        case 1:
            $index->addMessage(l("No Query Arg"), "warning");
            break;
    }
}


$form = new AdminFrontendForm(100, "test", "POST", "bla.php");
$form->addInput("Test", "blaname", "text");
$form->addButton("hoi", true);
$index->addObject($form);
$index->addObject(new AdminFrontendObject(100, "test", "lol",null));
$index->render();
?>
