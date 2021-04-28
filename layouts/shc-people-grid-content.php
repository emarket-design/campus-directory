<?php global $people_grid_count, $people_grid_filter, $people_grid_set_list;
$real_post = $post;
$ent_attrs = get_option('campus_directory_attr_list');
?>
<div class="emdcol col-pics">
<a class="emd-person-link" href="<?php echo get_permalink(); ?>" title="<?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
">
	<div class="emd-person-img" style="background:white top center / cover no-repeat url( <?php echo ((wp_get_attachment_url(current(get_post_meta(get_the_ID() , 'emd_person_photo')))) ? "" . wp_get_attachment_url(current(get_post_meta(get_the_ID() , 'emd_person_photo'))) . "" : CAMPUS_DIRECTORY_PLUGIN_URL . 'assets/img/photo_shell-149x150.jpg'); ?>)"></div>
		<div class="emd-person-name"><?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
</div>
</a>
</div>