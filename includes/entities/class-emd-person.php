<?php
/**
 * Entity Class
 *
 * @package CAMPUS_DIRECTORY
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Person Class
 * @since WPAS 4.0
 */
class Emd_Person extends Emd_Entity {
	protected $post_type = 'emd_person';
	protected $textdomain = 'campus-directory';
	protected $sing_label;
	protected $plural_label;
	protected $menu_entity;
	protected $id;
	/**
	 * Initialize entity class
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function __construct() {
		add_action('init', array(
			$this,
			'set_filters'
		) , 1);
		add_action('admin_init', array(
			$this,
			'set_metabox'
		));
		add_action('save_post', array(
			$this,
			'change_title'
		) , 99, 2);
		add_filter('post_updated_messages', array(
			$this,
			'updated_messages'
		));
		add_action('admin_menu', array(
			$this,
			'add_menu_link'
		));
		add_action('admin_head-edit.php', array(
			$this,
			'add_opt_button'
		));
		$is_adv_filt_ext = apply_filters('emd_adv_filter_on', 0);
		if ($is_adv_filt_ext === 0) {
			add_action('manage_emd_person_posts_custom_column', array(
				$this,
				'custom_columns'
			) , 10, 2);
			add_filter('manage_emd_person_posts_columns', array(
				$this,
				'column_headers'
			));
		}
		add_filter('is_protected_meta', array(
			$this,
			'hide_attrs'
		) , 10, 2);
		add_filter('postmeta_form_keys', array(
			$this,
			'cust_keys'
		) , 10, 2);
		add_filter('emd_get_cust_fields', array(
			$this,
			'get_cust_fields'
		) , 10, 2);
		add_filter('post_row_actions', array(
			$this,
			'duplicate_link'
		) , 10, 2);
		add_action('admin_action_emd_duplicate_entity', array(
			$this,
			'duplicate_entity'
		));
	}
	public function change_title_disable_emd_temp($title, $id) {
		$post = get_post($id);
		if ($this->post_type == $post->post_type && (!empty($this->id) && $this->id == $id)) {
			return '';
		}
		return $title;
	}
	/**
	 * Get custom attribute list
	 * @since WPAS 4.9
	 *
	 * @param array $cust_fields
	 * @param string $post_type
	 *
	 * @return array $new_keys
	 */
	public function get_cust_fields($cust_fields, $post_type) {
		global $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
               FROM $wpdb->postmeta a
               WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
			if (!empty($keys)) {
				foreach ($keys as $i => $mkey) {
					if (!preg_match('/^(_|wpas_|emd_)/', $mkey)) {
						$ckey = str_replace('-', '_', sanitize_title($mkey));
						$cust_fields[$ckey] = $mkey;
					}
				}
			}
		}
		return $cust_fields;
	}
	/**
	 * Set new custom attributes dropdown in admin edit entity
	 * @since WPAS 4.9
	 *
	 * @param array $keys
	 * @param object $post
	 *
	 * @return array $keys
	 */
	public function cust_keys($keys, $post) {
		global $post_type, $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
                FROM $wpdb->postmeta a
                WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
		}
		return $keys;
	}
	/**
	 * Hide all emd attributes
	 * @since WPAS 4.9
	 *
	 * @param bool $protected
	 * @param string $meta_key
	 *
	 * @return bool $protected
	 */
	public function hide_attrs($protected, $meta_key) {
		if (preg_match('/^(emd_|wpas_)/', $meta_key)) return true;
		if (!empty($this->boxes)) {
			foreach ($this->boxes as $mybox) {
				if (!empty($mybox['fields'])) {
					foreach ($mybox['fields'] as $fkey => $mybox_field) {
						if ($meta_key == $fkey) return true;
					}
				}
			}
		}
		return $protected;
	}
	/**
	 * Get column header list in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function column_headers($columns) {
		$ent_list = get_option(str_replace("-", "_", $this->textdomain) . '_ent_list');
		if (!empty($ent_list[$this->post_type]['featured_img'])) {
			$columns['featured_img'] = __('Featured Image', $this->textdomain);
		}
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if (!in_array($fkey, Array(
					'wpas_form_name',
					'wpas_form_submitted_by',
					'wpas_form_submitted_ip'
				)) && !in_array($mybox_field['type'], Array(
					'textarea',
					'wysiwyg'
				)) && $mybox_field['list_visible'] == 1) {
					$columns[$fkey] = $mybox_field['name'];
				}
			}
		}
		$taxonomies = get_object_taxonomies($this->post_type, 'objects');
		if (!empty($taxonomies)) {
			$tax_list = get_option(str_replace("-", "_", $this->textdomain) . '_tax_list');
			foreach ($taxonomies as $taxonomy) {
				if (!empty($tax_list[$this->post_type][$taxonomy->name]) && $tax_list[$this->post_type][$taxonomy->name]['list_visible'] == 1) {
					$columns[$taxonomy->name] = $taxonomy->label;
				}
			}
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list)) {
			foreach ($rel_list as $krel => $rel) {
				if ($rel['from'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'from'
				))) {
					$columns[$krel] = $rel['from_title'];
				} elseif ($rel['to'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'to'
				))) {
					$columns[$krel] = $rel['to_title'];
				}
			}
		}
		return $columns;
	}
	/**
	 * Get custom column values in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param int $column_id
	 * @param int $post_id
	 *
	 * @return string $value
	 */
	public function custom_columns($column_id, $post_id) {
		if (taxonomy_exists($column_id) == true) {
			$terms = get_the_terms($post_id, $column_id);
			$ret = array();
			if (!empty($terms)) {
				foreach ($terms as $term) {
					$url = add_query_arg(array(
						'post_type' => $this->post_type,
						'term' => $term->slug,
						'taxonomy' => $column_id
					) , admin_url('edit.php'));
					$a_class = preg_replace('/^emd_/', '', $this->post_type);
					$ret[] = sprintf('<a href="%s"  class="' . $a_class . '-tax ' . $term->slug . '">%s</a>', $url, $term->name);
				}
			}
			echo implode(', ', $ret);
			return;
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list) && !empty($rel_list[$column_id])) {
			$rel_arr = $rel_list[$column_id];
			if ($rel_arr['from'] == $this->post_type) {
				$other_ptype = $rel_arr['to'];
			} elseif ($rel_arr['to'] == $this->post_type) {
				$other_ptype = $rel_arr['from'];
			}
			$column_id = str_replace('rel_', '', $column_id);
			if (function_exists('p2p_type') && p2p_type($column_id)) {
				$rel_args = apply_filters('emd_ext_p2p_add_query_vars', array(
					'posts_per_page' => - 1
				) , Array(
					$other_ptype
				));
				$connected = p2p_type($column_id)->get_connected($post_id, $rel_args);
				$ptype_obj = get_post_type_object($this->post_type);
				$edit_cap = $ptype_obj->cap->edit_posts;
				$ret = array();
				if (empty($connected->posts)) return '&ndash;';
				foreach ($connected->posts as $myrelpost) {
					$rel_title = get_the_title($myrelpost->ID);
					$rel_title = apply_filters('emd_ext_p2p_connect_title', $rel_title, $myrelpost, '');
					$url = get_permalink($myrelpost->ID);
					$url = apply_filters('emd_ext_connected_ptype_url', $url, $myrelpost, $edit_cap);
					$ret[] = sprintf('<a href="%s" title="%s" target="_blank">%s</a>', $url, $rel_title, $rel_title);
				}
				echo implode(', ', $ret);
				return;
			}
		}
		$value = get_post_meta($post_id, $column_id, true);
		$type = "";
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($fkey == $column_id) {
					$type = $mybox_field['type'];
					break;
				}
			}
		}
		if ($column_id == 'featured_img') {
			$type = 'featured_img';
		}
		switch ($type) {
			case 'featured_img':
				$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id) , 'thumbnail');
				if (!empty($thumb_url)) {
					$value = "<img style='max-width:100%;height:auto;' src='" . $thumb_url[0] . "' >";
				}
			break;
			case 'plupload_image':
			case 'image':
			case 'thickbox_image':
				$image_list = emd_mb_meta($column_id, 'type=image');
				$value = "";
				if (!empty($image_list)) {
					$myimage = current($image_list);
					$value = "<img style='max-width:100%;height:auto;' src='" . $myimage['url'] . "' >";
				}
			break;
			case 'user':
			case 'user-adv':
				$user_id = emd_mb_meta($column_id);
				if (!empty($user_id)) {
					$user_info = get_userdata($user_id);
					$value = $user_info->display_name;
				}
			break;
			case 'file':
				$file_list = emd_mb_meta($column_id, 'type=file');
				if (!empty($file_list)) {
					$value = "";
					foreach ($file_list as $myfile) {
						$fsrc = wp_mime_type_icon($myfile['ID']);
						$value.= "<a style='margin:5px;' href='" . $myfile['url'] . "' target='_blank'><img src='" . $fsrc . "' title='" . $myfile['name'] . "' width='20' /></a>";
					}
				}
			break;
			case 'radio':
			case 'checkbox_list':
			case 'select':
			case 'select_advanced':
				$value = emd_get_attr_val(str_replace("-", "_", $this->textdomain) , $post_id, $this->post_type, $column_id);
			break;
			case 'checkbox':
				if ($value == 1) {
					$value = '<span class="dashicons dashicons-yes"></span>';
				} elseif ($value == 0) {
					$value = '<span class="dashicons dashicons-no-alt"></span>';
				}
			break;
			case 'rating':
				$value = apply_filters('emd_get_rating_value', $value, Array(
					'meta' => $column_id
				) , $post_id);
			break;
		}
		if (is_array($value)) {
			$value = "<div class='clonelink'>" . implode("</div><div class='clonelink'>", $value) . "</div>";
		}
		echo $value;
	}
	/**
	 * Register post type and taxonomies and set initial values for taxs
	 *
	 * @since WPAS 4.0
	 *
	 */
	public static function register() {
		$labels = array(
			'name' => __('People', 'campus-directory') ,
			'singular_name' => __('Person', 'campus-directory') ,
			'add_new' => __('Add New', 'campus-directory') ,
			'add_new_item' => __('Add New Person', 'campus-directory') ,
			'edit_item' => __('Edit Person', 'campus-directory') ,
			'new_item' => __('New Person', 'campus-directory') ,
			'all_items' => __('All People', 'campus-directory') ,
			'view_item' => __('View Person', 'campus-directory') ,
			'search_items' => __('Search People', 'campus-directory') ,
			'not_found' => __('No People Found', 'campus-directory') ,
			'not_found_in_trash' => __('No People Found In Trash', 'campus-directory') ,
			'menu_name' => __('People', 'campus-directory') ,
		);
		$ent_map_list = get_option('campus_directory_ent_map_list', Array());
		$myrole = emd_get_curr_usr_role('campus_directory');
		if (!empty($ent_map_list['emd_person']['rewrite'])) {
			$rewrite = $ent_map_list['emd_person']['rewrite'];
		} else {
			$rewrite = 'emd_person';
		}
		$supports = Array();
		register_post_type('emd_person', array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'description' => __('Any person who is a faculty, graduate or undergraduate student, staff.', 'campus-directory') ,
			'show_in_menu' => true,
			'menu_position' => 6,
			'has_archive' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'slug' => $rewrite
			) ,
			'can_export' => true,
			'show_in_rest' => false,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-welcome-learn-more',
			'map_meta_cap' => 'false',
			'taxonomies' => array() ,
			'capability_type' => 'post',
			'supports' => $supports,
		));
		$tax_settings = get_option('campus_directory_tax_settings', Array());
		$myrole = emd_get_curr_usr_role('campus_directory');
		$directory_tag_nohr_labels = array(
			'name' => __('Directory Tags', 'campus-directory') ,
			'singular_name' => __('Directory Tag', 'campus-directory') ,
			'search_items' => __('Search Directory Tags', 'campus-directory') ,
			'popular_items' => __('Popular Directory Tags', 'campus-directory') ,
			'all_items' => __('All', 'campus-directory') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Directory Tag', 'campus-directory') ,
			'update_item' => __('Update Directory Tag', 'campus-directory') ,
			'add_new_item' => __('Add New Directory Tag', 'campus-directory') ,
			'new_item_name' => __('Add New Directory Tag Name', 'campus-directory') ,
			'separate_items_with_commas' => __('Seperate Directory Tags with commas', 'campus-directory') ,
			'add_or_remove_items' => __('Add or Remove Directory Tags', 'campus-directory') ,
			'choose_from_most_used' => __('Choose from the most used Directory Tags', 'campus-directory') ,
			'menu_name' => __('Directory Tags', 'campus-directory') ,
		);
		if (empty($tax_settings['directory_tag']['hide']) || (!empty($tax_settings['directory_tag']['hide']) && $tax_settings['directory_tag']['hide'] != 'hide')) {
			if (!empty($tax_settings['directory_tag']['rewrite'])) {
				$rewrite = $tax_settings['directory_tag']['rewrite'];
			} else {
				$rewrite = 'directory_tag';
			}
			$targs = array(
				'hierarchical' => false,
				'labels' => $directory_tag_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'show_in_rest' => false,
				'capabilities' => array(
					'manage_terms' => 'manage_directory_tag',
					'edit_terms' => 'edit_directory_tag',
					'delete_terms' => 'delete_directory_tag',
					'assign_terms' => 'assign_directory_tag'
				) ,
			);
			if ($myrole != 'administrator' && !empty($tax_settings['directory_tag']['edit'][$myrole]) && $tax_settings['directory_tag']['edit'][$myrole] != 'edit') {
				$targs['meta_box_cb'] = false;
			}
			register_taxonomy('directory_tag', array(
				'emd_person'
			) , $targs);
		}
		$person_title_nohr_labels = array(
			'name' => __('Titles', 'campus-directory') ,
			'singular_name' => __('Title', 'campus-directory') ,
			'search_items' => __('Search Titles', 'campus-directory') ,
			'popular_items' => __('Popular Titles', 'campus-directory') ,
			'all_items' => __('All', 'campus-directory') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Title', 'campus-directory') ,
			'update_item' => __('Update Title', 'campus-directory') ,
			'add_new_item' => __('Add New Title', 'campus-directory') ,
			'new_item_name' => __('Add New Title Name', 'campus-directory') ,
			'separate_items_with_commas' => __('Seperate Titles with commas', 'campus-directory') ,
			'add_or_remove_items' => __('Add or Remove Titles', 'campus-directory') ,
			'choose_from_most_used' => __('Choose from the most used Titles', 'campus-directory') ,
			'menu_name' => __('Titles', 'campus-directory') ,
		);
		if (empty($tax_settings['person_title']['hide']) || (!empty($tax_settings['person_title']['hide']) && $tax_settings['person_title']['hide'] != 'hide')) {
			if (!empty($tax_settings['person_title']['rewrite'])) {
				$rewrite = $tax_settings['person_title']['rewrite'];
			} else {
				$rewrite = 'person_title';
			}
			$targs = array(
				'hierarchical' => false,
				'labels' => $person_title_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'show_in_rest' => false,
				'capabilities' => array(
					'manage_terms' => 'manage_person_title',
					'edit_terms' => 'edit_person_title',
					'delete_terms' => 'delete_person_title',
					'assign_terms' => 'assign_person_title'
				) ,
			);
			if ($myrole != 'administrator' && !empty($tax_settings['person_title']['edit'][$myrole]) && $tax_settings['person_title']['edit'][$myrole] != 'edit') {
				$targs['meta_box_cb'] = false;
			}
			register_taxonomy('person_title', array(
				'emd_person'
			) , $targs);
		}
		$person_rareas_nohr_labels = array(
			'name' => __('Research Areas', 'campus-directory') ,
			'singular_name' => __('Research Area', 'campus-directory') ,
			'search_items' => __('Search Research Areas', 'campus-directory') ,
			'popular_items' => __('Popular Research Areas', 'campus-directory') ,
			'all_items' => __('All', 'campus-directory') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Research Area', 'campus-directory') ,
			'update_item' => __('Update Research Area', 'campus-directory') ,
			'add_new_item' => __('Add New Research Area', 'campus-directory') ,
			'new_item_name' => __('Add New Research Area Name', 'campus-directory') ,
			'separate_items_with_commas' => __('Seperate Research Areas with commas', 'campus-directory') ,
			'add_or_remove_items' => __('Add or Remove Research Areas', 'campus-directory') ,
			'choose_from_most_used' => __('Choose from the most used Research Areas', 'campus-directory') ,
			'menu_name' => __('Research Areas', 'campus-directory') ,
		);
		if (empty($tax_settings['person_rareas']['hide']) || (!empty($tax_settings['person_rareas']['hide']) && $tax_settings['person_rareas']['hide'] != 'hide')) {
			if (!empty($tax_settings['person_rareas']['rewrite'])) {
				$rewrite = $tax_settings['person_rareas']['rewrite'];
			} else {
				$rewrite = 'person_rareas';
			}
			$targs = array(
				'hierarchical' => false,
				'labels' => $person_rareas_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'show_in_rest' => false,
				'capabilities' => array(
					'manage_terms' => 'manage_person_rareas',
					'edit_terms' => 'edit_person_rareas',
					'delete_terms' => 'delete_person_rareas',
					'assign_terms' => 'assign_person_rareas'
				) ,
			);
			if ($myrole != 'administrator' && !empty($tax_settings['person_rareas']['edit'][$myrole]) && $tax_settings['person_rareas']['edit'][$myrole] != 'edit') {
				$targs['meta_box_cb'] = false;
			}
			register_taxonomy('person_rareas', array(
				'emd_person'
			) , $targs);
		}
		$person_location_nohr_labels = array(
			'name' => __('Locations', 'campus-directory') ,
			'singular_name' => __('Location', 'campus-directory') ,
			'search_items' => __('Search Locations', 'campus-directory') ,
			'popular_items' => __('Popular Locations', 'campus-directory') ,
			'all_items' => __('All', 'campus-directory') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Location', 'campus-directory') ,
			'update_item' => __('Update Location', 'campus-directory') ,
			'add_new_item' => __('Add New Location', 'campus-directory') ,
			'new_item_name' => __('Add New Location Name', 'campus-directory') ,
			'separate_items_with_commas' => __('Seperate Locations with commas', 'campus-directory') ,
			'add_or_remove_items' => __('Add or Remove Locations', 'campus-directory') ,
			'choose_from_most_used' => __('Choose from the most used Locations', 'campus-directory') ,
			'menu_name' => __('Locations', 'campus-directory') ,
		);
		if (empty($tax_settings['person_location']['hide']) || (!empty($tax_settings['person_location']['hide']) && $tax_settings['person_location']['hide'] != 'hide')) {
			if (!empty($tax_settings['person_location']['rewrite'])) {
				$rewrite = $tax_settings['person_location']['rewrite'];
			} else {
				$rewrite = 'person_location';
			}
			$targs = array(
				'hierarchical' => false,
				'labels' => $person_location_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'show_in_rest' => false,
				'capabilities' => array(
					'manage_terms' => 'manage_person_location',
					'edit_terms' => 'edit_person_location',
					'delete_terms' => 'delete_person_location',
					'assign_terms' => 'assign_person_location'
				) ,
			);
			if ($myrole != 'administrator' && !empty($tax_settings['person_location']['edit'][$myrole]) && $tax_settings['person_location']['edit'][$myrole] != 'edit') {
				$targs['meta_box_cb'] = false;
			}
			register_taxonomy('person_location', array(
				'emd_person'
			) , $targs);
		}
		$person_area_nohr_labels = array(
			'name' => __('Academic Areas', 'campus-directory') ,
			'singular_name' => __('Academic Area', 'campus-directory') ,
			'search_items' => __('Search Academic Areas', 'campus-directory') ,
			'popular_items' => __('Popular Academic Areas', 'campus-directory') ,
			'all_items' => __('All', 'campus-directory') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Academic Area', 'campus-directory') ,
			'update_item' => __('Update Academic Area', 'campus-directory') ,
			'add_new_item' => __('Add New Academic Area', 'campus-directory') ,
			'new_item_name' => __('Add New Academic Area Name', 'campus-directory') ,
			'separate_items_with_commas' => __('Seperate Academic Areas with commas', 'campus-directory') ,
			'add_or_remove_items' => __('Add or Remove Academic Areas', 'campus-directory') ,
			'choose_from_most_used' => __('Choose from the most used Academic Areas', 'campus-directory') ,
			'menu_name' => __('Academic Areas', 'campus-directory') ,
		);
		if (empty($tax_settings['person_area']['hide']) || (!empty($tax_settings['person_area']['hide']) && $tax_settings['person_area']['hide'] != 'hide')) {
			if (!empty($tax_settings['person_area']['rewrite'])) {
				$rewrite = $tax_settings['person_area']['rewrite'];
			} else {
				$rewrite = 'person_area';
			}
			$targs = array(
				'hierarchical' => false,
				'labels' => $person_area_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'show_in_rest' => false,
				'capabilities' => array(
					'manage_terms' => 'manage_person_area',
					'edit_terms' => 'edit_person_area',
					'delete_terms' => 'delete_person_area',
					'assign_terms' => 'assign_person_area'
				) ,
			);
			if ($myrole != 'administrator' && !empty($tax_settings['person_area']['edit'][$myrole]) && $tax_settings['person_area']['edit'][$myrole] != 'edit') {
				$targs['meta_box_cb'] = false;
			}
			register_taxonomy('person_area', array(
				'emd_person'
			) , $targs);
		}
	}
	/**
	 * Set metabox fields,labels,filters, comments, relationships if exists
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function set_filters() {
		do_action('emd_ext_class_init', $this);
		$search_args = Array();
		$filter_args = Array();
		$this->sing_label = __('Person', 'campus-directory');
		$this->plural_label = __('People', 'campus-directory');
		$this->menu_entity = 'emd_person';
		$this->boxes['emd_person_info_emd_person_0'] = array(
			'id' => 'emd_person_info_emd_person_0',
			'title' => __('Person Info', 'campus-directory') ,
			'app_name' => 'campus_directory',
			'pages' => array(
				'emd_person'
			) ,
			'context' => 'normal',
		);
		$this->boxes['emd_cust_field_meta_box'] = array(
			'id' => 'emd_cust_field_meta_box',
			'title' => __('Custom Fields', 'campus-directory') ,
			'app_name' => 'campus_directory',
			'pages' => array(
				'emd_person'
			) ,
			'context' => 'normal',
			'priority' => 'low'
		);
		list($search_args, $filter_args) = $this->set_args_boxes();
		if (empty($this->boxes['emd_cust_field_meta_box']['fields'])) {
			unset($this->boxes['emd_cust_field_meta_box']);
		}
		if (!post_type_exists($this->post_type) || in_array($this->post_type, Array(
			'post',
			'page'
		))) {
			self::register();
		}
		do_action('emd_set_adv_filtering', $this->post_type, $search_args, $this->boxes, $filter_args, $this->textdomain, $this->plural_label);
		add_action('admin_notices', array(
			$this,
			'show_lite_filters'
		));
		$ent_map_list = get_option(str_replace('-', '_', $this->textdomain) . '_ent_map_list');
		if (!function_exists('p2p_register_connection_type')) {
			return;
		}
		$rel_list = get_option(str_replace('-', '_', $this->textdomain) . '_rel_list');
		$myrole = emd_get_curr_usr_role('campus_directory');
		if (empty($ent_map_list['emd_person']['hide_rels']['rel_supervisor']) || $ent_map_list['emd_person']['hide_rels']['rel_supervisor'] != 'hide') {
			if ($myrole != 'administrator' && !empty($ent_map_list['emd_person']['edit_rels'][$myrole]['rel_supervisor']) && $ent_map_list['emd_person']['edit_rels'][$myrole]['rel_supervisor'] != 'edit') {
				$admin_box = 'none';
			} else {
				$admin_box = 'to';
			}
			$rel_fields = Array();
			p2p_register_connection_type(array(
				'name' => 'supervisor',
				'from' => 'emd_person',
				'to' => 'emd_person',
				'sortable' => 'any',
				'reciprocal' => false,
				'cardinality' => 'many-to-many',
				'title' => array(
					'from' => __('Advisees', 'campus-directory') ,
					'to' => __('Advisor', 'campus-directory')
				) ,
				'from_labels' => array(
					'singular_name' => __('Person', 'campus-directory') ,
					'search_items' => __('Search People', 'campus-directory') ,
					'not_found' => __('No People found.', 'campus-directory') ,
				) ,
				'to_labels' => array(
					'singular_name' => __('Person', 'campus-directory') ,
					'search_items' => __('Search People', 'campus-directory') ,
					'not_found' => __('No People found.', 'campus-directory') ,
				) ,
				'fields' => $rel_fields,
				'admin_box' => $admin_box,
			));
		}
		$myrole = emd_get_curr_usr_role('campus_directory');
		if (empty($ent_map_list['emd_person']['hide_rels']['rel_support_staff']) || $ent_map_list['emd_person']['hide_rels']['rel_support_staff'] != 'hide') {
			if ($myrole != 'administrator' && !empty($ent_map_list['emd_person']['edit_rels'][$myrole]['rel_support_staff']) && $ent_map_list['emd_person']['edit_rels'][$myrole]['rel_support_staff'] != 'edit') {
				$admin_box = 'none';
			} else {
				$admin_box = 'from';
			}
			$rel_fields = Array();
			p2p_register_connection_type(array(
				'name' => 'support_staff',
				'from' => 'emd_person',
				'to' => 'emd_person',
				'sortable' => 'any',
				'reciprocal' => false,
				'cardinality' => 'many-to-many',
				'title' => array(
					'from' => __('Support Staff', 'campus-directory') ,
					'to' => __('Supported Faculty', 'campus-directory')
				) ,
				'from_labels' => array(
					'singular_name' => __('Person', 'campus-directory') ,
					'search_items' => __('Search People', 'campus-directory') ,
					'not_found' => __('No People found.', 'campus-directory') ,
				) ,
				'to_labels' => array(
					'singular_name' => __('Person', 'campus-directory') ,
					'search_items' => __('Search People', 'campus-directory') ,
					'not_found' => __('No People found.', 'campus-directory') ,
				) ,
				'fields' => $rel_fields,
				'admin_box' => $admin_box,
			));
		}
	}
	/**
	 * Initialize metaboxes
	 * @since WPAS 4.5
	 *
	 */
	public function set_metabox() {
		if (class_exists('EMD_Meta_Box') && is_array($this->boxes)) {
			foreach ($this->boxes as $meta_box) {
				new EMD_Meta_Box($meta_box);
			}
		}
	}
	/**
	 * Change content for created frontend views
	 * @since WPAS 4.0
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function change_content($content) {
		global $post;
		$layout = "";
		$this->id = $post->ID;
		$tools = get_option('campus_directory_tools');
		if (!empty($tools['disable_emd_templates'])) {
			add_filter('the_title', array(
				$this,
				'change_title_disable_emd_temp'
			) , 10, 2);
		}
		if (get_post_type() == $this->post_type && is_single()) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'single', 'emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_post_type_archive('emd_person')) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'archive', 'emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_tax('person_area') && $post->post_type == $this->post_type) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'taxonomy', 'person-area-emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_tax('person_title') && $post->post_type == $this->post_type) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'taxonomy', 'person-title-emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_tax('person_rareas') && $post->post_type == $this->post_type) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'taxonomy', 'person-rareas-emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_tax('person_location') && $post->post_type == $this->post_type) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'taxonomy', 'person-location-emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		} elseif (is_tax('directory_tag') && $post->post_type == $this->post_type) {
			ob_start();
			do_action('emd_single_before_content', $this->textdomain, $this->post_type);
			emd_get_template_part($this->textdomain, 'taxonomy', 'directory-tag-emd-person');
			do_action('emd_single_after_content', $this->textdomain, $this->post_type);
			$layout = ob_get_clean();
		}
		if ($layout != "") {
			$content = $layout;
		}
		if (!empty($tools['disable_emd_templates'])) {
			remove_filter('the_title', array(
				$this,
				'change_title_disable_emd_temp'
			) , 10, 2);
		}
		return $content;
	}
	/**
	 * Add operations and add new submenu hook
	 * @since WPAS 4.4
	 */
	public function add_menu_link() {
		add_submenu_page(null, __('CSV Import/Export', 'campus-directory') , __('CSV Import/Export', 'campus-directory') , 'manage_operations_emd_persons', 'operations_emd_person', array(
			$this,
			'get_operations'
		));
	}
	/**
	 * Display operations page
	 * @since WPAS 4.0
	 */
	public function get_operations() {
		if (current_user_can('manage_operations_emd_persons')) {
			$myapp = str_replace("-", "_", $this->textdomain);
			if (!function_exists('emd_operations_entity')) {
				emd_lite_get_operations('opr', $this->plural_label, $this->textdomain);
			} else {
				do_action('emd_operations_entity', $this->post_type, $this->plural_label, $this->sing_label, $myapp, $this->menu_entity);
			}
		}
	}
	public function show_lite_filters() {
		if (class_exists('EMD_AFC')) {
			return;
		}
		global $pagenow;
		if (get_post_type() == $this->post_type && $pagenow == 'edit.php') {
			emd_lite_get_filters($this->textdomain);
		}
	}
}
new Emd_Person;