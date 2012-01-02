<?php /* Smarty version Smarty 3.1.4, created on 2011-12-16 00:27:04
         compiled from "orongo-admin/style\header.orongo" */ ?>
<?php /*%%SmartyHeaderCode:308774ed6589dc358a0-92185707%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40e29b7afb33ff1ec4bcf2ced83da4c21a0982c2' => 
    array (
      0 => 'orongo-admin/style\\header.orongo',
      1 => 1323982534,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '308774ed6589dc358a0-92185707',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4ed6589e46741',
  'variables' => 
  array (
    'head_title' => 0,
    'website_url' => 0,
    'style' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ed6589e46741')) {function content_4ed6589e46741($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['head_title']->value;?>
</title>
        <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>     
        <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/style.menu.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/style.interface.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
<?php }} ?>