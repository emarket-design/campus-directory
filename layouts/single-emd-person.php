<?php $real_post = $post;
$ent_attrs = get_option('campus_directory_attr_list');
?>
<div id="single-emd-person-<?php echo get_the_ID(); ?>" class="emd-container emd-person-wrap single-wrap">
<?php $is_editable = 0; ?>
<div class="panel panel-<?php echo emd_get_attr_val('campus_directory', $post->ID, 'emd_person', 'emd_person_type', 'key'); ?>">
	<div class="panel-heading">
		<div class="panel-title">
			<span class='single-title font-bold'> <?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
</span>
		</div>
	</div>
	<div class="panel-body">
		<div class="single-well well emd-person">
			<div class="row">
				<div class="col-sm-4 <?php echo ((wp_get_attachment_url(current(get_post_meta(get_the_ID() , 'emd_person_photo')))) ? "well-left" : "well-left hidden"); ?>">
					<div class="slcontent emdbox">
						<?php if (emd_is_item_visible('ent_person_photo', 'campus_directory', 'attribute')) { ?>
						<div class="img-gallery segment-block ent-person-photo">
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
						<div class="single-content segment-block ent-person-type">
							<?php echo emd_get_attr_val('campus_directory', $post->ID, 'emd_person', 'emd_person_type'); ?>
						</div><?php
} ?><?php if (emd_is_item_visible('tax_person_title', 'campus_directory', 'taxonomy')) { ?>
						<div class="single-content segment-block tax-person-title">
							<?php echo emd_get_tax_vals(get_the_ID() , 'person_title'); ?>
						</div><?php
} ?><?php if (emd_is_item_visible('ent_person_office', 'campus_directory', 'attribute')) { ?>
						<div class="single-content segment-block ent-person-office">
							<span class="dashicons dashicons-building"></span> <?php echo esc_html(emd_mb_meta('emd_person_office')); ?>

						</div><?php
} ?><?php if (emd_is_item_visible('ent_person_phone', 'campus_directory', 'attribute')) { ?>
						<div class="single-content segment-block ent-person-phone">
							<span class="dashicons dashicons-phone"></span> <a href="tel:<?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
"><?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
</a>
						</div><?php
} ?><?php if (emd_is_item_visible('ent_person_email', 'campus_directory', 'attribute')) { ?>
						<div class="single-content segment-block ent-person-email">
							<span class="dashicons dashicons-email-alt"></span> <a href="mailto:<?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?>"><?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?></a>
						</div><?php
} ?>
					</div>
				</div>
			</div>
		</div>
		<div class=" contact-links clearfix">
                <div class="social" style="margin:15px 0">
<?php if (emd_is_item_visible('ent_person_linkedin', 'campus_directory', 'attribute')) { ?><div><a class="social-icon linkedin animate" href="<?php echo esc_html(emd_mb_meta('emd_person_linkedin')); ?>
"><i class="fa fa-linkedin fa-fw" style="font-size:20px"></i></a></div><?php
} ?>
<?php if (emd_is_item_visible('ent_person_twitter', 'campus_directory', 'attribute')) { ?><div><a class="social-icon twitter animate" href="<?php echo esc_html(emd_mb_meta('emd_person_twitter')); ?>
"><i class="fa fa-twitter fa-fw" style="font-size:20px"></i></a></div><?php
} ?>
<div><?php do_action('emd_vcard', 'campus_directory', 'emd_person', $post->ID); ?></div>
</div>
		</div>
		<div class="tab-container wpastabcontainer" style="padding:0 20px 40px">
			<ul class="nav nav-tabs" role="tablist" style="padding-bottom: 0px">
				<li class=" active">
					<a data-toggle="tab" href="#bio" id="bio-tablink" role="tab"><?php _e('Bio', 'campus-directory'); ?></a>
				</li>
				<li>
					<a data-toggle="tab" href="#background" id="background-tablink" role="tab"><?php _e('Background', 'campus-directory'); ?></a>
				</li>
				<li>
					<a data-toggle="tab" href="#details" id="details-tablink" role="tab"><?php _e('Details', 'campus-directory'); ?></a>
				</li>
			</ul>
			<div class="tab-content wpastabcontent">
				<div class="tab-pane fade in active" id="bio">
					<?php if (emd_is_item_visible('ent_person_bio', 'campus_directory', 'attribute')) { ?>
					<div class="single-content ent-person-bio">
						<?php echo emd_mb_meta('emd_person_bio'); ?>

					</div><?php
} ?>
				</div>
				<div class="tab-pane fade in" id="background">
<?php if (emd_is_item_visible('tax_person_rareas', 'campus_directory', 'taxonomy')) { ?>
					<div class="segment-block tax-person-rareas">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Research Area', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_get_tax_vals(get_the_ID() , 'person_rareas'); ?></span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_cv', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-cv">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Curriculum Vitae', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php
	add_thickbox();
	$emd_mb_file = emd_mb_meta('emd_person_cv', 'type=file');
	if (!empty($emd_mb_file)) {
		echo '<div class="clearfix">';
		foreach ($emd_mb_file as $info) {
			emd_get_attachment_layout($info);
		}
		echo '</div>';
	}
?>
</span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('tax_person_area', 'campus_directory', 'taxonomy')) { ?>
					<div class="segment-block tax-person-area">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Academic Area', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_get_tax_vals(get_the_ID() , 'person_area'); ?></span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_education', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-education">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Education', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_mb_meta('emd_person_education'); ?>
</span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_awards', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-awards">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Awards and Honors', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_mb_meta('emd_person_awards'); ?>
</span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_appointments', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-appointments">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Academic Appointments', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_mb_meta('emd_person_appointments'); ?>
</span>
							</div>
						</div>
					</div><?php
} ?>
				</div>
				<div class="tab-pane fade in" id="details">
					<?php if (emd_is_item_visible('tax_person_location', 'campus_directory', 'taxonomy')) { ?>
					<div class="segment-block tax-person-location">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Location', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo emd_get_tax_vals(get_the_ID() , 'person_location'); ?></span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_address', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-address">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Address', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><?php echo esc_html(emd_mb_meta('emd_person_address')); ?>
</span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_website', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-website">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Website', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><a href='<?php echo esc_html(emd_mb_meta('emd_person_website')); ?>
' target='_blank' title='<?php echo get_the_title(); ?>'><?php _e('Click for more info', 'campus-directory'); ?></a></span>
							</div>
						</div>
					</div><?php
} ?><?php if (emd_is_item_visible('ent_person_pwebsite', 'campus_directory', 'attribute')) { ?>
					<div class="segment-block ent-person-pwebsite">
						<div class="row" data-has-attrib="false">
							<div class="col-sm-4">
								<span class="segtitle"><?php _e('Personal Website', 'campus-directory'); ?></span>
							</div>
							<div class="col-sm-8">
								<span class="segvalue"><a href='<?php echo esc_html(emd_mb_meta('emd_person_pwebsite')); ?>
' target='_blank' title='<?php echo get_the_title(); ?>'><?php _e('Click for more info', 'campus-directory'); ?></a></span>
							</div>
						</div>
					</div><?php
} ?><?php do_action("emd_frontend_display_cust_fields", "campus_directory", "emd_person", $post->ID);
?>
				</div>
			</div>
		</div>
		<div class="contact-links" id="emd-accordion">
			<?php if (emd_is_item_visible('entrelcon_from_support_staff', 'campus_directory', 'relation')) { ?>
<?php $post = get_post();
	$rel_filter = "";
	$res = emd_get_p2p_connections('connected', 'support_staff', 'std', $post, 1, 0, 'to', 'campus_directory', $rel_filter);
	$rel_list = get_option('campus_directory_rel_list');
?>
<div class="supported-faculty">
<div class="rel-title">Supported Faculty</div><?php
	echo $res['before_list'];
	$real_post = $post;
	$rel_count_id = 1;
	$rel_eds = Array();
	foreach ($res['rels'] as $myrel) {
		$post = $myrel;
		echo $res['before_item']; ?>
		<div class="rel-link">
			<a class="archive permalink" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo esc_html(emd_mb_meta('emd_person_fname', '', $myrel->ID)); ?> <?php echo esc_html(emd_mb_meta('emd_person_lname', '', $myrel->ID)); ?></a>
		</div><?php
		echo $res['after_item'];
		$rel_count_id++;
	}
	$post = $real_post;
	echo $res['after_list']; ?>
</div><?php
} ?>
			<?php if (emd_is_item_visible('entrelcon_to_support_staff', 'campus_directory', 'relation')) { ?>
<?php $post = get_post();
	$rel_filter = "";
	$res = emd_get_p2p_connections('connected', 'support_staff', 'std', $post, 1, 0, '', 'campus_directory', $rel_filter);
	$rel_list = get_option('campus_directory_rel_list');
?>
<div class="support-staff">
<div class="rel-title">Support Staff</div><?php
	echo $res['before_list'];
	$real_post = $post;
	$rel_count_id = 1;
	$rel_eds = Array();
	foreach ($res['rels'] as $myrel) {
		$post = $myrel;
		echo $res['before_item']; ?>
		<div class="rel-link">
			<a class="archive permalink" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo esc_html(emd_mb_meta('emd_person_fname', '', $myrel->ID)); ?> <?php echo esc_html(emd_mb_meta('emd_person_lname', '', $myrel->ID)); ?></a>
		</div><?php
		echo $res['after_item'];
		$rel_count_id++;
	}
	$post = $real_post;
	echo $res['after_list']; ?>
</div><?php
} ?>
			<?php if (emd_is_item_visible('entrelcon_from_supervisor', 'campus_directory', 'relation')) { ?>
<?php $post = get_post();
	$rel_filter = "";
	$res = emd_get_p2p_connections('connected', 'supervisor', 'std', $post, 1, 0, 'to', 'campus_directory', $rel_filter);
	$rel_list = get_option('campus_directory_rel_list');
?>
<div class="emd-advisor">
<div class="rel-title">Advisor</div><?php
	echo $res['before_list'];
	$real_post = $post;
	$rel_count_id = 1;
	$rel_eds = Array();
	foreach ($res['rels'] as $myrel) {
		$post = $myrel;
		echo $res['before_item']; ?>
		<div class="rel-link">
			<a class="archive permalink" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo esc_html(emd_mb_meta('emd_person_fname', '', $myrel->ID)); ?> <?php echo esc_html(emd_mb_meta('emd_person_lname', '', $myrel->ID)); ?></a>
		</div><?php
		echo $res['after_item'];
		$rel_count_id++;
	}
	$post = $real_post;
	echo $res['after_list']; ?>
</div><?php
} ?> 
			<?php if (emd_is_item_visible('entrelcon_to_supervisor', 'campus_directory', 'relation')) { ?>
<?php $post = get_post();
	$rel_filter = "";
	$res = emd_get_p2p_connections('connected', 'supervisor', 'std', $post, 1, 0, '', 'campus_directory', $rel_filter);
	$rel_list = get_option('campus_directory_rel_list');
?>
<div class="emd-advisee">
<div class="rel-title">Advisees</div><?php
	echo $res['before_list'];
	$real_post = $post;
	$rel_count_id = 1;
	$rel_eds = Array();
	foreach ($res['rels'] as $myrel) {
		$post = $myrel;
		echo $res['before_item']; ?>
		<div class="rel-link">
			<a class="archive permalink" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo esc_html(emd_mb_meta('emd_person_fname', '', $myrel->ID)); ?> <?php echo esc_html(emd_mb_meta('emd_person_lname', '', $myrel->ID)); ?></a>
		</div><?php
		echo $res['after_item'];
		$rel_count_id++;
	}
	$post = $real_post;
	echo $res['after_list']; ?>
</div><?php
} ?>
		</div>
	</div>
	<div class="panel-footer">
		<?php if (emd_is_item_visible('tax_directory_tag', 'campus_directory', 'taxonomy')) { ?>
		<div class="footer-segment-block">
			<span class="footer-object-title label label-info" style="margin-right:2px"><?php _e('Directory Tag', 'campus-directory'); ?></span><span class="footer-object-value"><?php echo emd_get_tax_vals(get_the_ID() , 'directory_tag'); ?></span>
		</div><?php
} ?>
	</div>
</div>
</div><!--container-end-->