<?php
/**
 * Entity Related Shortcode Functions
 *
 * @package CAMPUS_DIRECTORY
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Shortcode function
 *
 * @since WPAS 4.0
 * @param array $atts
 * @param array $args
 * @param string $form_name
 * @param int $pageno
 *
 * @return html
 */
function campus_directory_people_grid_set_shc($atts, $args = Array() , $form_name = '', $pageno = 1, $shc_page_count = 0) {
	global $shc_count;
	if ($shc_page_count != 0) {
		$shc_count = $shc_page_count;
	} else {
		if (empty($shc_count)) {
			$shc_count = 1;
		} else {
			$shc_count++;
		}
	}
	$fields = Array(
		'app' => 'campus_directory',
		'class' => 'emd_person',
		'shc' => 'people_grid',
		'shc_count' => $shc_count,
		'form' => $form_name,
		'has_pages' => false,
		'pageno' => $pageno,
		'pgn_class' => '',
		'theme' => 'na',
		'hier' => 0,
		'hier_type' => 'ul',
		'hier_depth' => - 1,
		'hier_class' => '',
		'has_json' => 0,
	);
	$args_default = array(
		'posts_per_page' => '-1',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'filter' => ''
	);
	return emd_shc_get_layout_list($atts, $args, $args_default, $fields);
}
add_shortcode('people_grid', 'people_grid_list');
function people_grid_list($atts) {
	$show_shc = 1;
	$show_shc = apply_filters('emd_show_shc', $show_shc, 'people_grid');
	if ($show_shc == 1) {
		wp_enqueue_style('emd-pagination');
		add_action('wp_footer', 'campus_directory_enq_allview');
		campus_directory_enq_custom_css_js();
		$list = campus_directory_people_grid_set_shc($atts);
	} else {
		$list = '<div class="alert alert-info not-authorized">You are not authorized to access this content.</div>';
		$list = apply_filters('emd_no_access_msg_shc', $list, 'people_grid');
	}
	return $list;
}
/**
 * Shortcode function
 *
 * @since WPAS 4.0
 * @param array $atts
 * @param array $args
 * @param string $form_name
 * @param int $pageno
 *
 * @return html
 */
function campus_directory_search_people_set_shc($atts, $args = Array() , $form_name = '', $pageno = 1, $shc_page_count = 0) {
	global $shc_count;
	if ($shc_page_count != 0) {
		$shc_count = $shc_page_count;
	} else {
		if (empty($shc_count)) {
			$shc_count = 1;
		} else {
			$shc_count++;
		}
	}
	$fields = Array(
		'app' => 'campus_directory',
		'class' => 'emd_person',
		'shc' => 'search_people',
		'shc_count' => $shc_count,
		'form' => $form_name,
		'has_pages' => false,
		'pageno' => $pageno,
		'pgn_class' => '',
		'theme' => 'bs',
		'hier' => 0,
		'hier_type' => 'ul',
		'hier_depth' => - 1,
		'hier_class' => '',
		'has_json' => 0,
	);
	$args_default = array(
		'posts_per_page' => '-1',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'filter' => ''
	);
	return emd_shc_get_layout_list($atts, $args, $args_default, $fields);
}
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode', 11);