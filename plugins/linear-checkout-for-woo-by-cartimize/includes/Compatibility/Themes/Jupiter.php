<?php
/**
 * Linear Checkout for WooCommerce by Cartimize
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

namespace Cartimize\Compatibility\Themes;

use Cartimize\Compatibility\Core;

class Jupiter extends Core {
	function is_available() {
		$theme = wp_get_theme();

		return $theme->template == 'jupiter' && class_exists( '\\MK_Customizer' ) && class_exists( '\\ReflectionFunction' );
	}

	public function run() {
		$this->unset_theme_callbacks( 'woocommerce_after_checkout_billing_form' );
		$this->unset_theme_callbacks( 'woocommerce_check_cart_items' );
		$this->unset_theme_callbacks( 'woocommerce_review_order_before_payment' );
		$this->unset_theme_callbacks( 'woocommerce_review_order_before_submit' );
		$this->unset_theme_callbacks( 'woocommerce_check_cart_items', 9 );

		// Reverse their other stuff too
		add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review' );
		remove_action( 'woocommerce_checkout_shipping', 'woocommerce_order_review', 10 );

		add_action( 'woocommerce_checkout_shipping', array( \WC_Checkout::instance(), 'checkout_form_shipping' ) );
		remove_action( 'woocommerce_checkout_billing', array( \WC_Checkout::instance(), 'checkout_form_shipping' ) );

	}

	function unset_theme_callbacks( $hook, $priority = 10 ) {
		global $wp_filter;

		$existing_hooks = $wp_filter[ $hook ];

		if ( $existing_hooks[ $priority ] ) {
			foreach ( $existing_hooks[ $priority ] as $key => $callback ) {
				if ( is_array( $callback['function'] ) ) {
					continue;
				}

				try {
					$ref = new \ReflectionFunction( $callback['function'] );

					if ( stripos( $ref->getFileName(), get_template_directory() ) !== false ) {
						remove_action( $hook, $callback['function'], $priority );
						unset( $wp_filter[ $hook ][ $priority ][ $key ] );
					}
				} catch ( \Exception $e ) {
					error_log( 'Cartimize: Failed to unset Jupiter theme callbacks.' );
				}
			}
		}
	}
}
