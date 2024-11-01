<?php
/**
 * Plugin Name: Text.Rocks
 * Plugin URI: https://text.rocks
 * Description: Text.Rocks is an omni-channel chat widget you can include in your Wordpress websites. You can configure what channels go into this widget by logging-in to your account at https://text.rocks
 * Version: 4.9
 * Author: txtrocks
 * Author URI: https://www.n-frames.com
 * License: GPLv2 or later
*/
class TextRocksWidget{
	public function __construct(){
		add_action('admin_menu', array($this,'textrocks_menu_setting'));
		add_action( 'wp_enqueue_scripts', array($this,'textrocks_script' ));
	}
	public function textrocks_script(){
		$textrocks_widget_id = get_option('textrocks_widget_id');
		$textrocks_widget_active = get_option('textrocks_widget_active');
		if($textrocks_widget_active==1 && !empty($textrocks_widget_id)){
			wp_enqueue_script( 'textrocks-widget', 'https://cdn.text.rocks/js/widget.min.js', array(), '1.0.0', true );
			wp_add_inline_script( 'textrocks-widget', 'textrocks.init("'.$textrocks_widget_id.'");' );
		}
	}
	public function textrocks_menu_setting(){
		add_submenu_page('options-general.php', 'TextRocks Widget Settings','TextRocks', 'manage_options', 'textrocks-settings', array($this,'textrocks_settings_page'));
	}
	public function textrocks_settings_page(){
		if (!current_user_can('manage_options')) {
			wp_die('Unauthorized user');
		}
        if (isset($_POST['save'])) {
            $textrocks_settings = sanitize_text_field($_POST['textrocks_settings']);
            if (!wp_verify_nonce($textrocks_settings, 'save_settings')) {
                wp_die('Nonce verification failed');
            }
			update_option('textrocks_widget_id', sanitize_text_field($_POST['textrocks_widget_id']));
			update_option('textrocks_widget_active', sanitize_text_field($_POST['textrocks_widget_active']));
        }
		$textrocks_widget_id = get_option('textrocks_widget_id');
		$textrocks_widget_active = get_option('textrocks_widget_active');
	?>
	<div class="wrap">
		<style>
		label{font-weight:700;}
		#textrocks_widget_id{
			box-shadow: inset -4px -4px 8px 0 rgb(255 255 255 / 70%), inset 4px 4px 8px 0 rgb(0 0 0 / 5%);
			border-radius: 8px;
			border: 0px;
			font-size: 16px;
			color: rgba(29,29,31,0.60);
			padding: 8px 15px;
			background-color: #F0F0F0;
			height: 45px;
		}
		.wp-core-ui .button-primary.btnsubmit{box-shadow: 0 4px 6px rgb(50 50 93 / 11%), 0 1px 3px rgb(0 0 0 / 8%);
			border-radius: 4px;
			font-family: 'SF Pro Text';
			font-size: 18px;
			border: none;
			transition: all .25s ease;
			font-weight: 400;
			letter-spacing: -0.29px;
			color: #fff!important;
			background: #007AFF;
			width: 100px;
			margin-left: 10px;
			margin-top: 20px;
		}
		.wrap{background: #F0F0F0;
			box-shadow: -15px 15px 30px 0 rgb(0 0 0 / 7%), 15px -15px 30px 0 rgb(255 255 255 / 80%);
			border-radius: 16px;
			padding: 20px;
		}
		.switch { position: relative; display: inline-block; width: 52px;height: 30px; margin-top: -2px;}
            .switch input {opacity: 0;width: 0;height: 0;}
            .switch .slider {position: absolute;cursor: pointer;top: 0;left: 0; right: 0; bottom: 0;background-color: #ccc; -webkit-transition: .4s;transition: .4s;}
            .switch .slider:before {position: absolute;content: "";height: 24px;width: 24px;left: 3px;bottom: 3px; background-color: white; -webkit-transition: .4s;transition: .4s;box-shadow: 2px 4px 6px rgb(0 0 0 / 20%);}
            .switch input:checked + .slider {background-color: #007AFF;}
            .switch input:focus + .slider {box-shadow: 0 0 0px #007AFF;}
            .switch input:checked + .slider:before { -webkit-transform: translateX(22px); -ms-transform: translateX(22px);transform: translateX(22px);}
            .switch .slider.round {border-radius: 34px;}
            .switch .slider.round:before {border-radius: 50%;}
            .switch input[type=checkbox][disabled]~.slider {background-color: darkgrey;}
		</style>
		<h1 style="font-weight: 500;">TextRocks Widget Settings</h1>
		<form method="post" action="" enctype="multipart/form-data">
			<table class="form-table">
            	<tbody>
					<tr>
                        <td>
						<label for="textrocks_widget_active">Enable Widget</label><br><br>
						<label class="switch">
                        <input type="checkbox" id="textrocks_widget_active" class="switcher changeactive" name="textrocks_widget_active" value="1" <?php if($textrocks_widget_active==1) echo 'checked="checked"';?>><span class="slider round"></span>
						</label>
						</td>
                    </tr>
					<tr  id="textrocks_widget_id_show" <?php if($textrocks_widget_active!=1) echo 'style="display:none;"';?>>
                        <td>
						<label for="textrocks_widget_id">Widget ID </label><br><br>
						<input type="text" id="textrocks_widget_id" name="textrocks_widget_id" value="<?php echo $textrocks_widget_id;?>" /></td>
                    </tr>
                </tbody>
            </table>
			<script>
				jQuery('#textrocks_widget_active').click(function() {
					if (this.checked) {
						jQuery("#textrocks_widget_id_show").show();
					} else {
						jQuery("#textrocks_widget_id_show").hide();
					}
				});
			</script>
            <?php echo wp_nonce_field( 'save_settings','textrocks_settings' ); ?>
            <input type="submit" name="save" value="Save" class="btnsubmit button button-primary button-large">      
	    </form>
	</div>
	<?php
	}
}
new TextRocksWidget();