<?php /* Smarty version Smarty 3.1.4, created on 2011-12-15 16:21:57
         compiled from "themes/default\header.orongo" */ ?>
<?php /*%%SmartyHeaderCode:208604eea0cd564ad80-80601752%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fc98276be622b629702b6e61a5654ff1005e9130' => 
    array (
      0 => 'themes/default\\header.orongo',
      1 => 1323962516,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208604eea0cd564ad80-80601752',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4eea0cd61cebc',
  'variables' => 
  array (
    'head_title' => 0,
    'website_url' => 0,
    'head' => 0,
    'plugin_head' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4eea0cd61cebc')) {function content_4eea0cd61cebc($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['head_title']->value;?>
</title>
        <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>     
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/default/style.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
        <?php echo $_smarty_tpl->tpl_vars['head']->value;?>

        <?php echo $_smarty_tpl->tpl_vars['plugin_head']->value;?>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
<?php }} ?>