<?php
/**
 * Plugin Page Feedback Functions
 *
 * @package CAMPUS_DIRECTORY
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_filter('plugin_row_meta', 'campus_directory_plugin_row_meta', 10, 2);
add_filter('plugin_action_links', 'campus_directory_plugin_action_links', 10, 2);
add_action('wp_ajax_campus_directory_send_deactivate_reason', 'campus_directory_send_deactivate_reason');
global $pagenow;
if ('plugins.php' === $pagenow) {
	add_action('admin_footer', 'campus_directory_deactivation_feedback_box');
}
add_action('wp_ajax_campus_directory_show_rateme', 'campus_directory_show_rateme_action');
add_action('admin_notices', 'campus_directory_show_optin');
add_action('admin_post_campus-directory_check_optin', 'campus_directory_check_optin');
function campus_directory_check_optin() {
	if (!empty($_POST['campus-directory_optin'])) {
		if (!function_exists('wp_get_current_user')) {
			require_once (ABSPATH . 'wp-includes/pluggable.php');
		}
		$current_user = wp_get_current_user();
		if (!empty($_POST['optin-email']) && is_email($_POST['optin-email'])) {
			$data['email'] = sanitize_email($_POST['optin-email']);
			$data['plugin_name'] = 'campus_directory';
			$data['plugin_version'] = CAMPUS_DIRECTORY_VERSION;
			$data['wp_version'] = get_bloginfo('version');
			$data['php_version'] = phpversion();
			$data['server'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
			if (!empty($current_user->user_firstname)) {
				$data['first_name'] = $current_user->user_firstname;
			}
			if (!empty($current_user->user_lastname)) {
				$data['last_name'] = $current_user->user_lastname;
			}
			$data['nick_name'] = $current_user->user_nicename;
			$data['site_name'] = get_bloginfo('name');
			$data['site_url'] = home_url();
			$data['language'] = get_bloginfo('language');
			$resp = wp_remote_post('https://api.emarketdesign.com/optin_info.php', array(
				'body' => $data,
			));
			update_option('campus_directory_tracking_optin', 1);
		} else {
			//opt-out
			update_option('campus_directory_tracking_optin', -1);
		}
	} elseif (!empty($_POST['campus-directory_no_optin'])) {
		//opt-out
		update_option('campus_directory_tracking_optin', -1);
	}
	wp_redirect(admin_url('admin.php?page=campus_directory'));
	exit;
}
function campus_directory_show_optin() {
	if (!current_user_can('manage_options')) {
		return;
	}
	if (!get_option('campus_directory_tracking_optin')) {
		$tr_title = __('Please help us improve Campus Directory', 'campus-directory');
		$tr_msg = implode('<br />', array(
			__('Allow eMDPlugins to collect your usage of Campus Directory. This will help you to get a better, more compatible plugin in the future.', 'campus-directory') ,
			__('If you skip this, that\'s okay! Campus Directory will still work just fine.', 'campus-directory') ,
		));
		$tr_link = implode(' ', array(
			'<input type="submit" value="' . __('Do not allow', 'campus-directory') . '" class="button-secondary" name="campus-directory_no_optin" id="campus-directory-do-not-allow-tracking"></input>',
			'<input type="submit" value="' . __('Allow', 'campus-directory') . '" class="button-primary" name="campus-directory_optin" id="campus-directory-allow-tracking"></input>',
		));
		echo '<form method="post" action="' . admin_url('admin-post.php') . '">';
		echo '<input type="hidden" name="action" value="campus-directory_check_optin">';
		echo '<div class="update-nag emd-admin-notice">';
		echo '<h3 class="emd-notice-title"><span class="dashicons dashicons-smiley"></span>' . $tr_title . '<span class="dashicons dashicons-smiley"></span></h3><p class="emd-notice-body">';
		echo $tr_msg . '</p>';
		echo '<p>' . __('Please confirm your email address below to start receiving emails from us.', 'campus-directory') . '</p>';
		$current_user = wp_get_current_user();
		if (!empty($current_user->user_email)) {
			$email = $current_user->user_email;
		} else {
			$email = get_option('admin_email');
		}
		echo '<input id="optin-email" name="optin-email" type="text" value="' . $email . '">';
		echo '<ul class="emd-notice-body nf-red">';
		echo $tr_link . '</ul><div class="emd-permissions"><a href="#" class="emd-perm-trigger"><span class="dashicons dashicons-info" style="text-decoration:none;"></span>' . __('What permissions are being granted?', 'campus-directory') . '</a><ul class="emd-permissions-list" style="display:none;">';
		echo '<li class="emd-permission"><i class="dashicons dashicons-nametag"></i><div><span>' . __('Your Profile Overview', 'campus-directory') . '</span><p>' . __('Name and email address', 'campus-directory') . '</p></div></li>';
		echo '<li class="emd-permission"><i class="dashicons dashicons-admin-settings"></i><div><span>' . __('Your Site Overview', 'campus-directory') . '</span><p>' . __('Site URL, WP version and PHP info', 'campus-directory') . '</p></div></li>';
		echo '<li class="emd-permission"><i class="dashicons dashicons-email-alt"></i><div><span>' . __('Newsletter', 'campus-directory') . '</span><p>' . __('Updates, announcements, marketing, no spam', 'campus-directory') . ', <a href="https://emdplugins.smartlamb.com/subscription-preferences/" target="_blank">unsubscribe anytime</a></p></div></li>';
		echo '</ul></div></div></form>';
	} else {
		//check min entity count if its not -1 then show notice
		$min_trigger = get_option('campus_directory_show_rateme_plugin_min', 5);
		if ($min_trigger != - 1) {
			campus_directory_show_rateme_notice();
		}
	}
}
function campus_directory_show_rateme_action() {
	if (!wp_verify_nonce($_POST['rateme_nonce'], 'campus_directory_rateme_nonce')) {
		exit;
	}
	$min_trigger = get_option('campus_directory_show_rateme_plugin_min', 5);
	if ($min_trigger == - 1) {
		die;
	}
	if (5 === $min_trigger) {
		$response['redirect'] = "https://wordpress.org/support/plugin/campus-directory/reviews/#postform";
		$min_trigger = 10;
	} else {
		$response['redirect'] = "https://emdplugins.com/plugins/campus-directory-wordpress-plugin/";
		$min_trigger = - 1;
	}
	update_option('campus_directory_show_rateme_plugin_min', $min_trigger);
	echo json_encode($response);
	die;
}
function campus_directory_show_rateme_notice() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$min_count = 0;
	$ent_list = get_option('campus_directory_ent_list');
	$min_trigger = get_option('campus_directory_show_rateme_plugin_min', 5);
	$triggerdate = get_option('campus_directory_activation_date', false);
	$installed_date = (!empty($triggerdate) ? $triggerdate : '999999999999999');
	$today = mktime(0, 0, 0, date('m') , date('d') , date('Y'));
	$label = $ent_list['emd_person']['label'];
	$count_posts = wp_count_posts('emd_person');
	if ($count_posts->publish > $min_trigger) {
		$min_count = $count_posts->publish;
	}
	if ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		$message_start = '<div class="emd-show-rateme update-nag success" style="border-radius:40px;">
                        <br>
                        <div>';
		if ($min_count > 5) {
			$message_start.= sprintf(__("Hi, I noticed you just crossed the %d %s milestone - that's awesome!", "campus-directory") , $min_trigger, $label);
		} elseif ($installed_date <= $today) {
			$message_start.= __("Hi, I just noticed you have been using Campus Directory for about a week now - that's awesome!", "campus-directory");
		}
		$message_level1 = __('Give <b>Campus Directory</b> a <span style="color:red" class="dashicons dashicons-heart"></span> 5 star review <span style="color:red" class="dashicons dashicons-heart"></span> to help fellow WordPress users like YOU find it faster! <u>Your 5 star review</u> brings YOU a better FREE product and faster, motivated support when YOU need help.', 'campus-directory');
		$message_level2 = sprintf(__("Would you like to upgrade now to get more out of your %s?", "campus-directory") , $label);
		$message_end = '<br/><br/>
                        <strong>Safiye Duman</strong><br>eMarket Design Cofounder<br><a data-rate-action="twitter" style="text-decoration:none" href="https://twitter.com/safiye_emd" target="_blank"><span class="dashicons dashicons-twitter"></span>@safiye_emd</a>
                        </div>
                        <div style="background-color: #f0f8ff;padding: 0 0 10px 10px;width: 400px;border: 1px solid;border-radius: 10px;margin: 14px 0;"><br><strong>Thank you</strong> <span class="dashicons dashicons-smiley"></span>
                        <ul data-nonce="' . wp_create_nonce('campus_directory_rateme_nonce') . '">';
		$message_end1 = '<li><a data-rate-action="do-rate" data-plugin="campus_directory" href="#">' . __('Yes, I want a better FREE product and faster support', 'campus-directory') . '</a>
       </li>
        <li><a data-rate-action="done-rating" data-plugin="campus_directory" href="#">' . __('I already did - Thank you', 'campus-directory') . '</a></li>
        <li><a data-rate-action="not-enough" data-plugin="campus_directory" href="#">' . __('No, I don\'t want a better FREE product and faster support', 'campus-directory') . '</a></li>';
		$message_end2 = '<li><a data-rate-action="upgrade-now" data-plugin="campus_directory" href="#">' . __('I want to upgrade', 'campus-directory') . '</a>
       </li>
        <li><a data-rate-action="not-enough" data-plugin="campus_directory" href="#">' . __('Maybe later', 'campus-directory') . '</a></li>';
	}
	if ($min_count > 10 && $min_trigger == 10) {
		echo $message_start . '<br><br>' . $message_level2 . ' ' . $message_end . ' ' . $message_end2 . '</ul></div></div>';
	} elseif ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		echo $message_start . '<br><br>' . $message_level1 . ' ' . $message_end . ' ' . $message_end1 . '</ul></div></div>';
	}
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function campus_directory_plugin_row_meta($input, $file) {
	if ($file != 'campus-directory/campus-directory.php') return $input;
	$links = array(
		'<a href="https://docs.emdplugins.com/docs/campus-directory-community-documentation/">' . __('Docs', 'campus-directory') . '</a>',
		'<a href="https://emdplugins.com/plugins/campus-directory-wordpress-plugin/">' . __('Pro Version', 'campus-directory') . '</a>'
	);
	$input = array_merge($input, $links);
	return $input;
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function campus_directory_plugin_action_links($links, $file) {
	if ($file != 'campus-directory/campus-directory.php') return $links;
	foreach ($links as $key => $link) {
		if ('deactivate' === $key) {
			$links[$key] = $link . '<i class="campus_directory-deactivate-slug" data-slug="campus_directory-deactivate-slug"></i>';
		}
	}
	$new_links['settings'] = '<a href="' . admin_url('admin.php?page=campus_directory_settings') . '">' . __('Settings', 'campus-directory') . '</a>';
	$links = array_merge($new_links, $links);
	return $links;
}
function campus_directory_deactivation_feedback_box() {
	$is_long_term_user = true;
	$feedback_vars['utype'] = 0;
	$trigger_time = get_option('campus_directory_activation_date');
	//7 days before trigger
	$activation_time = $trigger_time - 604800;
	$date_diff = time() - $activation_time;
	$date_diff_days = floor($date_diff / (60 * 60 * 24));
	if ($date_diff_days < 2) {
		$feedback_vars['utype'] = 1;
		$is_long_term_user = false;
	}
	wp_enqueue_style("emd-plugin-modal", CAMPUS_DIRECTORY_PLUGIN_URL . 'assets/css/emd-plugin-modal.css');
	$feedback_vars['header'] = __('If you have a moment, please let us know why you are deactivating', 'campus-directory');
	$feedback_vars['submit'] = __('Submit & Deactivate', 'campus-directory');
	$feedback_vars['skip'] = __('Skip & Deactivate', 'campus-directory');
	$feedback_vars['cancel'] = __('Cancel', 'campus-directory');
	$feedback_vars['ask_reason'] = __('Please share the reason so we can improve', 'campus-directory');
	$feedback_vars['ticket'] = __('Would you like to open a support ticket?', 'campus-directory');
	$feedback_vars['emplach'] = __('Please enter your email address.', 'campus-directory');
	$feedback_vars['nonce'] = wp_create_nonce('campus_directory_deactivate_nonce');
	if ($is_long_term_user) {
		$reasons[1] = __('I no longer need the plugin', 'campus-directory');
		$reasons[3] = __('I only needed the plugin for a short period', 'campus-directory');
		$reasons[9] = __('The plugin update did not work as expected', 'campus-directory');
		$reasons[5] = __('The plugin suddenly stopped working', 'campus-directory');
		$reasons[2] = __('I found a better plugin', 'campus-directory');
	} else {
		$reasons[21] = __('I couldn\'t understand how to make it work', 'campus-directory');
		$reasons[22] = __('The plugin is not working', 'campus-directory');
		$reasons[23] = __('It\'s not what I was looking for', 'campus-directory');
		$reasons[24] = __('The plugin didn\'t work as expected', 'campus-directory');
		$reasons[8] = __('The plugin is great, but I need a specific feature that is not currently supported', 'campus-directory');
		$reasons[2] = __('I found a better plugin', 'campus-directory');
	}
	$shuffle_keys = array_keys($reasons);
	shuffle($shuffle_keys);
	foreach ($shuffle_keys as $key) {
		$new_reasons[$key] = $reasons[$key];
	}
	$reasons = $new_reasons;
	//all
	$reasons[6] = __('It\'s a temporary deactivation. I\'m just debugging an issue', 'campus-directory');
	$reasons[7] = __('Other', 'wp-easy-contact');
	$feedback_vars['disclaimer'] = __('No private information is sent during your submission. Thank you very much for your help improving our plugin.', 'campus-directory');
	$feedback_vars['reasons'] = '';
	foreach ($reasons as $key => $reason) {
		$feedback_vars['reasons'].= '<li class="reason';
		if (in_array($key, Array(
			2,
			7,
			8,
			9,
			5,
			22,
			23,
			24
		))) {
			$feedback_vars['reasons'].= ' has-input';
		}
		$feedback_vars['reasons'].= '"';
		switch ($key) {
			case 2:
				$feedback_vars['reasons'].= 'data-input-type="textfield"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('Please share the plugin name.', 'campus-directory') . '"';
			break;
			case 8:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('Please share the feature that you were looking for so that we can develop it in the future releases.', 'campus-directory') . '"';
			break;
			case 9:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('We are sorry to hear that. Please share your previous version number before update, new updated version number and detailed description of what happened.', 'campus-directory') . '"';
			break;
			case 5:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('We are sorry to hear that. Please share the detailed description of what happened.', 'campus-directory') . '"';
			break;
			case 22:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('Please share what didn\'t work so we can fix it in the future releases.', 'campus-directory') . '"';
			break;
			case 23:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('Please share what you were looking for.', 'campus-directory') . '"';
			break;
			case 24:
				$feedback_vars['reasons'].= 'data-input-type="textarea"';
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('Please share what you expected.', 'campus-directory') . '"';
			break;
			default:
			break;
		}
		$feedback_vars['reasons'].= '><label><span>
                                        <input type="radio" name="selected-reason" value="' . $key . '"/>
                                        </span><span>' . $reason . '</span></label></li>';
	}
	wp_enqueue_script('emd-plugin-feedback', CAMPUS_DIRECTORY_PLUGIN_URL . 'assets/js/emd-plugin-feedback.js');
	wp_localize_script("emd-plugin-feedback", 'plugin_feedback_vars', $feedback_vars);
	wp_enqueue_script('campus-directory-feedback', CAMPUS_DIRECTORY_PLUGIN_URL . 'assets/js/campus-directory-feedback.js');
	$campus_directory_vars['plugin'] = 'campus_directory';
	wp_localize_script("campus-directory-feedback", 'campus_directory_vars', $campus_directory_vars);
}
function campus_directory_send_deactivate_reason() {
	if (empty($_POST['deactivate_nonce']) || !isset($_POST['reason_id'])) {
		exit;
	}
	if (!wp_verify_nonce($_POST['deactivate_nonce'], 'campus_directory_deactivate_nonce')) {
		exit;
	}
	$uemail = '';
	$reason_info = isset($_POST['reason_info']) ? sanitize_text_field($_POST['reason_info']) : '';
	if (!empty($_POST['email']) && is_email($_POST['email'])) {
		$uemail = sanitize_email($_POST['email']);
	}
	if (!empty($uemail)) {
		$postfields['uemail'] = $uemail;
	}
	$postfields['utype'] = intval($_POST['utype']);
	$postfields['reason_id'] = intval($_POST['reason_id']);
	$postfields['plugin_name'] = sanitize_text_field($_POST['plugin_name']);
	if (!empty($reason_info)) {
		$postfields['reason_info'] = $reason_info;
	}
	$args = array(
		'body' => $postfields,
		'sslverify' => false,
		'timeout' => 15,
	);
	$resp = wp_remote_post('https://api.emarketdesign.com/deactivate_info.php', $args);
	echo 1;
	exit;
}