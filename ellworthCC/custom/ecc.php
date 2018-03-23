<?php /* custom coding for theme  */
// allow shortcodes in contact form 7....

add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );
function mycustom_wpcf7_form_elements( $form ) {
	$form = do_shortcode( $form );

	return $form;
}


// function for shortcode ecc_categories
if (!function_exists('ecc_listing_categories_display')) {
	function ecc_listing_categories_display( $atts ) {
		$atts = shortcode_atts( array(
			'show_all' => true,
		), $atts, 'ecc_categories' );

	// List terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
	 
	$taxonomy     = 'listing-item-category';
	$orderby      = 'name'; 
	$show_count   = false;
	$pad_counts   = false;
	$hierarchical = true;
	$title        = '';
	 
	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'echo'		 => 0,  // return, dont output
	);

 		$html_out = "";
		$html_out .= '<ul class="ecc_members_cats">';
	    $html_out .= wp_list_categories( $args );
		$html_out .= "</ul>";
		return $html_out;
	}
}
add_shortcode( 'ecc_categories', 'ecc_listing_categories_display' );

// function to query featured listing items in type & category, etc

if ( ! function_exists( 'ecc_query_feat_listing_items' ) ) {
	/**
	 * Function for getting listing items
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	function ecc_query_feat_listing_items( $params = array() ) {
		global $search_params;
		$search_params = $params;

		$args = array(
			'post_type' => 'listing-item',
			'post_status' => 'publish'
		);

		if ( $params ) {
			extract($params);

			if ( isset( $keywords ) ) {
				$args['s'] = $keywords;
			}
			
			if ( isset($number) ) {
				$args['posts_per_page'] = $number;
			}
			if ( isset($type) ) {
				if($type !== '' && $type !=='all' ){
					$args['meta_key'] = 'eltd_listing_item_type';
					$args['meta_value'] = $type;
				}
			}

			$args['tax_query'] = array(
				'relation' => 'AND'
			);

			if ( isset( $category ) ) {
				if($category !== '' && $category !=='all' ){
					$args['tax_query'][] = array(
						'taxonomy' => 'listing-item-category',
						'field' => 'term_id',
						'terms' => (int)$category
					);
				}
			}

			if ( isset($location) ) {
				if($location !== '' && $location !=='all' ){
					$args['tax_query'][] = array(
						'taxonomy' => 'listing-item-location',
						'field' => 'term_id',
						'terms' => (int)$location
					);
				}
			}

			if ( isset($tag) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'listing-item-tag',
					'field' => 'term_id',
					'terms' => (int)$tag
				);
			}

		}
		echo "ecc args: <pre>"; var_dump($args); echo "</pre>";
		$fquery = new WP_Query($args);
		echo "SQL for  featured listings:  {$fquery->request}";
		//add_filter('search_and_go_elated_multiple_map_variables', 'search_and_go_elated_return_search_map_data');
		return ($fquery);
		//return $fquery;

	}

}

// function for shortcode : ecc_feat_list
function  ecc_listing_feat_list( $atts) {
//echo "atts <pre>"; var_dump($atts); echo "</pre>";
		$args = array(
			'listing_feat_list_item_number'	=> '-1',
			'type'	=> '',
			'location' => '',
			'category' => '',
			'number' => "4", 
			'template' => 'default',
			'title' => '',
		);
		$html_out = "";

		$params = shortcode_atts($args, $atts, 'ecc_feat_list' );
		extract($params);
//$html_out .= "<p>location = ".$location." and type = ".$type." and number is: ".$number."</p>";
//echo "PARAMS <pre>"; var_dump($params); echo "</pre>";
		//set post args
		//Get listing items which are set as featured for listing featured list shortcode 
		/* $post_args = array(
			'posts_per_page'   => $number,
			'meta_key'         => 'eltd_listing_feature_item',
			'meta_value'       => 'yes',
			'post_type'        => 'listing-item',
			'post_status'      => 'publish'
		); */
		$meta_query = array(
			array(
					'key' => 'eltd_listing_feature_item',
					'value' => 'yes'
				));
		if ( isset($type) ) {
			if($type !== '' && $type !=='all' ){
				$meta_query[] = array(
					'key' => 'eltd_listing_item_type',
					'value' => $type);
				$meta_query['relation'] = 'AND';
			}
		}
		

		$post_args = array(
			'posts_per_page' => -1,
			'meta_query' 	=> 	$meta_query,
			//'meta_key'         => 'eltd_listing_feature_item',
			//'meta_value'       => 'yes',
			'post_type'     => 'listing-item',
			'post_status'	=> 'publish',
			'orderby' 		=> 'rand'

		);
		if ( isset( $category ) ) {
			if($category !== '' && $category !=='all' ){
				$post_args['tax_query'][] = array(
					'taxonomy' => 'listing-item-category',
					'field' => 'term_id',
					'terms' => (int)$category
				);
			}
		}

		if (isset ($location) ) {
			if($location !== '' ){
				//$location_term = get_term_by( 'name', $location, 'listing-item-location' );
				$post_args['tax_query'][] = array(
						'taxonomy' => 'listing-item-location',
						'field' => 'term_id',
						'terms' => (int)$location
					);
			}
		}
		//set taxonomy args
		$tax_args = array(
			'number' => (int)$number,
			'meta_query' => array(
				array(
					'key' => 'featured_taxonomy',
					'value' => 'yes'
				)
			)
		);
		
		$featured_tax_array = $featured_post_array = array();		
//		echo "post_args:  <pre>"; var_dump($post_args); echo "</pre>";
		//get all featured listing items
		$posts_array = get_posts( $post_args );
//echo "Posts <pre>"; var_dump($posts_array); echo "</pre>";
//echo "Posts count: <pre>"; echo count($posts_array); echo "</pre><br>";		

		//$html_out .= '<div class = "eltd-listing-feat-list-holder ecc-box ecc-'.$template.'">';
		
		if ($posts_array) {
			
			if ($template == 'default') {
				$html_out .= '<div class = "eltd-listing-feat-list-holder ecc-box ecc-'.$template.'">';
				$html_out .= '<div class = "eltd-listing-feat-list-holder-sizer "></div>';
			} else {
				$html_out .= '<div class = "ecc-listing-feat-list-holder ecc-box ecc-'.$template.'">';
			}
			if ($title != "") {
				$html_out .= '<h4 class="ecc-listing-feat-heading">'.$title.'</h4>';
			}
			$i = 0;
			foreach($posts_array as $listpost){
			//for  ($i = 0; $i<$number; $i++) {

				if ($i < $number) {
				/*
				$params['item_permalink'] = $this->getListingPermalink($feature_obj['post_object']->ID);
						$params['item_title'] = $feature_obj['post_object']->post_title;
						
						//get image class and size 
						$image_params = $this->getListingItemImageParams($feature_obj['post_object']->ID);					
						
						$params['item_layout_class'] = $image_params['layout_class']; 
						$params['item_feature_image'] = $this->getListingFeatureImage($feature_obj['post_object']->ID, $image_params['thumb_size']);
						 
				*/
					if ($template == 'stacked') {
						$image_size = array( 150, 150) ;
					} else {
						$image_size = array(300,300);
					}	

					$params['item_permalink'] = get_permalink($listpost->ID);
					$params['item_title'] = $listpost->post_title;
					$params['item_layout_class'] = 'eltd-listing-feature-square'; 
					$params['item_feature_image'] = get_the_post_thumbnail($listpost->ID, $image_size);
					$params['category_icon_array'] = '';
					$html_out .= eltd_listing_get_shortcode_module_template_part('listing', 'listing-feature-item', '', $params);
			
				} // end if
				$i++;
			} // end for
			$html_out .= '</div>';
		} else {
			$html_out .= "<!-- No Featured Listings -->";
		}
		
		return ($html_out);
		
}
add_shortcode( 'ecc_feat_list', 'ecc_listing_feat_list' );

/* trying to order the listings on the front end alphabetically to start (instead of by postdate) */
/* not going to work this way because I THINK the listings are loaded via AJAX - 
/* function order_cpt( $query ) {

	if ( !is_admin() &&  $query->is_main_query() )  {
		if ($query->is_post_type_archive('listing-item') ||  in_array ( $query->get('post_type'), array('listing-item') ) ) {
			$query->set( 'orderby', 'post_title');
			$query->set( 'order', 'ASC' );
			//return $query;
		}
	}
} */
//add_action( 'pre_get_posts', 'order_cpt', 100); 

/* use action to put phone number at top on mobile */
add_action('search_and_go_elated_after_header_top', 'ecc_top_bar');
function ecc_top_bar() {
	$html_out = "";
	$html_out .= '<div class="ecc-top-phone"><a href="tel:207-667-5584"><i class="eltd-icon-font-awesome fa fa-phone"></i> 207-667-5584</a></div>';
	echo $html_out;
}