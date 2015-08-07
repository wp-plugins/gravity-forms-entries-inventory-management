<?php

/*
Plugin Name: Gravity Forms Entries Inventory Management
Plugin URI: http://www.wpriders.com
Description: The Gravity Forms Entries Inventory Management provides an easy way to accept a given number of bookings/sell X tickets on your website.
Version: 1.0.0
Author: Marius Vetrici
Author URI: http://www.wpriders.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

load_plugin_textdomain( 'gfeim', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

add_filter( "gform_pre_render", "gfeim_custom_limit_validation_on_prerender" );
/**
 * Test and display if needed the "out of inventory" message upon form load
 *
 * @param $form GForm object
 *
 * @return mixed GForm object
 */
function gfeim_custom_limit_validation_on_prerender( $form ) {
	$count = gfeim_get_inventory_total( $form );

	if ( $form['limitEntries'] &&
	     $count >= $form['limitEntriesCount']
	) {
		add_filter( 'gform_get_form_filter', "gfeim_display_limit_message", 10, 2 );
	}

	return $form;
}

function gfeim_display_limit_message( $form_string, $form ) {
	$limit_message = $form['limitEntriesMessage'];

	return
<<<LIMIT_MESSAGE
<p class="limitmessage">$limit_message</p>
LIMIT_MESSAGE;
}

add_filter( 'gform_validation', 'gfeim_custom_limit_validation_on_submit' );
/**
 * Provide custom entries inventory validation upon form submission
 *
 * @param $validation_result GFObject
 *
 * @return mixed the validation result object
 */
function gfeim_custom_limit_validation_on_submit( $validation_result ) {
	$form = $validation_result['form'];

	$inventory_field_id = gfeim_get_inventory_field_id( $form );

	$count = gfeim_get_inventory_total( $form );

	if ( $form['limitEntries'] &&
	     $count + rgar( $_POST, "input_$inventory_field_id" ) > $form['limitEntriesCount']
	) {
		$validation_result['is_valid'] = false;

		// finding Field with ID of $inventory_field_id and marking it as failed validation
		foreach ( $form['fields'] as &$field ) {
			if ( $field->id == $inventory_field_id ) {
				$field->failed_validation  = true;
				$field->validation_message = __( "We're sorry, there are not enough seats available. Please try a lower number of people․", 'gfeim' );

				break;
			}
		}
	}

	$validation_result['form'] = $form;

	return $validation_result;
}

/**
 * Calculate the current inventory total for the given form. Adds up:
 * - values in fields marked with 'gfinventory' css class
 * - 1 for entries missing either a 'gfinventory' marked field or which have this field set to 0
 *
 * @param $form GForm object
 *
 * @return int the sum of all the items in the inventory
 */
function gfeim_get_inventory_total( $form ) {
	$inventory_field_id = gfeim_get_inventory_field_id( $form );

	$entries = GFAPI::get_entries( $form["id"] );

	$count = 0;

	foreach ( $entries as $id => $entry ) {
		if ( false !== $inventory_field_id &&
		     array_key_exists( $inventory_field_id, $entry ) &&
		     is_numeric( $entry[ $inventory_field_id ] ) &&
		     $entry[ $inventory_field_id ] > 0
		) {
			// Add a specific number of items to our inventory
			$count += $entry[ $inventory_field_id ];
		} else {
			// Add just the current Entry
			$count ++;
		}
	}

	return $count;
}

/**
 * Function returns the ID of the field (one single field) from the form that will be used for "adding up" the inventory
 *
 * @param $form the GForm object
 *
 * @return bool|int returns false if no field found or an int containing the field ID
 */
function gfeim_get_inventory_field_id( $form ) {
	$inventory_field_id = false;

	foreach ( $form["fields"] as $field ) {
		if ( false !== strpos( $field->cssClass, 'gfinventory' ) ) {
			$inventory_field_id = $field["id"];

			break;
		}
	}

	return $inventory_field_id;
}