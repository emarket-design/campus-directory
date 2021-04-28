<?php global $search_people_count;
$ent_attrs = get_option('campus_directory_attr_list'); ?>
<tr>
<td><a href="<?php echo get_permalink(); ?>" title="<?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
"> <?php echo esc_html(emd_mb_meta('emd_person_fname')); ?>
 <?php echo esc_html(emd_mb_meta('emd_person_lname')); ?>
</a></td>
<?php if (emd_is_item_visible('ent_person_photo', 'campus_directory', 'attribute', 1)) { ?>
<td><a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink(); ?>"><?php if (get_post_meta($post->ID, 'emd_person_photo')) {
		$sval = get_post_meta($post->ID, 'emd_person_photo');
		$thumb = wp_get_attachment_image_src($sval[0], 'thumbnail');
		echo '<img class="emd-img thumb" src="' . $thumb[0] . '" width="' . $thumb[1] . '" height="' . $thumb[2] . '" alt="' . get_post_meta($sval[0], '_wp_attachment_image_alt', true) . '"/>';
	} ?></a></td>
<?php
} ?>
<?php if (emd_is_item_visible('ent_person_type', 'campus_directory', 'attribute', 1)) { ?>
<td><?php echo emd_get_attr_val('campus_directory', $post->ID, 'emd_person', 'emd_person_type'); ?></td>
<?php
} ?>
<?php if (emd_is_item_visible('ent_person_office', 'campus_directory', 'attribute', 1)) { ?>
<td><?php echo esc_html(emd_mb_meta('emd_person_office')); ?>
</td>
<?php
} ?>
<?php if (emd_is_item_visible('ent_person_phone', 'campus_directory', 'attribute', 1)) { ?>
<td>&phone; <a href="tel:<?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
"><?php echo esc_html(emd_mb_meta('emd_person_phone')); ?>
</a></td>
<?php
} ?>
<?php if (emd_is_item_visible('ent_person_email', 'campus_directory', 'attribute', 1)) { ?>
<td>✉ <a href="mailto:<?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?>"><?php echo antispambot(esc_html(emd_mb_meta('emd_person_email'))); ?></a></td>
<?php
} ?>
</tr>