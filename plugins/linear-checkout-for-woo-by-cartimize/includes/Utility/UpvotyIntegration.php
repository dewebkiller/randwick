<?php
/**
 * Linear Checkout for WooCommerce by Cartimize
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

namespace Cartimize\Utility;

use Cartimize\Cartimize;

class UpvotyIntegration{

	public $upvoty_url = "https://feedback.cartimize.com/";
	public $upvoty_base_url = "feedback.cartimize.com";
	private $upvoty_public_key = "ec6c1200b5304429f3a395b0e06960f9";

	public $plugin_instance;

	public function __construct( $plugin ) {
		$this->plugin_instance = $plugin;
		add_action( 'init', array( $this, 'init_hooks' ), 1 );
	}

	public function init_hooks(){
		add_action( 'cartimize_setting_page', array( $this, 'feedback_integration' ), 10 );
		add_action( 'wp_footer', array( $this, 'single_singon' ), 10 );
		add_action( 'admin_footer', array( $this, 'single_singon' ), 10 );
	}

	public function feedback_integration(){
		if ( current_user_can('administrator') ) { 
			$site_token = $this->plugin_instance->get_license_controller()->get_setting( 'site_token' );
			if ( $site_token == false ) {
				$url = '<a class="suggest-feature" href="'.esc_url(CARTIMIZE_LOGIN_PAGE_URL_MUST_SIGNUP).'">
			      <h6>Suggest a Plugin/Feature</h6>
			      <p>Let us know your favorite plugins and we’ll make LCW compatible.</p>
			    </a>';
			}else{
				$url = '<a class="suggest-feature" href="'.esc_url($this->upvoty_url).'b/feature-requests/'.'">
			      <h6>Suggest a Plugin/Feature</h6>
			      <p>Let us know your favorite plugins and we’ll make LCW compatible.</p>
			    </a>';
			}
		?>
			<div class="beta-program-cont">
				<?php echo wp_kses_post($url); ?>
			    <a class="report-bug cartimize-report-bug" title="Report Bug" style="cursor: pointer;">
			      <h6>Report a Bug</h6>
			      <p>Found a bug? Report it and we’ll fix it immediately.</p>
			    </a>
			    <a class="get-support" target="_blank" href="mailto:support@cartimize.com">
			      <h6>Get Support</h6>
			      <p>Need assistance? Email us and we’ll get back to you asap.</p>
			    </a>
			    <div class="visible-admin">Visible only to you (admin)</div>
			</div>
		<?php
		}
	}

	public function single_singon(){
		$site_token = $this->plugin_instance->get_license_controller()->get_setting( 'site_token' );
		$email = $this->plugin_instance->get_license_controller()->get_setting( 'email' );
		$user_id = $this->plugin_instance->get_license_controller()->get_setting( 'user_id' );

		if ( !current_user_can('administrator') || $site_token == false || $email == false || $user_id == false || !((isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'cartimize_checkopt_settings') || Cartimize::is_cartimize_checkout()) ) {
			 return ;
		}
		$email_parts = explode("@", $email);
		$username = $email_parts[0];
		
		wp_enqueue_script('cartimize-upvoty', $this->upvoty_url.'javascript/upvoty.embed.js', array(), CARTIMIZE_VERSION, true);
		
		$script = "upvoty.init('identify', {
	        user: {
	            id: ".json_encode($user_id).",
	            name: ".json_encode($username).",
	            email: ".json_encode($email).",
	        },
	        baseUrl: '".$this->upvoty_base_url."',
	        publicKey: '".$this->upvoty_public_key."',
	    });";
		
		
		wp_add_inline_script('cartimize-upvoty', $script, 'after');
	}
}