<?php /* Smarty version Smarty 3.1.4, created on 2012-01-04 17:09:11
         compiled from "style\index.orongo" */ ?>
<?php /*%%SmartyHeaderCode:82454ee91371cd7323-90342227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd08225ce08f562c4396303445d5945ee33d35d6c' => 
    array (
      0 => 'style\\index.orongo',
      1 => 1325604885,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '82454ee91371cd7323-90342227',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4ee91371d6028',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'username' => 0,
    'content_block_1' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ee91371d6028')) {function content_4ee91371d6028($_smarty_tpl) {?>    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

        });
    </script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/interface.menu_effects.js" type="text/javascript" charset="utf-8"></script>
    <div class="orongo_menu fixed hide">
        <div class="seperator right hide" style="padding-right: 100px"></div>
        <div class="menu_text right hide">
            Settings
        </div>
        <div class="icon_settings_small right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text right hide">
            Notifications
        </div>
        <div class="icon_messages_small right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text right hide">
            Pages
        </div>
        <div class="icon_pages_small right hide"></div>
        <div class="seperator right hide"></div>
        <div class="menu_text left hide" style="padding-left: 200px">
            <div class="icon_account_small left"></div> Logged in as <?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-logout.php">Logout</a>
        </div>
    </div>
    <div id="container" class="hidden">
    <div class="content_block  left hidden" style="padding-top:100px; padding-left:150px"><?php echo $_smarty_tpl->tpl_vars['content_block_1']->value;?>
</div>
    <div class="content_block  hidden right" style="padding-top:100px; padding-right:150px">
        <h2>OrongoCMS News</h2>
        <script src="http://widgets.twimg.com/j/2/widget.js"></script>
        <script>
            new TWTR.Widget({
            version: 2,
            type: 'profile',
            rpp: 6,
            interval: 30000,
            width: 250,
            height: 300,
            theme: {
                shell: {
                    background: '#ffffff',
                    color: '#000000'
                },
                tweets: {
                    background: '#ffffff',
                    color: '#000000',
                    links: '#ff8400'
                }
            },
            features: {
                scrollbar: true,
                loop: false,
                live: false,
                behavior: 'all'
            }
            }).render().setUser('OrongoCMS').start();
        </script>
    </div>
    </div><?php }} ?>