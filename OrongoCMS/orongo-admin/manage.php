<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_view');

Security::promptAuth();

if(getUser()->getRank() < RANK_ADMIN){ header("Location: index.php"); exit; }

$view = new AdminFrontend();

$view->main(array("time"=>time(),"page_title" => "test", "page_template" => "dashboard"));

$manager = new AdminFrontendContentManager(100, "test");
$manager->createTab("test", array("bla1"));
$manager->addItem("test", array("test"), "bla.php", "bla.php");
$view->addObject($manager);
$view->render();
?>