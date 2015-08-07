=== Gravity Forms Entries Inventory Management ===
Contributors: Marius Vetrici
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N4MHT82R3LQZA
Tags: gravity-forms, inventory, inventory management, tickets, entry limits
Requires at least: 3.5
Tested up to: 4.2.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Entries inventory management for Gravity Forms.

== Description ==

The Gravity Forms Entries Inventory Management provides an easy way to accept a given number of bookings/sell X tickets on your website.

Mark the desired field with 'gfinventory' css class in the Field Appearence tab. Further to this the plugin will:
* Sum up the values from the 'gfinventory' field from all the available Entries of this form
* Will compare that total with the "Number of Entries" (total entries) option from Form Settings tab.
* Will display any needed validation both at:
	* Form load time
	* Form submit

Note!
Plugin works with "total entries" option for Number of Entries.
Plugin **not** tested with other Number of Entries limits like:
* per day
* per week
* per month
* per year

= Compatibility =

This plugin is compatible with:
* Gravity Forms >= 1.9.12.10

== Installation ==

1. Copy the `gravity-entry-inventory-management` folder into your `wp-content/plugins` folder
2. Activate the Gravity Forms Entries Inventory Management plugin via the plugins admin page
3. Create a new Gravity Form 
4. Add a new input field on the form
5. Go to the Field Appearence tab and add 'gfinventory' (without single quotes) to the Custom Css Class field

== Screenshots ==

1. This is an example of how the Entries Inventory Management works.

== Changelog ==

= 1.0.0 =
* First working version
