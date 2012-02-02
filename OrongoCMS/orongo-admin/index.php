<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_index');

Security::promptAuth();

$index = new AdminFrontend();
$index->main(array("time" => time(), "page" => "index"));
$form = new AdminFrontendForm("lol", "full", "POST", "bla.php");
$form->addInput("text", "Test", "d");
$form->addButton("hoi", true);
$index->addObject($form);
$index->addObject(new AdminFrontendObject("test", "full", "lol",null));
$index->render();
?>
