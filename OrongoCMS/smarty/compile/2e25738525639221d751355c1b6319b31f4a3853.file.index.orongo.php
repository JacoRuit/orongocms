<?php /* Smarty version Smarty 3.1.4, created on 2012-01-03 12:45:14
         compiled from "themes/default\index.orongo" */ ?>
<?php /*%%SmartyHeaderCode:107574eea0dd17bb473-72456378%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e25738525639221d751355c1b6319b31f4a3853' => 
    array (
      0 => 'themes/default\\index.orongo',
      1 => 1325591112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '107574eea0dd17bb473-72456378',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4eea0dd1926d9',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'plugin_document_ready' => 0,
    'menu_bar' => 0,
    'plugin_body' => 0,
    'errors' => 0,
    'website_name' => 0,
    'menu' => 0,
    'articles' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4eea0dd1926d9')) {function content_4eea0dd1926d9($_smarty_tpl) {?>    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

            <?php echo $_smarty_tpl->tpl_vars['plugin_document_ready']->value;?>

        });
    </script>
    <?php echo $_smarty_tpl->tpl_vars['menu_bar']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['plugin_body']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['errors']->value;?>

    <div id="header">
        <h1><?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
</h1>
        <div id="menuwrap">
            <div id="menu">
                <?php echo $_smarty_tpl->tpl_vars['menu']->value;?>

            </div>
        </div>
    </div>
    <div id="blog_posts">
        <?php echo $_smarty_tpl->tpl_vars['articles']->value;?>

    </div>
        <?php }} ?>