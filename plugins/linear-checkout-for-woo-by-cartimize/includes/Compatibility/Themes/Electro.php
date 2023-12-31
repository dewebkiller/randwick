<?php
/**
 * Linear Checkout for WooCommerce by Cartimize
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

namespace Cartimize\Compatibility\Themes;

use Cartimize\Compatibility\Core;

class Electro extends Core {
	public function is_available() {
		return function_exists( 'electro_wrap_order_review' );
	}

	function pre_init() {
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'cleanup_actions' ) );
	}

	public function run() {
		$this->cleanup_actions();
	}

	function cleanup_actions() {
		remove_action( 'woocommerce_checkout_shipping', 'electro_shipping_details_header', 0 );
		remove_action( 'woocommerce_checkout_before_order_review', 'electro_wrap_order_review', 0 );
		remove_action( 'woocommerce_checkout_after_order_review', 'electro_wrap_order_review_close', 0 );
	}
}
