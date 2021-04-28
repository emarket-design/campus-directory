<?php $real_post = $post;
$ent_attrs = get_option('campus_directory_attr_list');
?>
<div id="archive-emd-person-<?php echo get_the_ID(); ?>" class="emd-container emd-person-wrap archive-wrap">
<?php $is_editable = 0; ?>
<div class="archive-well tax panel emd-person panel-<?php echo emd_get_attr_val('campus_directory', $post->ID, 'emd_person', 'emd_person_type', 'key'); ?>" style="background-color:#FFFFFF">
	<div class="panel-heading">
		<div class="panel-title">
			<a class="archive permalink font-bold" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
</a>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-4 <?php echo ((wp_get_attachment_url(current(get_post_meta(get_the_ID() , 'emd_person_photo')))) ? "well-left" : "well-left hidden"); ?>">
				<div class="slcontent emdbox">
					<?php if (emd_is_item_visible('ent_person_photo', 'campus_directory', 'attribute')) { ?>
					<div class="archive-image segment-block ent-person-photo">
						<a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php if (get_post_meta($post->ID, 'emd_person_photo')) {
		$sval = get_post_meta($post->ID, 'emd_person_photo');
		$thumb = wp_get_attachment_image_src($sval[0], 'thumbnail');
		echo '<img class="emd-img thumb" src="' . $thumb[0] . '" width="' . $thumb[1] . '" height="' . $thumb[2] . '" alt="' . get_post_meta($sval[0], '_wp_attachment_image_alt', true) . '"/>';
	} ?></a>
					</div><?php
} ?>
				</div>
			</div>
			<div class="col-sm-<?php echo ((wp_get_attachment_url(current(get_post_meta(get_the_ID() , 'emd_person_photo')))) ? "8" : "12"); ?>">
				<div class="srcontent emdbox">
					<?php if (emd_is_item_visible('ent_person_type', 'campus_directory', 'attribute')) { ?>
					<div class="tax-content segment-block ent-person-type">
						<?php echo emd_get_attr_val('campus_directory', $post->ID, 'emd_person', 'emd_person_type'); ?>
					</div><?php
} ?><?php if (emd_is_item_visible('tax_person_title', 'campus_directory', 'taxonomy')) { ?>
					<div class="tax-content segment-block tax-person-title">
						<?php echo emd_get_tax_vals(get_the_ID() , 'person_title'); ?>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_office', 'campus_directory', 'attribute')) { ?>
					<div class="tax-content segment-block ent-person-office">
						<span class="dashicons dashicons-building"></span> <?php echo esc_html(emd_mb_meta('emd_person_office')); ?>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_phone', 'campus_directory', 'attribute')) { ?>
					<div class="tax-content segment-block ent-person-phone">
						<span class="dashicons dashicons-phone"></span> <a href="tel:<?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
"><?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
</a>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_email', 'campus_directory', 'attribute')) { ?>
					<div class="tax-content segment-block ent-person-email">
						<span class="dashicons dashicons-email-alt"></span> <a href="mailto:<?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?>"><?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?></a>
					</div><?php
} ?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="fcontent">
			<?php if (emd_is_item_visible('tax_directory_tag', 'campus_directory', 'taxonomy')) { ?>
			<div class="footer-segment-block">
				<span class="footer-object-title label label-info" style="margin-right:2px"><?php _e('Directory Tag', 'campus-directory'); ?></span><span class="footer-object-value"><?php echo emd_get_tax_vals(get_the_ID() , 'directory_tag'); ?></span>
			</div><?php
} ?>
		</div>
	</div>
</div>
</div><!--container-end-->