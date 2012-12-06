<?php
/**
 * cbnet Different Posts Per Page Plugin Options Settings API
 *
 * This file implements the WordPress Settings API for the 
 * Options for the cbnet Different Posts Per Page Plugin.
 * 
 * @package 	cbnetdppp
 * @copyright	Copyright (c) 2012, Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		cbnetdppp 2.0
 */

/**
 * Register Plugin Settings
 * 
 * Register plugin_cbnetdppp_options array to hold
 * all Plugin options.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 * 
 * @param	string		$option_group		Unique Settings API identifier; passed to settings_fields() call
 * @param	string		$option_name		Name of the wp_options database table entry
 * @param	callback	$sanitize_callback	Name of the callback function in which user input data are sanitized
 */
register_setting( 
	// $option_group
	'reading', 
	// $option_name
	'plugin_cbnetdppp_options', 
	// $sanitize_callback
	'cbnetdppp_options_validate' 
);


/**
 * Plugin register_setting() sanitize callback
 * 
 * Validate and whitelist user-input data before updating Plugin 
 * Options in the database. Only whitelisted options are passed
 * back to the database, and user-input data for all whitelisted
 * options are sanitized.
 * 
 * @link	http://codex.wordpress.org/Data_Validation	Codex Reference: Data Validation
 * 
 * @param	array	$input	Raw user-input data submitted via the Plugin Settings page
 * @return	array	$input	Sanitized user-input data passed to the database
 */
function cbnetdppp_options_validate( $input ) { 
	// This is the "whitelist": current settings
	$valid_input = cbnetdppp_get_options();
	// Get the array of option parameters
	$option_parameters = cbnetdppp_get_option_parameters();
	// Get the array of option defaults
	$option_defaults = cbnetdppp_get_option_defaults();
	
	// Determine what type of submit was input
	$submittype = ( ! empty( $input['reset'] ) ? 'reset' : 'submit' );
	
	// Loop through each setting
	foreach ( $option_defaults as $setting => $value ) {
		// If no option is selected, set the default
		//$valid_input[$setting] = ( ! isset( $input[$setting] ) ? $option_defaults[$setting] : $input[$setting] );
		
		// If submit, validate/sanitize $input
		if ( 'submit' == $submittype ) {
		
			// Get the setting details from the defaults array
			$optiondetails = $option_parameters[$setting];
			// Get the array of valid options, if applicable
			$valid_options = ( isset( $optiondetails['valid_options'] ) ? $optiondetails['valid_options'] : false );
			
			// Validate checkbox fields
			if ( 'checkbox' == $optiondetails['type'] ) {
				// If input value is set and is true, return true; otherwise return false
				$valid_input[$setting] = ( ( isset( $input[$setting] ) && true == $input[$setting] ) ? true : false );
			}
			// Validate radio button fields
			else if ( 'radio' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $input[$setting], $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate select fields
			else if ( 'select' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $input[$setting], $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate text input and textarea fields
			else if ( ( 'text' == $optiondetails['type'] || 'textarea' == $optiondetails['type'] ) ) {
				// Validate no-HTML content
				if ( 'nohtml' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_nohtml_kses filter
					$valid_input[$setting] = wp_filter_nohtml_kses( $input[$setting] );
				}
				// Validate HTML content
				if ( 'html' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_kses filter
					$valid_input[$setting] = wp_filter_kses( $input[$setting] );
				}
				// Validate integer content
				if ( 'integer' == $optiondetails['sanitize'] ) { 
					// Verify value is an integer
					$valid_input[$setting] = ( is_int( (int) $input[$setting] ) ? $input[$setting] : $valid_input[$setting] );
				}
			}
		} 
		// If reset, reset defaults
		elseif ( 'reset' == $submittype ) {
			// Set $setting to the default value
			$valid_input[$setting] = $option_defaults[$setting];
		}
	}
	return $valid_input;		

}


$cbnetdppp_sections = cbnetdppp_get_options_page_sections();
/**
 * Call add_settings_section() for each Settings 
 * 
 * Loop through each Plugin Settings page tab, and add 
 * a new section to the Plugin Settings page for each 
 * section specified for each tab.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section	Codex Reference: add_settings_section()
 * 
 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
 * @param	string		$title		Title of the Settings page section
 * @param	callback	$callback	Name of the callback function in which section text is output
 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
 */
foreach ( $cbnetdppp_sections as $section ) {
	$sectionname = $section['name'];
	$sectiontitle = $section['title'];
	// Add settings section
	add_settings_section(
		// $sectionid
		'cbnetdppp_' . $sectionname . '_section',
		// $title
		$sectiontitle,
		// $callback
		'cbnetdppp_sections_callback',
		// $pageid
		'reading'
	);
}

/**
 * Callback for add_settings_section()
 * 
 * Generic callback to output the section text
 * for each Plugin settings section. 
 * 
 * @uses	cbnetdppp_get_settings_page_tabs()	Defined in /functions/options.php
 * 
 * @param	array	$section_passed	Array passed from add_settings_section()
 */
function cbnetdppp_sections_callback( $section_passed ) {
	$sections = cbnetdppp_get_options_page_sections();
	foreach ( $sections as $section ) {
		if ( 'cbnetdppp_' . $section['name'] . '_section' == $section_passed['id'] ) {
			?>
			<p><?php echo $section['description']; ?></p>
			<?php
		}
	}
}


$cbnetdppp_option_parameters = cbnetdppp_get_option_parameters();
/**
 * Call add_settings_field() for each Setting Field
 * 
 * Loop through each Plugin option, and add a new 
 * setting field to the Plugin Settings page for each 
 * setting.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_field	Codex Reference: add_settings_field()
 * 
 * @param	string		$settingid	Unique Settings API identifier; passed to the callback function
 * @param	string		$title		Title of the setting field
 * @param	callback	$callback	Name of the callback function in which setting field markup is output
 * @param	string		$pageid		Name of the Settings page to which to add the setting field; passed from add_settings_section()
 * @param	string		$sectionid	ID of the Settings page section to which to add the setting field; passed from add_settings_section()
 * @param	array		$args		Array of arguments to pass to the callback function
 */
foreach ( $cbnetdppp_option_parameters as $option ) {
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optionsection = $option['section'];
	$optiontype = $option['type'];
	add_settings_field(
		// $settingid
		'cbnetdppp_setting_' . $optionname,
		// $title
		$optiontitle,
		// $callback
		'cbnetdppp_setting_callback',
		// $pageid
		'reading',
		// $sectionid
		'cbnetdppp_' . $optionsection . '_section',
		// $args
		$option
	);
}

/**
 * Callback for get_settings_field()
 */
function cbnetdppp_setting_callback( $option ) {
	$cbnetdppp_options = cbnetdppp_get_options();
	$option_parameters = cbnetdppp_get_option_parameters();
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiondescription = ( isset( $option['description'] ) ? $option['description'] : '' );
	$fieldtype = $option['type'];
	$fieldname = 'plugin_cbnetdppp_options[' . $optionname . ']';
	
	// Output checkbox form field markup
	if ( 'checkbox' == $fieldtype ) {
		?>
		<input type="checkbox" name="<?php echo $fieldname; ?>" <?php checked( $cbnetdppp_options[$optionname] ); ?> />
		<?php
	}
	// Output radio button form field markup
	else if ( 'radio' == $fieldtype ) {
		$valid_options = array();
		$valid_options = $option['valid_options'];
		foreach ( $valid_options as $valid_option ) {
			?>
			<input type="radio" name="<?php echo $fieldname; ?>" <?php checked( $valid_option['name'] == $cbnetdppp_options[$optionname] ); ?> value="<?php echo $valid_option['name']; ?>" />
			<span>
			<?php echo $valid_option['title']; ?>
			<?php if ( $valid_option['description'] ) { ?>
				<span style="padding-left:5px;"><em><?php echo $valid_option['description']; ?></em></span>
			<?php } ?>
			</span>
			<br />
			<?php
		}
	}
	// Output select form field markup
	else if ( 'select' == $fieldtype ) {
		$valid_options = array();
		$valid_options = $option['valid_options'];
		?>
		<select name="<?php echo $fieldname; ?>">
		<?php 
		foreach ( $valid_options as $name => $title ) {
			?>
			<option <?php selected( $name == $cbnetdppp_options[$optionname] ); ?> value="<?php echo $name; ?>"><?php echo $title; ?></option>
			<?php
		}
		?>
		</select>
		<?php
	} 
	// Output text input form field markup
	else if ( 'text' == $fieldtype ) {
		?>
		<input type="text" name="<?php echo $fieldname; ?>" value="<?php echo wp_filter_nohtml_kses( $cbnetdppp_options[$optionname] ); ?>" />
		<?php
	} 
	// Output the setting description
	if ( '' != $optiondescription ) {
		?>
		<span class="description"><?php echo $optiondescription; ?></span>
		<?php
	}
}

?>