<?php
/* languages customizations
*/
	if ( !function_exists('eai_change_theme_text') ){
		function eai_change_theme_text( $translated_text, $text, $domain ) {
			 /* if ( is_singular() ) { */
			 	switch ( $domain) {
			 		case 'eventon':
			 			switch ( $translated_text ) {
				 			case 'Organizer Address':
				            	$translated_text = __( 'Organizer Address/Phone',  $domain  );
				            	break;
			            	case 'Enter Organizer Address':
				            	$translated_text = __( 'Enter Organizer Address/Phone',  $domain  );
				            	break;
				            case '(Optional) Organizer Address':
				            	$translated_text = __( '(Optional) Organizer Address/Phone',  $domain  );
				            	break;
			            } // end eventOn text switch
							break;
			 		default: // other domains
				 		switch ( $translated_text ) {
				            case 'Call us anytime' :
				                $translated_text = __( 'Call us',  $domain  );
				                break;
				            case 'Previous post':
				            	$translated_text = __( 'Previous',  $domain  );
				            	break;
				            case 'Next post':
				            	$translated_text = __( 'Next',  $domain  );
				            	break;
				            case 'Your wishlist is empty.':
				            	$translated_text = __( '',  $domain  );
				            	break;
				            case 'Specification':
				            	$translated_text = __( '',  $domain  );
				            	break;
				            case 'Organizer Address':
				            	$translated_text = __( 'Organizer Address/Phone',  $domain  );
				            	break;
				            /* case 'Share this post:':
				            	$translated_text = __('Share', ' $domain );
				            	break; */
			        	}
			 			break;
			 	}

		    /* } */

	    	return $translated_text;
		}
		add_filter( 'gettext', 'eai_change_theme_text', 20, 3 );
	}

?>
