<?php /* Smarty version Smarty 3.1.4, created on 2011-12-14 21:07:21
         compiled from "style\interface.index.orongo" */ ?>
<?php /*%%SmartyHeaderCode:88004ee7d78fdb6381-55273692%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c4917e49440dea0b22e792a50231afd95dbcede' => 
    array (
      0 => 'style\\interface.index.orongo',
      1 => 1323893235,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88004ee7d78fdb6381-55273692',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4ee7d78fdb74b',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'username' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ee7d78fdb74b')) {function content_4ee7d78fdb74b($_smarty_tpl) {?>    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

        });
    </script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/interface.menu_effects.js" type="text/javascript" charset="utf-8"></script>
    <div class="menu fixed hide">
        <div class="seperator right hide" style="padding-right: 100px"></div>
        <div class="menu_text right hide">
            Settings
        </div>
        <div class="icon_settings right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text right hide">
            Notifications
        </div>
        <div class="icon_messages right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text right hide">
            Pages
        </div>
        <div class="icon_pages right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text left hide" style="font-size: 10px; padding-left: 200px">
            <div class="icon_account_small left"></div> Logged in as <?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-logout.php">Logout</a>
        </div>
    </div>
    <div id="lol"></div>
<?php }} ?>