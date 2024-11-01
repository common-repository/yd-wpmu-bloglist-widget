<?php
/**
 * @package YD_WPMU-Bloglist-Widget
 * @author Yann Dubois
 * @version 2.1.1
 */

/*
 Plugin Name: YD WPMU Bloglist Widget
 Plugin URI: http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget
 Description: Installs a new sidebar widget that can display an ordered list of your WPMU subsites with post count. Uses cache to avoid multiple database queries. You can also insert this list in your templates and not use the widget. | Funded by <a href="http://www.wellcom.fr">Wellcom.fr</a>
 Author: Yann Dubois
 Version: 2.1.1
 Author URI: http://www.yann.com/
 */
/**
 * @copyright 2010  Yann Dubois  ( email : yann _at_ abc.fr )
 *
 *  Original development of this plugin was kindly funded by http://www.pressonline.com
 *  Spanish and Galician translation kindly provided by: Arume @ http://www.arumeinformatica.es/ 
 *  Dutch translation kindly provided by: Rene @ http://www.fethiyehotels.com
 *  German translation by Rian Kramer @ Pangaea http://www.pangaea.nl
 *  Additional developments to this plugin kindly funded by http://www.pressonline.com and Eurospreed
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 Revision 2.1.1:
 - Indonesian translation by Syamsul Alam
 Revision 2.1.0:
 - Ability to build drop-down blog menu easily
 - Ability to sort by domain
 - Filter hook for external processing of the list by other plugins
 - show_count = false parameter
 - Regular wp-style arguments parsing (now allows using array of arguments)
 - Ukrainian translation by Mikalay @ webhostinggeeks.com
 Revision 2.0.0:
 - WPML language support
 - Place longer column first
 - Backlink disabled by default
 - Use POST for form submission
 - Blog selection options
 - Blog exclude list option
 - New YD Logo
 Revision 1.0.2:
 - Bugfix: postcount sort order (as reported by Bohdan)
 - German translation by Rian Kramer @ Pangaea http://www.pangaea.nl
 Revision 1.0.1:
 - Bugfix: settings update problem.
 Revision 1.0.0:
 - Considered stable, no complaint after more than 500 downloads
 - Added Dutch version, translation credits go to Andre @ http://www.fethiyehotels.com
 - Activated "linkbackware" mode by default
 - Donate section in the option page
 - Improved option page design
 - Added option page link in the short description
 - (slightly) Revised documentation page
 Revision 0.2.2:
 - Bugfix: Plural form was not saved (2010-04-22) (thanks to Thomas@Wellcom for noticing)
 - Bugfix: html_entities_decode would cause utf-8 nightmare ans crash in options encoding (thanks TB again!)
 Revision 0.2.1:
 - Bugfix: "Duplicate link" (2010-04-22) (thanks to Marius, Scot, Andu, Rob, Jason for noticing)
 Revision 0.2.0:
 - Bugfix: Alphabetical order was case-sensitive (2010-04-21) (thanks to Thomas@Wellcom)
 - Feature: trailing slash option
 - Compatibility: WP 2.9.2 & WPMU 2.9.2
 Revision 0.1.1:
 - Bugfix: The order and limit did not function. (2010-03-04) (thanks to Arume)
  Revision 0.1.0:
 - Original beta release
 **
 Feature requests:
 Better cache expiry
**/

/** Install or reset plugin defaults **/
function yd_wpmubl_reset( $force ) {
	/** Init values **/
	$yd_wpmubl_version		= "2.1.1";
	$default_bottomlink		= 'http://www.wellcom.fr/';
	$default_bottomtext		= '<br/><small>[&rarr;visit www.Wellcom.fr]</small>';
	$newoption				= 'widget_yd_wpmubl';
	$newvalue				= '';
	$prev_options = get_option( $newoption );
	if( ( isset( $force ) && $force ) || !isset( $prev_options['plugin_version'] ) ) {
		// those default options are set-up at plugin first-install or manual reset only
		// they will not be changed when the plugin is just upgraded or deactivated/reactivated
		$newvalue['plugin_version'] = $yd_wpmubl_version;
		$newvalue[1]['home_bottomlink'] = $default_bottomlink;
		$newvalue[1]['home_bottomtext'] = $default_bottomtext;
		$newvalue[0]['column_count'] 	= 1;
		$newvalue[0]['before_block'] 	= '<table class="blog_block"><tr>';
		$newvalue[0]['after_block'] 	= '</tr></table>';
		$newvalue[0]['before_column'] 	= '<td valign="top">';
		$newvalue[0]['after_column'] 	= '</td>';
		$newvalue[0]['before_list'] 	= '<ul class="blog_list">';
		$newvalue[0]['after_list'] 		= '</ul>';
		$newvalue[0]['before_item'] 	= '<li>';
		$newvalue[0]['after_item'] 		= '</li>';
		$newvalue[0]['before_count'] 	= '<span class="post_count"> ( ';
		$newvalue[0]['after_count'] 	= __( ' post%S% )</span>', 'yd-wpmubl' );
		$newvalue[0]['plural_form'] 	= 's';
		$newvalue[0]['limit'] 			= 0;
		$newvalue[0]['alt_text'] 		= __( '' ); // alt is not a valid <a> parameter!
		$newvalue[0]['title_text'] 		= __( '%B% blog', 'yd-wpmubl' );
		$newvalue[0]['order_by'] 		= 'blogname';
		$newvalue[0]['order'] 			= 'ASC';
		$newvalue[0]['trailing_slash'] 	= 0;
		$newvalue[0]['disable_backlink']= 1;
		$newvalue[0]['wpml_support']	= 0;
		$newvalue[0]['only_public']		= 0; 
    	$newvalue[0]['skip_archived']	= 0; 
    	$newvalue[0]['skip_mature']		= 0; 
    	$newvalue[0]['skip_spam']		= 0; 
    	$newvalue[0]['skip_deleted']	= 0; 
		$newvalue[0]['to_skip']			= '';
		if( $prev_options ) {
			update_option( $newoption, $newvalue );
		} else {
			add_option( $newoption, $newvalue );
		}
	}
}
register_activation_hook(__FILE__, 'yd_wpmubl_reset');

/** Create Text Domain For Translations **/
add_action('init', 'yd_wpmubl_textdomain');
function yd_wpmubl_textdomain() {
	$plugin_dir = basename( dirname(__FILE__) );
	load_plugin_textdomain(
		'yd-wpmubl',
		PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ),
		dirname( plugin_basename( __FILE__ ) )
	); 
}

/** Create custom admin menu page **/
add_action('admin_menu', 'yd_wpmubl_menu');
function yd_wpmubl_menu() {
	add_options_page(
	__('YD WPMU Bloglist Options',
		'yd-wpmubl'), 
	__('YD WPMU Bloglist', 'yd-wpmubl'),
	8,
	__FILE__,
		'yd_wpmubl_options'
		);
}
function yd_wpmubl_options() { //http://www.yann.com/yd-wpmubl-v102-logo.gif
	$support_url	= 'http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget';
	$yd_logo		= 'http://www.yann.com/yd-logo.gif?plugin=wpmubl-v210&ver=' . urlencode( get_bloginfo( 'version' ) );
	$jstext			= preg_replace( "/'/", "\\'", __( 'This will disable the link in your blog footer. ' .
							'If you are using this plugin on your site and like it, ' .
							'did you consider making a donation?' .
							' -- Thanks.', 'yd-wpmubl' ) );
	?>
	<script type="text/javascript">
	<!--
	function donatemsg() {
		alert( '<?php echo $jstext ?>' );
	}
	//-->
	</script>
	<?php
	echo '<div class="wrap">';
	
	// ---
	// options page header section: h2 title + warnings / updates
	// ---
	
	echo '<h2>' . __('YD WPMU Bloglist Options', 'yd-wpmubl' ) . '</h2>';

	if( isset( $_POST["do"] ) ) {
		echo '<div class="updated">';
		echo '<p>' . __('Action:', 'yd-wpmubl') . ' '
		. __( 'I should now', 'yd-wpmubl' ) . ' ' . __( $_POST["do"], 'yd-wpmubl' ) . '.</p>';
		if(			$_POST["do"] == __('Clear cache', 'yd-wpmubl') ) {
			clear_yd_widget_cache( 'wpmubl' );
			echo '<p>' . __('Caches are cleared', 'yd-wpmubl') . '</p>';
		} elseif(	$_POST["do"] == __('Reset widget options', 'yd-wpmubl') ) {
			yd_wpmubl_reset( 'force' );
			echo '<p>' . __('Widget options are reset', 'yd-wpmubl') . '</p>';
		} elseif(	$_POST["do"] == __('Update widget options', 'yd-wpmubl') ) {
			yd_wpmubl_update_options();
			echo '<p>' . __('Widget options are updated', 'yd-wpmubl') . '</p>';
		}
		echo '</div>'; // / updated
	} else {
		echo '<div class="updated">';
		echo '<p>'
		. '<a href="' . $support_url . '" target="_blank" title="Plugin FAQ">';
		echo __('Welcome to YD WPMU Bloglist Admin Page.', 'yd-wpmubl')
		. '</a></p>';
		echo '</div>'; // / updated
	}
	$options = get_option( 'widget_yd_wpmubl' );
	$i = 0;
	if( ! is_array( $options ) ) {
		// Something went wrong
		echo '<div class="updated">';
		echo __( 'Uh-oh. Looks like I lost my settings. Sorry.', 'yd-wpmubl' );
		echo '<form method="post" style="display:inline;">';
		echo '<input type="submit" name="do" value="' . __( 'Reset widget options', 'yd-wpmubl' ) . '"><br/>';
		echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
		echo '</form>';
		echo '</div>'; // / updated
		return false;
	}
	
	// ---
	// Right sidebar
	// ---
	
	echo '<div class="metabox-holder has-right-sidebar">';
	echo '<div class="inner-sidebar">';
	echo '<div class="meta-box-sortabless ui-sortable">';

	// == Block 1 ==

	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __( 'Considered donating?', 'yd-wpmubl' ) . '</h3>';
	echo '<div class="inside" style="text-align:center;"><br/>';
	echo '<a href="' . $support_url . '" target="_blank" title="Plugin FAQ" border="0">'
	. '<img src="' . $yd_logo . '" alt="YD logo" /></a>'
	. '<br/><small>' . __( 'Enjoy this plugin?', 'yd-wpmubl' ) . '<br/>' . __( 'Help me improve it!', 'yd-wpmubl' ) . '</small><br/>'
	. '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">'
	. '<input type="hidden" name="cmd" value="_s-xclick">'
	. '<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----'
	. '">'
	. '<input type="image" src="https://www.paypal.com/' . __( 'en_US', 'yd-wpmubl' ) . '/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">'
	. '<img alt="" border="0" src="https://www.paypal.com/' . __( 'en_US', 'yd-wpmubl' ) . '/i/scr/pixel.gif" width="1" height="1">'
	. '</form>'
	. '<small><strong>' . __( 'Thanks', 'yd-wpmubl' ) . ' - Yann.</strong></small><br/><br/>';
	
	//---
	echo '<form method="post" style="display:inline;">';
	//---
	
	echo '<table style="margin:10px;">';
	echo '<tr><td>' . __( 'Disable backlink in the blog footer:', 'yd-wpmubl' ) .
		'</td><td><input type="checkbox" name="yd_wpmubl-disable_backlink-0" value="1" ';
	if( $options[$i]["disable_backlink"] == 1 ) echo ' checked="checked" ';
	echo ' onclick="donatemsg()" ';
	echo ' /></td></tr>';
	echo '</table>';
	
	echo '</div>'; // / inside
	echo '</div>'; // / postbox
	
	// == Block 2 ==
	
	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __( 'Credits', 'yd-wpmubl' ) . '</h3>';
	echo '<div class="inside" style="padding:10px;">';
	echo '<b>' . __( 'Initial funding', 'yd-wpmubl' ) . '</b>';
	echo '<ul><li><a href="http://www.wellcom.fr">Wellcom.fr</a></li></ul>';
	echo '<b>' . __( 'Translations', 'yd-wpmubl' ) . '</b>';
	echo '<ul>';
	echo '<li>' . __( 'English:', 'yd-wpmubl' ) . ' <a href="http://www.yann.com">Yann</a></li>';
	echo '<li>' . __( 'French:', 'yd-wpmubl' ) . ' <a href="http://www.yann.com">Yann</a></li>';
	echo '<li>' . __( 'Spanish:', 'yd-wpmubl' ) . ' <a href="http://www.arumeinformatica.es/">Arume</a></li>';
	echo '<li>' . __( 'Galician:', 'yd-wpmubl' ) . ' <a href="http://www.arumeinformatica.es/">Arume</a></li>';
	echo '<li>' . __( 'Dutch:', 'yd-wpmubl' ) . ' Rene</li>';
	echo '<li>' . __( 'German:', 'yd-wpmubl' ) . ' <a href="http://www.pangaea.nl/diensten/exact-webshop">Rian Kramer</a></li>';
	echo '<li>' . __( 'Ukrainian:', 'yd-wpmubl' ) . ' Mikalay Lisica</li>';
	echo '<li>' . __( 'Indonesian:', 'yd-wpmubl' ) . ' Syamsul Alam</li>';
	echo '</ul>';
	echo __( 'If you want to contribute to a translation of this plugin, please drop me a line by ', 'yd-wpmubl' );
	echo '<a href="mailto:yann@abc.fr">' . __('e-mail', 'yd-wpmubl' ) . '</a> ';
	echo __( 'or leave a comment on the ', 'yd-wpmubl' );
	echo '<a href="' . $support_url . '">' . __( 'plugin\'s page', 'yd-wpmubl' ) . '</a>. ';
	echo __( 'You will get credit for your translation in the plugin file and the documentation page, ', 'yd-wpmubl' );
	echo __( 'as well as a link on this page and on my developers\' blog.', 'yd-wpmubl' );
		
	echo '</div>'; // / inside
	echo '</div>'; // / postbox
	
	// == Block 3 ==
	
	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __( 'Support', 'yd-wpmubl' ) . '</h3>';
	echo '<div class="inside" style="padding:10px;">';
	echo '<b>' . __( 'Free support', 'yd-wpmubl' ) . '</b>';
	echo '<ul>';
	echo '<li>' . __( 'Support page:', 'yd-wpmubl' );
	echo ' <a href="' . $support_url . '">' . __( 'here.', 'yd-wpmubl' ) . '</a>';
	echo ' ' . __( '(use comments!)', 'yd-wpmubl' ) . '</li>';
	echo '</ul>';
	echo '<p><b>' . __( 'Professional consulting', 'yd-wpmubl' ) . '</b><br/>';
	echo __( 'I am available as an experienced free-lance Wordpress plugin developer and web consultant. ', 'yd-wpmubl' );
	echo __( 'Please feel free to <a href="mailto:yann@abc.fr">check with me</a> for any adaptation or specific implementation of this plugin. ', 'yd-wpmubl' );
	echo __( 'Or for any WP-related custom development or consulting work. Hourly rates available.', 'yd-wpmubl' ) . '</p>';
	echo '</div>'; // / inside
	echo '</div>'; // / postbox
	
	echo '</div>'; // / meta-box-sortabless ui-sortable
	echo '</div>'; // / inner-sidebar

	// ---
	// Main content area
	// ---
	
	echo '<div class="has-sidebar sm-padded">';
	echo '<div id="post-body-content" class="has-sidebar-content">';
	echo '<div class="meta-box-sortabless">';
	
	// == HTML display options ==
	
	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __( 'HTML display options:', 'yd-wpmubl' ) . '</h3>';
	echo '<div class="inside">';
	echo '<table style="margin:10px;">';
	echo '<tr><td>' . __('Column count:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-column_count-0" value="' .
		htmlentities( $options[$i]["column_count"] ) . '" size="3" /></td></tr>';
	echo '<tr><td>' . __('Before block:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-before_block-0" value="' .
		htmlentities( $options[$i]["before_block"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('After block:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-after_block-0" value="' .
		htmlentities( $options[$i]["after_block"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('Before column:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-before_column-0" value="' .
		htmlentities( $options[$i]["before_column"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('After column:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-after_column-0" value="' .
		htmlentities( $options[$i]["after_column"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('Before list:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-before_list-0" value="' .
		htmlentities( $options[$i]["before_list"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('After list:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-after_list-0" value="' .
		htmlentities( $options[$i]["after_list"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('Before item:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-before_item-0" value="' .
		htmlentities( $options[$i]["before_item"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('After item:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-after_item-0" value="' .
		htmlentities( $options[$i]["after_item"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('Before count:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-before_count-0" value="' .
		htmlentities( $options[$i]["before_count"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('After count:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-after_count-0" value="' .
		htmlentities( $options[$i]["after_count"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>&nbsp;</td><td><em>' . 
		__( '(use %S% to insert plural form; use &lt!-- and --&gt; to hide count)', 'yd-wpmubl' ) . 
		'</em></td></tr>';
	echo '<tr><td>' . __('Plural form:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-plural_form-0" value="' .
		htmlentities( $options[$i]["plural_form"] ) . '" size="3" /></td></tr>';
	echo '<tr><td>' . __('Link ALT text:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-alt_text-0" value="' .
		htmlentities( $options[$i]["alt_text"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>' . __('Link TITLE text:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-title_text-0" value="' .
		htmlentities( $options[$i]["title_text"] ) . '" size="50" /></td></tr>';
	echo '<tr><td>&nbsp;</td><td><em>' .
		__( '(use %B% to insert blog name into link ALT or TITLE text)', 'yd-wpmubl' ) .
		'</em></td></tr>';
	echo '</table>';
	
	echo '</div>'; // / inside
	echo '</div>'; // / postbox
	
	// == Data extract and sort options ==
		
	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __('Data extract and sort options:', 'yd-wpmubl') . '</h3>';
	echo '<div class="inside">';
	echo '<table style="margin:10px;">';
	echo '<tr><td>' . __('Limit:', 'yd-wpmubl') .
		'</td><td><input type="text" name="yd_wpmubl-limit-0" value="' .
		htmlentities( $options[$i]["limit"] ) . '" size="3" /><em>' .
		__( '(0 = no limit)', 'yd-wpmubl' ) . '</em></td></tr>';
	echo '<tr><td>' . __('Order by:', 'yd-wpmubl') .
		'</td><td><select name="yd_wpmubl-order_by-0">';
	$aoptions = Array (
		'blogname' 	=> __('Blog name', 'yd-wpmubl'),
		'path'		=> __('Blog url path', 'yd-wpmubl'),
		'domain'	=> __('Blog url domain', 'yd-wpmubl'),
		'count'		=> __('Blog post count', 'yd-wpmubl'),
		'created'	=> __('Blog creation date', 'yd-wpmubl'),
		'updated'	=> __('Blog last update', 'yd-wpmubl'),
		'id'		=> __('Blog ID', 'yd-wpmubl')
	);
	foreach( $aoptions as $value=>$text ) {
		echo '<option ';
		if( $value == $options[$i]["order_by"] ) echo ' selected="selected" ';
		echo ' value="' . $value . '">' . $text . '</option>';
	}
	echo '</select></td></tr>';
	echo '<tr><td>' . __('Sort order:', 'yd-wpmubl') .
		'</td><td><select name="yd_wpmubl-order-0">';
	$aoptions = Array (
		'ASC' 		=> __('Ascending', 'yd-wpmubl'),
		'DESC'		=> __('Descending', 'yd-wpmubl')
	);
	foreach( $aoptions as $value=>$text ) {
		echo '<option ';
		if( $value == $options[$i]["order"] ) echo ' selected="selected" ';
		echo ' value="' . $value . '">' . $text . '</option>';
	}
	echo '</select></td></tr>';
	echo '<tr><td>' . __('Add trailing slashes to blog URLs:', 'yd-wpmubl') .
		'</td><td><input type="checkbox" name="yd_wpmubl-trailing_slash-0" value="1" ';
	if( $options[$i]["trailing_slash"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Enable WPML language filter support:', 'yd-wpmubl') .
		'</td><td><input type="checkbox" name="yd_wpmubl-wpml_support-0" value="1" ';
	if( $options[$i]["wpml_support"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '</table>';
	
	echo '</div>'; // / inside
	echo '</div>'; // / postbox	

	// == Blog selection options ==
		
	echo '<div class="postbox">';
	echo '<h3 class="hndle">' . __('Blog selection options:', 'yd-wpmubl') . '</h3>';
	echo '<div class="inside">';
	echo '<table style="margin:10px;">';
	
	// public = '1' 
	// $newvalue[0]['only_public']		= 1; 
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-only_public-0\">" 
			. __('Only propagate to blogs marked as public:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"checkbox\" name=\"yd_wpmubl-only_public-0\" value=\"1\" id=\"yd_wpmubl-only_public-0\" ";
	if( $options[$i]["only_public"] == 1 )
		echo ' checked="checked" ';	
	echo '/></td></tr>';
	
    // archived = '0' 
    // $newvalue[0]['skip_archived']	= 0; 
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-skip_archived-0\">" 
			. __('Skip blogs marked as archived:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"checkbox\" name=\"yd_wpmubl-skip_archived-0\" value=\"1\" id=\"yd_wpmubl-skip_archived-0\" ";
	if( $options[$i]["skip_archived"] == 1 )
		echo ' checked="checked" ';	
	echo '/></td></tr>';
			
	// mature = '0' 
    // $newvalue[0]['skip_mature']		= 0; 
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-skip_mature-0\">" 
			. __('Skip blogs marked as mature:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"checkbox\" name=\"yd_wpmubl-skip_mature-0\" value=\"1\" id=\"yd_wpmubl-skip_mature-0\" ";
	if( $options[$i]["skip_mature"] == 1 )
		echo ' checked="checked" ';	
	echo '/></td></tr>';
	
	// spam = '0'
	// $newvalue[0]['skip_spam']		= 0; 
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-skip_spam-0\">" 
			. __('Skip blogs marked as spam:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"checkbox\" name=\"yd_wpmubl-skip_spam-0\" value=\"1\" id=\"yd_wpmubl-skip_spam-0\" ";
	if( $options[$i]["skip_spam"] == 1 )
		echo ' checked="checked" ';	
	echo '/></td></tr>';
	
    // deleted ='0' 
	// $newvalue[0]['skip_deleted']			= 0; 
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-skip_deleted-0\">" 
			. __('Skip blogs marked as deleted:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"checkbox\" name=\"yd_wpmubl-skip_deleted-0\" value=\"1\" id=\"yd_wpmubl-skip_deleted-0\" ";
	if( $options[$i]["skip_deleted"] == 1 )
		echo ' checked="checked" ';	
	echo '/></td></tr>';
	
	// skip blogs
	// $newvalue[0]['to_skip']			= '';
	echo "
		<tr>
			<th scope=\"row\" align=\"right\"><label for=\"yd_wpmubl-to_skip-0\">" 
			. __('Skip blog IDs:', 'yd-wpmubl') . "
			</label></th>";
	echo "	<td><input type=\"text\" name=\"yd_wpmubl-to_skip-0\" value=\"" . $options[$i]["to_skip"];
	echo "\" id=\"yd_wpmubl-to_skip-0\" size=\"10\" />";
	echo '<em>' . __('(comma-separated list of blog IDs to avoid)', 'yd-wpmubl') . '</em>';
	echo "</td></tr>";
		
	echo '</table>';
	
	echo '</div>'; // / inside
	echo '</div>'; // / postbox		

	echo '<div>';
	echo '<p class="submit">';
	echo '<input type="submit" name="do" value="' . __('Update widget options', 'yd-wpmubl') . '">';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
	echo '</p>';
	echo '</form>';
	
	//---
	
	echo '<form method="post" style="display:inline;">';
	echo '<p class="submit">';
	echo '<input type="submit" name="do" value="' . __('Clear cache', 'yd-wpmubl') . '">';
	echo '<input type="submit" class="sm_warning" name="do" value="' . __('Reset widget options', 'yd-wpmubl') . '">';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';

	echo '</p>'; // / submit
	echo '</form>';
	echo '</div>'; // /
	
	echo '</div>'; // / meta-box-sortabless
	echo '</div>'; // / has-sidebar-content
	echo '</div>'; // / has-sidebar sm-padded
	echo '</div>'; // / metabox-holder has-right-sidebar
	echo '</div>'; // /wrap
}

/** Update display options of the options admin page **/
function yd_wpmubl_update_options(){
	$to_update = Array(
		'column_count',
		'before_block',
		'after_block',
		'before_column',
		'after_column',
		'before_list',
		'after_list',
		'before_item',
		'after_item',
		'before_count',
		'after_count',
		'plural_form',
		'alt_text',
		'title_text',
		'limit',
		'order_by',
		'order',
		'trailing_slash',
		'disable_backlink',
		'wpml_support',
		'only_public', 
    	'skip_archived', 
    	'skip_mature',
    	'skip_spam',
    	'skip_deleted', 
		'to_skip'
	);
	if( yd_update_options_nostrip( 'widget_yd_wpmubl', 0, $to_update, $_POST, 'yd_wpmubl-' ) ) {
		clear_yd_widget_cache( 'wpmubl' );
	}
}

/** Add links on the plugin page (short description) **/
add_filter( 'plugin_row_meta', 'yd_wpmubl_links' , 10, 2 );
function yd_wpmubl_links( $links, $file ) {
	$base = plugin_basename(__FILE__);
	if ( $file == $base ) {
		$links[] = '<a href="options-general.php?page=yd-wpmu-bloglist-widget%2Fyd-wpmu-bloglist-widget.php">' . __('Settings') . '</a>';
		$links[] = '<a href="http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget">' . __('Support') . '</a>';
	}
	return $links;
}
function yd_wpmubl_action_links( $links ) {
	$settings_link = '<a href="options-general.php?page=yd-wpmu-bloglist-widget%2F' . basename( __FILE__ ) . '">' . __('Settings') . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'yd_wpmubl_action_links', 10, 4 );

/** Display with PHP outside widget functions **/
function yd_display_wpmu_bloglist( $echo = TRUE, $params = NULL ) {
	$html = '';
	if( $echo && $echo !== TRUE && $params === NULL ) {
		// shift parameters
		$params = $echo;
		$echo = TRUE;
	}
	//$html .= '<ul>';
	$html .= yd_list_all_wpmu_blogs( $params );
	//$html .= '</ul>';
	if( isset( $echo ) && $echo !== FALSE ) {
		echo $html;
	} else {
		return $html;
	}
}

/** Display drop-down menu **/
function yd_wpmu_bloglist_dropdown( $echo = TRUE, $params = NULL ) {
	$html = '';
	if( $echo && $echo !== TRUE && $params === NULL ) {
		// shift parameters
		$params = $echo;
		$echo = TRUE;
	}
	
	$dd = preg_replace( '/<a href=/', '<option value=', yd_list_all_wpmu_blogs( $params ) );
	//$dd = preg_replace( '/title="[^"]*"/', '', $dd );
	$dd = preg_replace( '|</a>|', '</option>', $dd );
	$html .= $dd;
	if( isset( $echo ) && $echo !== FALSE ) {
		echo $html;
	} else {
		return $html;
	}
}

/** Javascript for drop-down **/
function yd_wpmubl_dropdown_js() {
	?>
	<script>function ddjump(o){document.location=o.options[o.selectedIndex].value;}</script>
	<?php
}

/** Display inside content **/
function yd_wpmubl_generate( $content ) {
	if (strpos($content, "[!YDWPMUBL]") !== FALSE) {
		$content = preg_replace('/<p>\s*\[!(.*)\]\s*<\/p>/i', "[!$1]", $content);
		$content = str_replace('[!YDWPMUBL]', yd_display_wpmu_bloglist( FALSE ), $content);
	}
	return $content;
}
add_filter('the_content', 'yd_wpmubl_generate');

/** Widget function: WPMU Bloglist **/
function widget_yd_wpmubl( $args, $cache_name = NULL, $spec_query = NULL ) {
	if( isset( $args ) && $args === FALSE ) {
		$echo = FALSE;
	} else {
		if( is_array( $args ) ) extract( $args );
		$echo = TRUE;
	}
	$default_cutlength = 128;
	global $wpdb;
	global $user_level;
	$plugin_dir = 'yd-wpmu-bloglist-widget';
	$options = get_option('widget_yd_wpmubl');
	$current_querycount = get_num_queries();
	$html = '';
	$i = 1;
	$title = $options[$i]['widget_title'];
	if( is_admin() ) return;
	if( !check_yd_widget_cache( 'wpmubl' ) ) {
		$html .= $before_widget;
		if( $title )
		$html .= $before_title . $title . $after_title;
		$html .= '<div class="yd_wpmubl">';
		
		$html .= yd_list_all_wpmu_blogs();
		
		$html .= '<a href="' . $bottom_link . '">' . $bottom_text . '</a>';
		$html .= '</div>' . $after_widget;
		update_yd_widget_cache( 'widget_yd_wpmubl_' . $cache_key, $html );
	} else {
		//echo "FROM CACHE<br/>";
		$html = get_yd_widget_cache( 'wpmubl' );
	}
	if( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}

/** Widget options **/
function widget_yd_wpmubl_control($number) {
	$options = get_option( 'widget_yd_wpmubl' );
	$to_update = Array(
		'widget_title'
	);
	if ( $_POST["yd_wpmubl-submit-$number"] ) {
		if( yd_update_options( 'widget_yd_wpmubl', $number, $to_update, $_POST, 'yd_wpmubl-' ) ) {
			clear_yd_widget_cache( 'wpmubl' );
		}
	}
	foreach( $to_update as $key ) {
		$v[$key] = htmlspecialchars( $options[$number][$key], ENT_QUOTES );
	}
	?>
<div style="float: right"><a
	href="http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget"
	title="Help!" target="_blank">?</a></div>
<strong><?php echo __('Widget title:', 'yd-wpmubl') ?></strong>
<br />
<input
	style="width: 450px;" id="yd_wpmubl-widget_title-<?php echo "$number"; ?>"
	name="yd_wpmubl-widget_title-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['widget_title']; ?>" />
<hr />

<input
	type="hidden" id="yd_wpmubl-submit-<?php echo "$number"; ?>"
	name="yd_wpmubl-submit-<?php echo "$number"; ?>" value="1" />
	<?php
}

function widget_wpmubl_init() {
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
	return;
	register_sidebar_widget( __('YD WPMU Bloglist', 'yd-wpmubl'), 'widget_yd_wpmubl' );
	register_widget_control( __('YD WPMU Bloglist', 'yd-wpmubl'), 'widget_yd_wpmubl_control', 470, 470, 1 );
}

// Tell Dynamic Sidebar about our new widget and its control
add_action('plugins_loaded', 'widget_wpmubl_init');

// ============================ Credits for original code of this function go to: ====
/* 
								Andrew Billits
								http://www.wpmudev.org/project/list-all
*/

function yd_list_all_wpmu_blogs( $params = NULL ) {
	$html = '';
	$options = get_option( 'widget_yd_wpmubl' );
	$i = 0;
	if( $params ) {
		//parse_str( $params, $args );
		//$options[$i] = array_merge( (array)$options[$i], $args ); // parameter overloading
		$options[$i] = wp_parse_args( $params, $options[$i] );
	}
	/* *
	echo '<pre>';
	var_dump( $options );
	echo '</pre>';
	* */
    // html display options
    if ($tmp_name_or_url == "") {
        $name_or_url = "name";
    } else {
        if ($tmp_name_or_url == "name") {
            $name_or_url = "name";
        } else {
            $name_or_url = "url";
        }
    }
    if (tmp_begin_wrap == "" || tmp_end_wrap == "" ) {
        $begin_wrap = "<p>";
        $end_wrap = "</p>";
    } else {
        $begin_wrap = $tmp_begin_wrap;
        $end_wrap = $tmp_end_wrap;
    }
    if( $blog_list = yd_get_all_wpmu_blogs( $params ) ) {
    	// column count calculation
    	$blog_count = count( $blog_list );
    	if( !isset( $options[$i]['column_count'] ) || $options[$i]['column_count'] == 0 )
    		$options[$i]['column_count'] = 1;
    	$item_per_column = floor( $blog_count / $options[$i]['column_count'] );
    	$remaining_items = $blog_count - ( $item_per_column * $options[$i]['column_count'] );
    	$item = 0;
    	$col = 0;
    	$html .= $options[$i]['before_block'];
    	$current_group = '';

    	foreach( $blog_list as $blog ) {
    		if( $item == 0 && $col < $options[$i]['column_count'] ) {
    			$html .= $options[$i]['before_column'];
    			$html .= $options[$i]['before_list'];
    			$open_column = true;
    			$col ++;
    		    /** Group_by hierarchy / display group_by tag criteria **/
		    	if( isset($options[$i]['group_by']) && !empty($options[$i]['group_by']) ) {
		    		if( isset($options[$i]['before_groupby']) ) {
		    			$html .= $options[$i]['before_groupby'];
		    		} else {
		    			$html .= '<ul class="groupby">';
		    		}
		    		$mygroup = $blog[$options[$i]['group_by']];
    				if( $mygroup != $current_group ) {
		    			$html .= '<li class="groupname">' . $mygroup . '</li>';
		    			$item ++;
    				}
    				$current_group = $blog[$options[$i]['group_by']];
		    	}
    		} else {
    			if( isset($options[$i]['group_by']) && !empty($options[$i]['group_by']) ) {
    				$mygroup = $blog[$options[$i]['group_by']];
    				if( $mygroup != $current_group ) {
    					$current_group = $mygroup;
    					$html .= '</li></ul>';
    					$html .= '<ul class="groupby"><li class="groupname">';
		    			$html .= $current_group . '</li>';
		    			$item ++;
    				}
    			}
    		}
    		$item ++;
    		if( $blog['post_count'] > 1 ) $s = preg_replace( '/"/', '&quot;', $options[$i]['plural_form'] );
    			else $s = ''; // plural
    		$html .= 	$options[$i]['before_item'] . 
    					'<a href="' . $blog['siteurl'] . '" ';
    		if ( '' != $options[$i]['alt_text'] ) {
    			// alt attribute in <a> tag is not valid xhtml: thanks to Arume for pointing this!
    			$html .= 	' alt="' . preg_replace( "/%B%/", preg_replace( '/"/', '&quot;', $blog['blogname'] ), $options[$i]['alt_text'] ) . '" ';
    		}
    		if( !isset($blog['blogname']) || empty($blog['blogname']) )
    			$blog['blogname'] = __( 'Untitled', 'yd-wpmubl' );
    		$html .= 	' title="' . preg_replace( "/%B%/", preg_replace( '/"/', '&quot;', $blog['blogname'] ), $options[$i]['title_text'] ) . '" ' .
    					' >' .
    					$blog['blogname'] . //$item . 
    					'</a>';
    		if( !isset($options[$i]['show_count']) || $options[$i]['show_count'] ) {
    			$html .= 	preg_replace( "/%S%/", $s, $options[$i]['before_count'] ) .
    						$blog['post_count'] . 
    						preg_replace( "/%S%/", $s, $options[$i]['after_count'] );
    		}
    		$html .= 	$options[$i]['after_item'];
    		if( 
    			(	
    				( $col == 1 && $item >= $item_per_column + $remaining_items )
    				||
    				( $col > 1 && $item >= $item_per_column )
    			)
    			&& 	$col < $options[$i]['column_count'] 
    			&& 	$open_column 
    		) {
    			$html .= $options[$i]['after_list'];
    			$html .= $options[$i]['after_column'];
    			$item = 0;
    			$open_column = false;
    		}
    	}
    	if( $open_column ) {
	    	/** Group_by hierarchy / display group_by tag criteria **/
	    	if( isset($options[$i]['group_by']) && !empty($options[$i]['group_by']) ) {
	    		if( isset($options[$i]['after_groupby']) ) {
	    			$html .= $options[$i]['after_groupby'];
	    		} else {
	    			$html .= '</li></ul>';
	    		}
	    	}
    		$html .= $options[$i]['after_list'];
    		$html .= $options[$i]['after_column'];
    	}
    	$html .= $options[$i]['after_block'];
    } else {
        $html .= $begin_wrap . __('There are currently no active blogs.', 'yd-wpmubl') . $end_wrap;
    }
    $html = apply_filters( 'ydwpmubl_html', $html );
    return $html;
}

function yd_get_all_wpmu_blogs( $params = NULL ) {
	global $wpdb;
	$options = get_option( 'widget_yd_wpmubl' );
	$i = 0;
	if( $params ) {
		//parse_str( $params, $args );
		//$options[$i] = array_merge( (array)$options[$i], $args ); // parameter overloading
		$options[$i] = wp_parse_args( $params, $options[$i] );
	}
	// sql options
	$order = '';
    if ( 
    	intval( $options[$i]['limit'] ) == 0 
    	|| $options[$i]['order_by'] == "blogname" 
    	|| $options[$i]['order_by'] == "count" 
    ) {
    	// sql limit won't work with array-ordered lists
        //no limit
    } else {
        $limit = "LIMIT " . intval( $options[$i]['limit'] );
    }
    /* available order_by options
    	$aoptions = Array (
		'blogname' 	=> __('Blog name', 'yd-wpmubl'),
		'path'		=> __('Blog url path', 'yd-wpmubl'),
		'domain'	=> __('Blog domain', 'yd-wpmubl'),
		'count'		=> __('Blog post count', 'yd-wpmubl'),
		'created'	=> __('Blog creation date', 'yd-wpmubl'),
		'updated'	=> __('Blog last update', 'yd-wpmubl')
		'id'		=> __('Blog ID', 'yd-wpmubl')
	);
		// credits go to Arume for pointing the bug in 0.1.0
	*/
    if( '' == $options[$i]['order_by'] || 'id' == $options[$i]['order_by'] ) {
        $order = "ORDER BY blog_id";
    } else {
    	$order = "ORDER BY blog_id";
        if ( 'updated' == $options[$i]['order_by'] ) {
            $order = "ORDER BY  last_updated";
        }
        if ( 'created' == $options[$i]['order_by'] ) {
            $order = "ORDER BY  blog_id";
        }
		if ( 'path' == $options[$i]['order_by'] ) {
            $order = "ORDER BY  path";
        }
   		if ( 'domain' == $options[$i]['order_by'] ) {
            $order = "ORDER BY  domain";
        }
    }
    if( 'DESC' == $options[$i]['order'] ) {
    	$order .= ' DESC';
    } else {
    	$order .= ' ASC';
    }

    if( $options[$i]['wpml_support'] && defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE != 'in' ) {
    	$query = "
	    	SELECT DISTINCT
	    		b.blog_id, 
	    		b.domain, 
	    		b.last_updated
		    FROM 
		    	" . $wpdb->blogs . " AS b,
		    	" . $wpdb->postmeta . " AS m,
		    	" . $wpdb->prefix . "icl_translations AS t
		    WHERE 1
		    AND	t.language_code = '" . ICL_LANGUAGE_CODE . "'
		    AND t.element_type = 'post_post'
		    AND t.element_id = m.post_id
		    AND m.meta_key = 'blogid'
		    AND m.meta_value = b.blog_id
    	";
    } else {
    	$query = "	
    		SELECT blog_id, domain, last_updated 
	    	FROM " . $wpdb->blogs . " AS b
	     	WHERE 1
		";
    }
    if( 
    	$options[$i]['only_public']		||
    	$options[$i]['skip_archived']	|| 
    	$options[$i]['skip_mature']		|| 
    	$options[$i]['skip_spam']		|| 
    	$options[$i]['skip_deleted']	|| 
		$options[$i]['to_skip']	!= ''
	) {
		if( $options[$i]['only_public'] ) 	$query .= " AND b.public='1' ";
		if( $options[$i]['skip_archived'] )	$query .= " AND b.archived='0' ";
		if( $options[$i]['skip_mature'] ) 	$query .= " AND b.mature='0' ";
		if( $options[$i]['skip_spam'] ) 	$query .= " AND b.spam='0' ";
		if( $options[$i]['skip_deleted'] )	$query .= " AND b.deleted='0' ";
		if( $options[$i]['to_skip']	!= '' && preg_match( '/^(\d+,?\s*)+$/', $options[$i]['to_skip'] ) ) {
			$query .= ' AND b.blog_id NOT IN (' . $options[$i]['to_skip'] . ')';
		}
	}
	$query .= " $order ";
	$query .= " $limit ";
	$query = apply_filters( 'ydwpmubl_query', $query );
	$blog_list = $wpdb->get_results( $query, ARRAY_A );
	$blog_list = apply_filters( 'ydwpmubl_sort', $blog_list, 'results' );
	
	//echo $query;
    if( count( $blog_list ) > 1 ) {
    	foreach( $blog_list as $id => $blog ) {
    		$blog_list[$id]['post_count']	= get_blog_option( $blog['blog_id'], 'post_count' );
    		$blog_list[$id]['blogname']		= get_blog_option( $blog['blog_id'], 'blogname' );
    		if( isset( $options[$i]['group_by'] ) && !empty( $options[$i]['group_by'] ) ) {
    			$blog_list[$id][$options[$i]['group_by']] = get_blog_option( $blog['blog_id'], $options[$i]['group_by'] );
    		}
			// get blog url depending on vhost or not-vhost installtion
			if( defined( "VHOST" ) && constant( "VHOST" ) == 'yes' )
				$blog_list[$id]['siteurl'] = $blog_list[$id]['domain'];
			else
				$blog_list[$id]['siteurl']	= get_blog_option( $blog['blog_id'], 'siteurl' );
			if( $options[$i]['trailing_slash'] )
				$blog_list[$id]['siteurl']	= 
					preg_replace( '|/+$|', '', $blog_list[$id]['siteurl'] )
						. '/';
			if( !preg_match( '|^http://|', $blog_list[$id]['siteurl'] ) )
				$blog_list[$id]['siteurl'] = 'http://' . $blog_list[$id]['siteurl'];
					// "duplicated links" bug??
    	}
    	
    	// $blog_list array sort
    	if ( 'blogname' == $options[$i]['order_by'] ) {
    		if( 'DESC' == $options[$i]['order'] ) {
            	usort( $blog_list, 'yd_asort_by_blogname_desc' );
    		} else {
    			usort( $blog_list, 'yd_asort_by_blogname' );
    		}
    		if( 0 != intval( $options[$i]['limit'] ) ) {
    			array_splice( $blog_list, $options[$i]['limit'] );
    		}
        } elseif ( 'count' == $options[$i]['order_by'] ) {
        	if( 'DESC' == $options[$i]['order'] ) {
            	usort( $blog_list, 'yd_asort_by_postcount_desc' );
        	} else {
    			usort( $blog_list, 'yd_asort_by_postcount' );
    		}
    		if( 0 != intval( $options[$i]['limit'] ) ) {
    			array_splice( $blog_list, $options[$i]['limit'] );
    		}
        }
        $blog_list = apply_filters( 'ydwpmubl_sort', $blog_list, 'after_sort' );
  		
        /** group by blog option **/
        if( isset( $options[$i]['group_by'] ) && !empty( $options[$i]['group_by'] ) ) {
        	$blc = count( $blog_list );
        	$blw = strlen( (string)$blc );
        	foreach( $blog_list as $key => $val ) {
        		$lf = $blw - strlen( (string)$key );
        		if( $lf > 0 ) $key = str_repeat( '0', $lf ) . $key;
        		$blog_list[$key]['order'] = (string)$key; // left-filled with appropriate number of zeroes
        	}
        	global $YDWPMUSOGB;
        	$YDWPMUSOGB = $options[$i]['group_by'];
        	usort( $blog_list, 'yd_asort_groupby' );
        	//echo "CONTINENT\n";
        }
        $blog_list = apply_filters( 'ydwpmubl_groupby', $blog_list, 'after_groupby' );
        
        return( $blog_list );
    } else {
    	return FALSE;
    }
}
function yd_asort_by_blogname( $a, $b ) {
	return strcmp( strtolower( $a["blogname"] ), strtolower( $b["blogname"] ) );
}
function yd_asort_by_postcount( $a, $b ) {
	return ( $a["post_count"] > $b["post_count"] );
}
function yd_asort_by_blogname_desc( $b, $a ) {
	return strcmp( strtolower( $a["blogname"] ), strtolower( $b["blogname"] ) );
}
function yd_asort_by_postcount_desc( $b, $a ) {
	return ( $a["post_count"] > $b["post_count"] );
}
function yd_asort_groupby( $a, $b ) {
	global $YDWPMUSOGB;
	return strcmp( strtolower( $a[$YDWPMUSOGB] . $a['order'] ), strtolower( $b[$YDWPMUSOGB] . $b['order'] ) );
}

function yd_linkware() {
	$options = get_option( 'widget_yd_wpmubl' );
	$i = 0;
	if( $options[$i]['disable_backlink'] ) echo "<!--\n";
	echo '<p style="text-align:center" class="yd_linkware"><small><a href="http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget">Featuring WPMU Bloglist Widget by YD WordPress Developer</a></small></p>';
	if( $options[$i]['disable_backlink'] ) echo "\n-->";
}
add_action('wp_footer', 'yd_linkware');

// ============================ Generic YD WP functions ==============================

include( 'yd-wp-lib.inc.php' );

if( !function_exists( 'yd_update_options_nostrip' ) ) {
	function yd_update_options_nostrip( $option_key, $number, $to_update, $fields, $prefix ) {
		$options = $newoptions = get_option( $option_key );
		if( ! is_array( $options ) ) {
			echo "Uh-oh. Options string was broken. I probably lost my settings.";
			$newoptions = array();
		}
		foreach( $to_update as $key ) {
			//$newoptions[$number][$key] = html_entity_decode( stripslashes( $fields[$prefix . $key . '-' . $number] ) );
			$newoptions[$number][$key] = stripslashes( $fields[$prefix . $key . '-' . $number] );
			//echo $key . " = " . $prefix . $key . '-' . $number . " = " . $newoptions[$number][$key] . "<br/>";
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option( $option_key, $options );
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if( !function_exists( 'check_yd_widget_cache' ) ) {
	function check_yd_widget_cache( $widg_id ) {
		$option_name = 'yd_cache_' . $widg_id;
		$cache = get_option( $option_name );
		//echo "rev: " . $cache["revision"] . " - " . get_yd_cache_revision() . "<br/>";
		if( $cache["revision"] != get_yd_cache_revision() ) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}

if( !function_exists( 'update_yd_widget_cache' ) ) {
	function update_yd_widget_cache( $widg_id, $html ) {
		//echo "uwc " . $widg_id;
		$option_name = 'yd_cache_' . $widg_id;
		$nvarr["html"] = $html;
		$nvarr["revision"] = get_yd_cache_revision();
		$newvalue = $nvarr;
		if ( get_option( $option_name ) ) {
			update_option( $option_name, $newvalue );
		} else {
			$deprecated=' ';
			$autoload='no';
			add_option($option_name, $newvalue, $deprecated, $autoload);
		}
	}
}

if( !function_exists( 'get_yd_widget_cache' ) ) {
	function get_yd_widget_cache( $widg_id ) {
		$option_name = 'yd_cache_' . $widg_id;
		$nvarr = get_option( $option_name );
		return $nvarr["html"];
	}
}

if( !function_exists( 'clear_yd_widget_cache' ) ) {
	function clear_yd_widget_cache( $widg_id ) {
		$caches = yd_get_all_widget_caches( 'yd_cache_' );
		foreach( $caches as $cache_name ) {
			$option_name = 'yd_cache_' . $widg_id;
			$nvarr["html"] = __('clear', 'yd-wpmubl');
			$nvarr["revision"] = 0;
			$newvalue = $nvarr;
			update_option( $option_name, $newvalue );
		}
	}
}
if( !function_exists( 'yd_get_all_widget_caches') ) {
	function yd_get_all_widget_caches( $widget_prefix ) {
		global $wpdb;
		$query = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '$widget_prefix%'";
		return $wpdb->get_col( $query );
	}
}

if( !function_exists( 'get_yd_cache_revision' ) ) {
	function get_yd_cache_revision() {
		global $wpdb;
		return $wpdb->get_var( "SELECT max( ID ) FROM " . $wpdb->posts .
			" WHERE post_type = 'post' and post_status = 'publish'" );
	}
}
?>