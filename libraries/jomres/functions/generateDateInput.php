<?php
/**
 * Core file
 *
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 8
 * @package Jomres
 * @copyright	2005-2014 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly.
 **/


// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################


/**
#
 * Makes a javascript date input field. Creates a random name for the form element each time so that multiple javascript forms can be used on the same page without collision
#
 */
function generateDateInput( $fieldName, $dateValue, $myID = false, $siteConfig = false, $historic = false )
	{
	// We need to give the javascript date function a random name because it will be called by both the component and modules
	$uniqueID = "";
	$output   = "";
	// If this date picker is "arrivalDate" then we need to create a departure date input name too, then set it in showtime. With that we'll be able to tell this set of functionality what the id of the
	// departureDate is so that it can set it's date when this one changes
	if ( $fieldName == "arrivalDate" || $fieldName == "asc_arrivalDate" )
		{
		$uniqueID = generateJomresRandomString( 15 );
		set_showtime( 'departure_date_unique_id', $uniqueID . "_XXX" );
		}
	else
		{
		$uniqueID = get_showtime( 'departure_date_unique_id' );
		}

	if ( $dateValue == "" ) $dateValue = date( "Y/m/d" );
	$dateValue = JSCalmakeInputDates( $dateValue, $siteConfig );

	$siteConfig = jomres_singleton_abstract::getInstance( 'jomres_config_site_singleton' );
	$jrConfig   = $siteConfig->get();
	$dateFormat = $jrConfig[ 'cal_input' ];
	$dateFormat = strtolower( str_replace( "%", "", $dateFormat ) ); // For the new jquery calendar, we'll strip out the % symbols. This should mean that we don't need to force upgraders to reset their settings.
	$dateFormat = str_replace( "y", "yy", $dateFormat );
	$dateFormat = str_replace( "m", "mm", $dateFormat );
	$dateFormat = str_replace( "d", "dd", $dateFormat );

	if ( !defined( '_JOMRES_CALENDAR_RTL' ) ) define( '_JOMRES_CALENDAR_RTL', 'false' );

	$clear_checkbox_js = '&nbsp;';

	if ( ( $fieldName == "departureDate" || $fieldName == "asc_departureDate" ) && $jrConfig[ 'use_cleardate_checkbox' ] == "1" )
		{
		$arr_date_unique_id = str_replace( "_XXX", "", $uniqueID );
		$clear              = jr_gettext( '_JOMRES_CLEARDATES', _JOMRES_CLEARDATES );
		$rand_id            = generateJomresRandomString( 10 );
		//$clear_checkbox_js = '<input type="checkbox" onClick="jomresJquery(\'#'.$uniqueID.'\').datepicker( \'setDate\' , null );jomresJquery(\'#'.$arr_date_unique_id.'\').datepicker( \'setDate\' , null );" /> '.$clear;
		$clear_checkbox_js = '<input type="checkbox" name="nodates" value="1" id="' . $rand_id . '" onClick="jomresJquery(\'#' . $uniqueID . '\').datepicker( \'isDisabled\' )?jomresJquery(\'#' . $uniqueID . '\').datepicker( \'enable\' ):jomresJquery(\'#' . $uniqueID . '\').datepicker( \'disable\' );jomresJquery(\'#' . $arr_date_unique_id . '\').datepicker( \'isDisabled\' )?jomresJquery(\'#' . $arr_date_unique_id . '\').datepicker( \'enable\' ):jomresJquery(\'#' . $arr_date_unique_id . '\').datepicker( \'disable\' );" /> ' . $clear;
		}

	$size        = " size=\"10\" ";
	$input_class = "";
	if ( using_bootstrap() )
		{
		$size        = "";
		$input_class = " input-small ";
		}

	$output .= '<script type="text/javascript">
	jomresJquery(function() {
		jomresJquery("#' . $uniqueID . '").datepicker( {
			dateFormat: "' . $dateFormat . '", ';

	if ( $historic ) // Can we show older dates? By default no
	$output .= 'minDate: "-5Y", ';
	else
	$output .= 'minDate: 0, ';

	$output .= 'maxDate: "+5Y",
			';

	if ( (using_bootstrap() && jomres_bootstrap_version() == "2") || !using_bootstrap() )
		{
		$output .= 'buttonImage: \''.JOMRES_ROOT_DIRECTORY.'/images/calendar.png\',';
		$bs3_icon = '';
		}
	else
		{
		$output .= 'buttonText: "",';
		$bs3_icon = '<span class="input-group-addon"><span class="fa fa-calendar"></span></span>';
		}

	$output .= '
			buttonImageOnly: true,
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			showOtherMonths: true,
			selectOtherMonths: true,';
	if ( $jrConfig[ 'calendarstartofweekday' ] == "1" ) $output .= 'firstDay: 0,';
	else
	$output .= 'firstDay: 1,';
	$output .= '	showButtonPanel: true';
	if ( $fieldName == "arrivalDate" || $fieldName == "asc_arrivalDate"  )
		{
		$output .= ',onSelect: function(selectedDate) {
				var nextDayDate = jomresJquery("#' . $uniqueID . '").datepicker(\'getDate\', \'+1d\');
				nextDayDate.setDate(nextDayDate.getDate() + 1);
				jomresJquery("#' . get_showtime( 'departure_date_unique_id' ) . '").datepicker(\'setDate\', nextDayDate);
				//alert(date);
				}';
		}

	$output .= '} );

	});
	</script>
	<input type="text" ' . $size . ' name="' . $fieldName . '" id="' . $uniqueID . '" value="' . $dateValue . '" class="' . $input_class . ' form-control input-group" />'.$bs3_icon.'
	';
	$br = "";
	if ( $fieldName == "departureDate" && $jrConfig[ 'use_cleardate_checkbox' ] == "1" ) 
		$br = "<br/>";

	set_showtime("current_clear_checkbox" , $clear_checkbox_js );
	
	$pageoutput[ ] = array ( "INPUT" => $output, "CHECKBOX" => $clear_checkbox_js, "BR" => $br );
	$tmpl          = new patTemplate();
	$tmpl->setRoot( JOMRES_TEMPLATEPATH_FRONTEND );
	$tmpl->readTemplatesFromInput( 'js_calendar_input.html' );
	$tmpl->addRows( 'pageoutput', $pageoutput );

	return $tmpl->getParsedTemplate();
	}
	