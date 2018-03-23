<?php
/**
 * Event Manager for frontend user submitted events
 * @version 2.0.14
 * @author  AJDE
 *
 * You can copy this template file and place it in ...wp-content/themes/<--your-theme-name->/eventon/actionuser/ folder
 * and edit that file to customize this template.
 * This sub content template will use default page.php template from your theme and the below
 * content will be placed in content area of the page template.
 */

	// INITIAL
	$fcnt  = $eventon_au->frontend->functions;

	$eventon_au->frontend->print_em_styles();

?>
<div id='evoau_event_manager'>
<?php

	/* zig xout echo "<h2 class='title'>".$fcnt->get_lang('My Eventon Events Manager')."</h2>"; */
	if(!is_user_logged_in()){
		echo "<p class='intro'>".$fcnt->get_lang('Login required to manage your submitted events')." <br/><a href='".wp_login_url($current_page_link)."' class='evcal_btn evoau'><i class='fa fa-user'></i> ".$fcnt->get_lang('Login Now')."</a></p>";
		return;
	}
?>

<?php /* zig xout <p><?php echo $fcnt->get_lang('Hello');?> <?php echo $current_user->display_name?>. <?php echo $fcnt->get_lang('From your event manager dashboard you can view your submitted events and manage them in here');?></p> */ ?>

<?php
	//get back link to event manager
		$BACKLINK = $fcnt->get_backlink($current_page_link);

		$BACK_BTN = "<p><a class='evoau evoau_back_btn' href='".($BACKLINK)."'><i class='fa fa-angle-left'></i> ".$fcnt->get_lang('Back to my events')."</a></p>";

	// Edit an event
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit' && !empty($_REQUEST['eid'])):

		// check if the user have permission to edit this event
		if(!$fcnt->can_currentuser_edit_event($_REQUEST['eid'])){
			echo "<script type='text/javascript'> window.location = '{$BACKLINK}';</script>";
		}

		echo $BACK_BTN;
		$eventon_au->frontend->get_submission_form($_REQUEST['eid'], $atts);


	elseif( isset($_REQUEST['customaction']) && !empty($_REQUEST['eid']) ):

		// check if the user have permission to edit this event
		if(!$fcnt->can_currentuser_edit_event($_REQUEST['eid'])){
			echo "<script type='text/javascript'> window.location = '{$BACKLINK}';</script>";
		}
		do_action('evoauem_custom_action', $BACK_BTN);

	else:

		// trash event
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && !empty($_REQUEST['eid'])){

			// check if the user have permission to edit this event
			if(!$fcnt->can_currentuser_delete_event($_REQUEST['eid'])){
				echo "<script type='text/javascript'> window.location = '{$BACKLINK}';</script>";
			}else{
				$status =	$fcnt->trash_event($_REQUEST['eid']);
				$eventname = get_the_title($_REQUEST['eid']);
				echo "<h3 class='evoauem_del_msg' style='background-color: #B3D488;color: #fff;'>".( ($status)? $eventname.' Deleted!':'Could not delete event.')."</h3>";
			}

		}
?>

<h3><?php echo $fcnt->get_lang('Submitted Events');?></h3>
<?php

	// GET events for the current user
	$events = $eventon_au->frontend->get_user_events($current_user->ID);

	if($events){

		echo "<div class='eventon_actionuser_eventslist'>";
		$evoDateTime = new evo_datetime();

		// for each event user can see
		foreach($events as $event_id=>$evv){

			// initial values
			$DateTime = '';
			$ePmv = get_post_custom($event_id);
			$can_user_edit_event = $fcnt->can_currentuser_edit_event($event_id, $ePmv);

			// edit button html
				$editurl = $eventon_au->frontend->functions->get_custom_url($current_page_link, array('action'=>'edit','eid'=>$event_id));
				$edit_html = (!$can_user_edit_event)? '':"<a class='fa fa-pencil editEvent' href='{$editurl}'></a>";

			// delete button html
				$deleteurl = $eventon_au->frontend->functions->get_custom_url($current_page_link, array('action'=>'delete','eid'=>$event_id));
				$delete_html = (!$fcnt->can_currentuser_delete_event($event_id, $ePmv))?
					'':"<a class='fa fa-trash deleteEvent' href='{$deleteurl}'></a>";

			// Event Date
				$startUNIX = !empty($ePmv['evcal_srow'])? $ePmv['evcal_srow'][0]:false;
				$eUnix = !empty($ePmv['evcal_erow'])? $ePmv['evcal_erow'][0]: $startUNIX;
				if($startUNIX)
					$DateTime = $evoDateTime->get_formatted_smart_time($startUNIX, $eUnix, $ePmv);
				//$time = date_i18n(get_option('date_format').' '.get_option('time_format'), $ePmv);

			// Link
				$link = get_permalink($event_id);

			echo "<div class='evoau_manager_row'>";
			echo "<p><subtitle><a href='{$link}'>".$evv[0]."</a></subtitle>";
				do_action('evoau_manager_row_title', $event_id, $ePmv );
			echo "</p>";

			echo "{$edit_html} {$delete_html}
				<span>".evo_lang('Status').": <em>".__($evv[1],'eventon')."</em></span>
				<span>".evo_lang('Date').": <em>{$DateTime}</em></span>";

			// pluggable
			do_action('evoau_manager_row', $event_id, $ePmv, $current_page_link, $can_user_edit_event );

			echo "</div>";
		}
		echo "</div>";
	}else{
		echo "<p class='evoau_outter_shell'>". evo_lang('You do not have submitted events') . "</p>";
	}
	endif;
?>
</div>
