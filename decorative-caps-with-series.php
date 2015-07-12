<?php
/*
Plugin Name: Decorative Caps With Series
Plugin URI: http://www.intaligo.com/decorative-caps-with-series.zip
Version: 2.0
Author: Mike Tulloch
Author URI: http://www.intaligo.com
Description: Change the first letter of a series post to a decorative cap via css.

Based on Decorative Caps, by Steve Sensenig.
This plugin interfaces with and requires Organize Series by Darren Ethier.  

Tested with WP 3.9.1 / Organize Series 2.4.6 / WP-PageNavi 2.8.5 
Other versions *should* work, but have not been tested.

Solo Dei Gloria
*/

/* This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//This is needed so that we can use some functions with Organize Series. 
include_once( plugin_dir_path( __FILE__).'../organize-series/orgSeries-taxonomy.php');

// Call to the plugin options page, so the user can set the plugin options. 
if ( function_exists("is_plugin_page") && is_plugin_page() ) {
	scs_decorative_caps_options_page();
	return;
}

// Update and display the admin page. This is where user can set the CSS for the series.
function scs_decorative_caps_options_page() {

	// First handle updating
	$updatemsg = "";
	if ( ! isset( $_REQUEST['updated'] ) ) 
		{
		$_REQUEST['updated'] = false;
	
		// Only display something if the form was actually submitted (i.e. Update Options button clicked).
		// This prevents the failure message from appearing when just the page was reloaded. 
		if ( $_REQUEST['dcws_cssarray'] ) 
			{ 
			// Write values to the db.
			// If successful $ok = true.
			$ok = false;
			$ok = update_option('dcws_cssarray',$_REQUEST['dcws_cssarray']);
			if ($ok) 
				{
				$updatemsg = "<div id=\"message\" class=\"updated fade\"><p>Options saved.</p></div>";
				}
			else 
				{
				$updatemsg = "<div id=\"message\" class=\"error_fade\"><p>Failed to save options.</p></div>";
				}
			echo $updatemsg;
			}
		}


	// Now lay out options themselves
	?>
	<div class="wrap">
	<h2>Decorative Caps With Series Options</h2>
	<form method="post" name="dcws_form">
	For each series, enter the name of the CSS class to use for the decorative capital of that series.<br>
	Once you add that class definition to any stylesheet you use for your theme, you're all set.
	
	<table class="optiontable editform" cellspacing="10px">
			<?php   
			    // Hacky way of figuring out if we have any series at all. get_series_ordered returns an array w/ all the series in it.
				// If it's NULL, then we have no series defined. 
				$listser = get_series_ordered();
				if (empty($listser) ) 
					{
					print('You have no series! You need to define some in order to use Decorative Caps with Series.');
					}
				else 
					{
					// Lists all series with a text input field for the class name for the decorative first letter
					// for that series. 
				  
					$i=0;
					$series = get_series_list();
					foreach ( $series as $serial ) 
						{
						$i++;
						echo '<tr><td>';
						// Series name
						echo (esc_html( $serial['ser_name'] ));
						echo ('</td><td>');
						echo ('<input type="text" value="');
						$results=get_option('dcws_cssarray');
						echo ($results[$i]);
						// Needed so the values of the form are stored and we can read them after form submission.
						echo ('" name="dcws_cssarray[');
						echo ($i);
						echo (']" size="30">');
						echo('</td></tr>');
						}
					}
			?>
	</table>
	<p class="submit">
	<input type="hidden" id="action" name="action" value="submit">
 	<input type="submit" value="Update Options &raquo;">
	</p>
	</form>
	</div>

<?php 

}

function scs_add_decorative_caps_options()
{
	add_options_page('Decorative Caps With Series','Decorative Caps With Series','manage_options',__FILE__,'scs_decorative_caps_options_page');
}
add_action('admin_menu','scs_add_decorative_caps_options');

//Load other files for the admin page and set main settings for the page
function scs_admin_theme_scripts() {
	//Create the option and add it if it doesn't exist.
	$default= array('','','','','','','','','','');
	add_option('dcws_cssarray', $default);	
}
add_action('admin_enqueue_scripts','scs_admin_theme_scripts');

// This is the main function that does the actual work of making the initial cap in a series.
function scs_add_decorative_caps($text='') {

  // Determine what page we're on, and if we're on any page but the first, we bail.
  $strURL = $_SERVER['REQUEST_URI'];
  $arrVals = split("/",$strURL);
  $found = 0;
  $i = 0;
  $reali = 0;

  foreach ($arrVals as $index => $value) 
    {
        if($value == $name) $found = $index;
    }
  // Weirdly enough, at least for my series, $found = 4 on the first page. 
  // If the post is one page, $found = 4, so it works for single posts.
  // This might need to be changed for other setups.  
  if ($found > 4) return $text;

  $decorative_text = "";
  $firstcharpos = 0;
  $firstchar = substr($text,$firstcharpos,1);
  // Check to see if first character is part of an opening tag, such as <p>
  // If it is, add all that other stuff to decorative_text so we don't lose it.
  if($firstchar == "<")
  {
     $firstcharpos = strpos($text,">") + 1;
     $firstchar = substr($text,$firstcharpos,1);
     $decorative_text = substr($text,0,$firstcharpos);
  }

  // Treat the first char differently than the rest of the text. 
  // Specifically the first letter will be in a span with a css class that is 
  // whatever the user defines on the options page.
  // This allows different series to have a different style of first letter.

  // Bail out if the first letter is not a letter, like say a quote. 
  if (! ctype_alpha($firstchar))
  	{
  	return $text;
  	}

  // We know it's a letter.
  $alttext = $firstchar;

	// Are we in a series? If so, let's use the css class for that series.
    $serarray = get_the_series();
	// do we have any series?
	if (!empty($serarray) ) 
		{
		// Assumes post is in one series only, so we set
		// $real_name to the first non-null response.
		foreach ($serarray as $series) 
				{
				$i++;
				$ser_ID = $series->term_id;
				$name = get_series_name($ser_ID);
				if ($name != '') 
					{
					$real_name = $name;
					$reali = $i;
					}
				}
		}
	// Get the CSS definition for this class from the db.
	$cfs = get_option('dcws_cssarray');
	// Now we have the whole css definition for this class, but all we want is the class name.
	$cssclass = $cfs[$reali];
	// If we have no class defined or if we aren't in a series, then don't do anything with
	// the initial letter.
	if (($cssclass == '') || ($real_name == ''))
		{
		$firstchartext = $firstchar;
		}	
	// If we are in a series, make the span class to the class for the series.	
	else
		{
		$firstchartext = "<span class=\"$cssclass\">$firstchar</span>";
		}

  $remainingpost = substr($text,$firstcharpos + 1);
  $decorative_text .= $firstchartext . $remainingpost;
	return $decorative_text;
} //end of function

	add_filter('the_content','scs_add_decorative_caps');
?>