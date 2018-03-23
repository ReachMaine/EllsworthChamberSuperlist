<?php
/** 
 * Event Manager for frontend user submitted events
 * @version 2.0.3
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

	// zig xout echo "<h2>".$fcnt->get_lang('My Eventon Events Manager')."</h2>";
	if(!is_user_logged_in()){
		echo "<p>".$fcnt->get_lang('Login required to manage your submitted events')." <br/><a href='".wp_login_url($current_page_link)."' class='evcal_btn evoau'><i class='fa fa-user'></i> ".$fcnt->get_lang('Login Now')."</a></p>";
		return;
	}	
?>

<?php /* zig xout <p><?php echo $fcnt->get_lang('Hello');?> <?php echo $current_user->display_name?>. <?php echo $fcnt->get_lang('From your event manager dashboard you can view your submitted events and manage them in here');?></p> */ ?>

<?php
	// Edit an event
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit' && !empty($_REQUEST['eid'])):
		$BACKLINK = str_replace('action=edit', '', $current_page_link);

		// check if the user have permission to edit this event
		if(!$fcnt->can_currentuser_edit_event($_REQUEST['eid'])){
			echo "<script type='text/javascript'> window.location = '{$BACKLINK}';</script>";
		}

		echo "<p><a class='evcal_btn evoau' href='".($BACKLINK)."'><i class='fa fa-angle-left'></i> ".$fcnt->get_lang('Back to my events')."</a></p>";
		$eventon_au->frontend->get_submission_form($_REQUEST['eid'], $atts);

	elseif( isset($_REQUEST['customaction']) && !empty($_REQUEST['eid']) ):

		// Passing data
			$BACKLINK = str_replace('action=edit', '', $current_page_link);
			$backto_events_html = "<p><a class='evcal_btn evoau' href='".($BACKLINK)."'><i class='fa fa-angle-left'></i> ".$fcnt->get_lang('Back to my events')."</a></p>";

		// check if the user have permission to edit this event
		if(!$fcnt->can_currentuser_edit_event($_REQUEST['eid'])){
			echo "<script type='text/javascript'> window.location = '{$BACKLINK}';</script>";
		}
		do_action('evoauem_custom_action', $backto_events_html);

	else:

		// trash event
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && !empty($_REQUEST['eid'])){
			$status =	$fcnt->trash_event($_REQUEST['eid']);
			$eventname = get_the_title($_REQUEST['eid']);
			echo "<h3 class='evoauem_del_msg' style='background-color: #B3D488;color: #fff;'>".( ($status)? $eventname.' Deleted!':'Could not delete event.')."</h3>";
		}
?>

<h3><?php echo $fcnt->get_lang('Submitted Events');?></h3>
<?php	
	$events = $eventon_au->frontend->get_user_events($current_user->ID);	

	if($events){

		echo "<div class='eventon_actionuser_eventslist'>";
		$evoDateTime = new evo_datetime();
		foreach($events as $eventid=>$evv){
			// edit button html
				$editurl = $eventon_au->frontend->functions->get_custom_url($current_page_link, array('action'=>'edit','eid'=>$eventid))
;				$edit_html = (!$fcnt->can_edit_event($eventid))?'':"<a class='fa fa-pencil editEvent' href='{$editurl}'></a>";
			// delete button html
				$deleteurl = $eventon_au->frontend->functions->get_custom_url($current_page_link, array('action'=>'delete','eid'=>$eventid));
				$delete_html = (!$fcnt->can_delete_event())?'':"<a class='fa fa-trash deleteEvent' href='{$deleteurl}'></a>";

			// Event Date				
				$ePmv = get_post_custom($eventid);
				$eUnix = !empty($ePmv['evcal_erow'])? $ePmv['evcal_erow'][0]: $ePmv['svcal_erow'][0];
				$DateTime = $evoDateTime->get_formatted_smart_time($ePmv['evcal_srow'][0], $eUnix, $ePmv);
				//$time = date_i18n(get_option('date_format').' '.get_option('time_format'), $ePmv);

			echo "<div class='evoau_manager_row'>";
			echo "<p><subtitle>".$evv[0]."</subtitle>";
				do_action('evoau_manager_row_title', $eventid, $ePmv );
			echo "</p>";
			
			echo "{$edit_html} {$delete_html}
				<span>".evo_lang('Status').": <em>".__($evv[1],'eventon')."</em></span>
				<span>".evo_lang('Date').": <em>{$DateTime}</em></span>";

			do_action('evoau_manager_row', $eventid, $ePmv, $current_page_link );
			echo "</div>";
		}
		echo "</div>";
	}
	endif;
?>