<?php
/**
 * Plugin Functions
 * @package     EMD
 * @since       5.3
 */
if (!defined('ABSPATH')) exit;
add_action('emd_ext_set_conf', 'campus_directory_set_vcard_list');
add_action('emd_ext_reset_conf', 'campus_directory_reset_vcard_list');
function campus_directory_reset_vcard_list($app) {
	delete_option('campus_directory_vcard_field_list');
}
function campus_directory_set_vcard_list($app) {
	$vcard_list['fname'] = Array(
		'type' => 'blt',
		'key' => 'blt_title'
	);
	$vcard_list['n'] = Array(
		'type' => 'blt',
		'key' => 'blt_title'
	);
	$vcard_list['fn'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_fname'
	);
	$vcard_list['title'] = Array(
		'type' => 'tax',
		'key' => 'person_title'
	);
	$vcard_list['adr'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_address'
	);
	$vcard_list['email'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_email'
	);
	$vcard_list['photo'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_photo'
	);
	$vcard_list['wphone'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_phone'
	);
	$vcard_list['twitter'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_twitter'
	);
	$vcard_list['linkedin'] = Array(
		'type' => 'attr',
		'key' => 'emd_person_linkedin'
	);
	$vcard_list['dept'] = Array(
		'type' => 'tax',
		'key' => 'person_area'
	);
	$vcard_list['org'] = Array(
		'type' => 'glob',
		'key' => 'glb_vcard_org_name'
	);
	update_option("campus_directory_vcard_field_list", $vcard_list);
}
?>