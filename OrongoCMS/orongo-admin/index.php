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
            $index->addMessage(l("Invalid Query Arg"), "warning");
            break;
        case 2:
            $index->addMessage(l("Internal Error"), "warning");
            break;
    }
}



$index->addObject(new AdminFrontendObject(100, "test", "lol",null));
$index->render();
?>
