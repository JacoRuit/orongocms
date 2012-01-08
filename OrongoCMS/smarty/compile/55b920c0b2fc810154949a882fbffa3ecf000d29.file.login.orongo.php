<?php /* Smarty version Smarty 3.1.4, created on 2012-01-08 21:02:02
         compiled from "orongo-admin/style\login.orongo" */ ?>
<?php /*%%SmartyHeaderCode:255384ee8bb86c3c611-09968803%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '55b920c0b2fc810154949a882fbffa3ecf000d29' => 
    array (
      0 => 'orongo-admin/style\\login.orongo',
      1 => 1325604884,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '255384ee8bb86c3c611-09968803',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4ee8bb86dca13',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'website_name' => 0,
    'login_msg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ee8bb86dca13')) {function content_4ee8bb86dca13($_smarty_tpl) {?>    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".login").fadeIn(700);
            $(".logowrap").fadeIn(2000);
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

        });
    </script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/interface.menu_effects.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/login.document_ready.js" type="text/javascript" charset="utf-8"></script>
    <div class="orongo_menu hide fixed">
        <div class="menu_text left hide" style="padding-left: 200px">
            <div class="icon_account_small left"></div> Not logged in | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
">Back to <?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
</a>
        </div>
    </div>
    <div class="logowrap">
	<a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-login.php"><img class="logo" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
/orongo-admin/style/images/logo.png" alt="orongo"/></a>
    </div>
    <?php echo $_smarty_tpl->tpl_vars['login_msg']->value;?>

    <div class="login">
	<p><?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
 - Login</p>
	<form  method="post" action="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
actions/action_Login.php" >
		<input class="login_input" type="text" name="username" value="Username"/>
		<input class="login_input" type="password" name="password" />
		<input class="login_submit" type="submit"/>
	</form>
    </div>
    <div class="info">
        <p><a href="#">Forgot Password?</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-register.php">Register</a></p>
    </div><?php }} ?>