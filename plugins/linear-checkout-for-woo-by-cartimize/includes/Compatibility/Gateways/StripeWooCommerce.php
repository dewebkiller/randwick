<?php
/**
 * Linear Checkout for WooCommerce by Cartimize
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

namespace Cartimize\Compatibility\Gateways;

use Cartimize\Compatibility\Core;

class StripeWooCommerce extends Core {
	public function is_available() {
		return class_exists( '\\WC_Stripe_Field_Manager' );
	}

	public function run() {
		// Remove theirs
		add_action ( 'woocommerce_checkout_before_customer_details', array(
			'\\WC_Stripe_Field_Manager', 'output_banner_checkout_fields'
		) );

		// Add our own stripe requests
		add_action( 'cartimize_payment_request_buttons', array( '\\WC_Stripe_Field_Manager', 'output_banner_checkout_fields' ), 1 );
		add_action( 'cartimize_checkout_add_separator', array( $this, 'add_separator' ), 12 ); // This should be 12, which is after 11, which is the hook other gateways use
	}
}