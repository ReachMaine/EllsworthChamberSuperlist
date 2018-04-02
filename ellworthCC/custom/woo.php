<?php
/* woocommerce customizations
*/
// to remove sku from everywhere....
add_filter( 'wc_product_sku_enabled', '__return_false' );

// remove the Additional_information tab
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

	function woo_remove_product_tabs( $tabs ) {

		unset( $tabs['additional_information'] );  	// Remove the additional information tab

		return $tabs;

	}
  // remove categories printed at bottom of single product page.
  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    /*
  Disable Variable Product Price Range completely:
  */

  add_filter( 'woocommerce_variable_sale_price_html', 'my_remove_variation_price', 10, 2 );
  add_filter( 'woocommerce_variable_price_html', 'my_remove_variation_price', 10, 2 );

  function my_remove_variation_price( $price ) {
      $price = '';
      return $price;
    }
?>
