<?php
/**
 * Settings Glossary Functions
 *
 * @package CAMPUS_DIRECTORY
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('campus_directory_settings_glossary', 'campus_directory_settings_glossary');
/**
 * Display glossary information
 * @since WPAS 4.0
 *
 * @return html
 */
function campus_directory_settings_glossary() {
	global $title;
?>
<div class="wrap">
<h2><?php echo $title; ?></h2>
<p><?php _e('Integrated information repository for academic people', 'campus-directory'); ?></p>
<p><?php _e('The below are the definitions of entities, attributes, and terms included in Campus Directory.', 'campus-directory'); ?></p>
<div id="glossary" class="accordion-container">
<ul class="outer-border">
<li id="emd_person" class="control-section accordion-section open">
<h3 class="accordion-section-title hndle" tabindex="1"><?php _e('People', 'campus-directory'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('Any person who is a faculty, graduate or undergraduate student, staff.', 'campus-directory'); ?></p><tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Attributes', 'campus-directory'); ?></div></th></tr>
<tr>
<th><?php _e('Photo', 'campus-directory'); ?></th>
<td><?php _e('For best results choose a photo close to 150x150px dimensions with crop thumbnail to exact dimensions option selected in WordPress media settings Photo does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('First Name', 'campus-directory'); ?></th>
<td><?php _e(' First Name is a required field. Being a unique identifier, it uniquely distinguishes each instance of Person entity. First Name is filterable in the admin area. First Name does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Last Name', 'campus-directory'); ?></th>
<td><?php _e(' Last Name is a required field. Being a unique identifier, it uniquely distinguishes each instance of Person entity. Last Name is filterable in the admin area. Last Name does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Person Type', 'campus-directory'); ?></th>
<td><?php _e(' Person Type is a required field. Person Type is filterable in the admin area. Person Type does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Bio', 'campus-directory'); ?></th>
<td><?php _e(' Bio does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Office', 'campus-directory'); ?></th>
<td><?php _e(' Office is filterable in the admin area. Office does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Address', 'campus-directory'); ?></th>
<td><?php _e('The mailing address of this person Address does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Email', 'campus-directory'); ?></th>
<td><?php _e(' Email is filterable in the admin area. Email does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Phone', 'campus-directory'); ?></th>
<td><?php _e(' Phone is filterable in the admin area. Phone does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Website', 'campus-directory'); ?></th>
<td><?php _e(' Website does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Personal Website', 'campus-directory'); ?></th>
<td><?php _e(' Personal Website does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Linkedin', 'campus-directory'); ?></th>
<td><?php _e(' Linkedin does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Twitter', 'campus-directory'); ?></th>
<td><?php _e(' Twitter does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Curriculum Vitae', 'campus-directory'); ?></th>
<td><?php _e(' Curriculum Vitae does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Education', 'campus-directory'); ?></th>
<td><?php _e(' Education does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Awards And Honors', 'campus-directory'); ?></th>
<td><?php _e(' Awards And Honors does not have a default value. ', 'campus-directory'); ?></td>
</tr>
<tr>
<th><?php _e('Academic Appointments', 'campus-directory'); ?></th>
<td><?php _e(' Academic Appointments does not have a default value. ', 'campus-directory'); ?></td>
</tr><tr><th style='font-size:1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Taxonomies', 'campus-directory'); ?></div></th></tr>
<tr>
<th><?php _e('Academic Area', 'campus-directory'); ?></th>

<td><?php _e('Academic area of expertise Academic Area accepts multiple values like tags', 'campus-directory'); ?>. <?php _e('Academic Area does not have a default value', 'campus-directory'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Academic Area.</b>', 'campus-directory'); ?></p></div></td>
</tr>

<tr>
<th><?php _e('Title', 'campus-directory'); ?></th>

<td><?php _e(' Title accepts multiple values like tags', 'campus-directory'); ?>. <?php _e('Title does not have a default value', 'campus-directory'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Title.</b>', 'campus-directory'); ?></p></div></td>
</tr>

<tr>
<th><?php _e('Research Area', 'campus-directory'); ?></th>

<td><?php _e(' Research Area accepts multiple values like tags', 'campus-directory'); ?>. <?php _e('Research Area does not have a default value', 'campus-directory'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Research Area.</b>', 'campus-directory'); ?></p></div></td>
</tr>

<tr>
<th><?php _e('Location', 'campus-directory'); ?></th>

<td><?php _e(' Location accepts multiple values like tags', 'campus-directory'); ?>. <?php _e('Location does not have a default value', 'campus-directory'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Location.</b>', 'campus-directory'); ?></p></div></td>
</tr>

<tr>
<th><?php _e('Directory Tag', 'campus-directory'); ?></th>

<td><?php _e('Generic taxonomy which binds people, courses and publications together. Directory Tag accepts multiple values like tags', 'campus-directory'); ?>. <?php _e('Directory Tag does not have a default value', 'campus-directory'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Directory Tag.</b>', 'campus-directory'); ?></p></div></td>
</tr>
<tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Relationships', 'campus-directory'); ?></div></th></tr>
<tr>
<th><?php _e('Support Staff', 'campus-directory'); ?></th>
<td><?php _e('Allows to display and create connections with People', 'campus-directory'); ?>. <?php _e('One instance of People can associated with many instances of People, and vice versa', 'campus-directory'); ?>.  <?php _e('The relationship can be set up in the edit area of People using Support Staff relationship box', 'campus-directory'); ?>. </td>
</tr>
<tr>
<th><?php _e('Advisees', 'campus-directory'); ?></th>
<td><?php _e('Allows to display and create connections with People', 'campus-directory'); ?>. <?php _e('One instance of People can associated with many instances of People, and vice versa', 'campus-directory'); ?>.  <?php _e('The relationship can be set up in the edit area of People using Advisor relationship box', 'campus-directory'); ?>. </td>
</tr>
<tr>
<th><?php _e('Supported Faculty', 'campus-directory'); ?></th>
<td><?php _e('Allows to display and create connections with People', 'campus-directory'); ?>. <?php _e('One instance of People can associated with many instances of People, and vice versa', 'campus-directory'); ?>.  <?php _e('The relationship can be set up in the edit area of People using Support Staff relationship box', 'campus-directory'); ?>. </td>
</tr>
<tr>
<th><?php _e('Advisor', 'campus-directory'); ?></th>
<td><?php _e('Allows to display and create connections with People', 'campus-directory'); ?>. <?php _e('One instance of People can associated with many instances of People, and vice versa', 'campus-directory'); ?>.  <?php _e('The relationship can be set up in the edit area of People using Advisor relationship box. ', 'campus-directory'); ?> </td>
</tr></table>
</div>
</div>
</li>
</ul>
</div>
</div>
<?php
}