<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.1 Alpha 1
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

/**
 * Initialize the template... mainly little settings.
 */
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	// The version this template/theme is for. This should probably be the version of SMF it was created for.
	$settings['theme_version'] = '2.0';

	// Set a setting that tells the theme that it can render the tabs.
	$settings['use_tabs'] = true;

	// Use plain buttons - as opposed to text buttons?
	$settings['use_buttons'] = true;

	// Show sticky and lock status separate from topic icons?
	$settings['separate_sticky_lock'] = true;

	// Does this theme use the strict doctype?
	$settings['strict_doctype'] = false;

	// Set the following variable to true if this theme requires the optional theme strings file to be loaded.
	$settings['require_theme_strings'] = false;
}

/**
 * The main sub template above the content.
 */
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?alp21 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?alp21" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	// Note: Commented this out as it will be unnecessary if we go ahead with setting browser as id on <body>.
//	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
//		if ($context['browser']['is_' . $cssfix])
//			echo '
//	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// load in any css from mods or themes so they can overwrite if wanted
	template_css();

	// Jquery Librarys
	if (isset($modSettings['jquery_source']) && $modSettings['jquery_source'] == 'cdn')
		echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>';
	elseif (isset($modSettings['jquery_source']) && $modSettings['jquery_source'] == 'local')
		echo '
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery-1.7.1.min.js"></script>';
	else
		echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		window.jQuery || document.write(\'<script src="', $settings['theme_url'], '/scripts/jquery-1.7.1.min.js"><\/script>\');
	// ]]></script>';

	// Note that the Superfish function seems to like being called by the full syntax.
	// It doesn't appear to like being called by short syntax. Please test if contemplating changes.
	echo '
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/hoverIntent.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/superfish.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery.bt.js"></script>';

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?alp21"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?alp21"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";
		var smf_session_id = "', $context['session_id'], '";
		var smf_session_var = "', $context['session_var'], '";
		var smf_member_id = "', $context['user']['id'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		$(document).ready(function() { 
			$("ul.dropmenu, ul.quickbuttons, div.poster ul").superfish();
		});
	// ]]></script>';

	// load in any javascript files from mods and themes
	template_javascript();
		
	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="contents" href="', $scripturl, '" />', ($context['allow_search'] ? '
	<link rel="search" href="' . $scripturl . '?action=search" />' : '');

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss2;action=.xml" />
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['atom'], '" href="', $scripturl, '?type=atom;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	// Note: These have been modified to add additional functionality. Has already been tested live for two years.
	// Please see http://www.simplemachines.org/community/index.php?topic=210212.msg2546739#msg2546739
	// and
	// http://www.simplemachines.org/community/index.php?topic=210212.msg2548628#msg2548628 for further details.
	if (!empty($context['links']['next']))
		echo '
	<link rel="next" href="', $context['links']['next'], '" />';
	else if (!empty($context['current_topic']))
		echo '
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';
	if (!empty($context['links']['prev']))
		echo '
	<link rel="prev" href="', $context['links']['prev'], '" />';
	else if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body id="', $context['browser_body_id'], '" class="action_', !empty($context['current_action']) ? htmlspecialchars($context['current_action']) : (!empty($context['current_board']) ? 'messageindex' : (!empty($context['current_topic']) ? 'display' : 'home')),
	!empty($context['current_board']) ? ' board_' . htmlspecialchars($context['current_board']) : '',
	'">';
}

function template_body_above()
{
	// Note: Globals updated to allow linking member avatar to their profile.
	global $context, $settings, $options, $scripturl, $txt, $modSettings, $member;

	// Note: Echo a true top-of-page link. This is much better than just having the standard SMF "Go up" and "Go down" links.
	// Not that I have anything against going down, you understand. It's just that if one is going down one should do it properly.
	echo '
	<a id="top_most"></a>';

	// Note: Header div is now full width.
	// We could change this later to an HTML5 <header> tag if we use javascript to create some useful HTML5 elements for IE<9.
	// Example javascript here: http://www.nickyeoman.com/blog/html/118-html5-tags-in-ie8
	// Note: Wrapper has been changed from an id to a class, to enable better theming options, and the ability to set forum width on the theme settings page has been removed.
	// Wrapper width is now set directly in index.css, as this is the only practical way of supporting an adaptable layout that will cope with mobile devices as well as desktop.

	echo '

	<div id="top_section">
		<div class="wrapper">
			<div class="frame">';

	// Note: Markup past this point may be slightly WIP. Wear your armour-plated undies.
	if ($context['user']['is_logged'])
	{
		if (!empty($context['user']['avatar']))
	echo '
				<a href="', $scripturl, '?action=profile" class="avatar">', $context['user']['avatar']['image'], '</a>';

	echo '
				<ul>
					<li class="greeting">', $txt['hello_member_ndt'], ' <span>', $context['user']['name'], '</span>';
	// Is the forum in maintenance mode?
	if (($context['allow_moderation_center'] && ($context['in_maintenance'])||!empty ($context['unapproved_members'])||!empty($context['open_mod_reports'])))
		{
		echo '
						<ul id="top_bar_notifications">';
		if ($context['in_maintenance'])
			echo '
							<li class="notice">', $txt['maintain_mode_on'], '</li>';

		if (!empty ($context['unapproved_members']))
			echo '
							<li>', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve" style="font-weight: bold;">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], ' ', $txt['approve_members_waiting'], '</a></li>';

		if (!empty($context['open_mod_reports']))
			echo '
							<li><a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';

			echo '
						</ul>';
		}
		echo '
					</li>
					<li>', $context['current_time'], '</li>
				</ul>';
	}

	// the upshrink image, right-floated
	echo '
				<img id="upshrink" src="', $settings['images_url'], '/upshrink.png" alt="*" title="', $txt['upshrink_description'], '" style="display: none;" />';

	echo '
				<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input type="text" name="search" value="" class="input_text" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" />';

	// Search within current topic?
	if (!empty($context['current_topic']))
		echo '
					<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
	echo '
					<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '
				</form>';

	echo '
			</div>
		</div>
	</div>
	<div id="header">
		<div class="wrapper">
			<div class="frame">
				<h1 class="forumtitle">
					<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
				</h1>

	', empty($settings['site_slogan']) ? '<img id="smflogo" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<div id="siteslogan">' . $settings['site_slogan'] . '</div>', '

				<div id="upper_section" ', empty($options['collapse_header']) ? '' : ' style="display: none;"', '>
					<div class="news" id="news_collapse">';

	// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
	if (!empty($context['show_login_bar']))
	{
		echo '
						<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
						<form id="guest_form" style="float: left; margin-left: 0; width: auto;" action="', $scripturl, '?action=login2;quicklogin" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
							<div class="info">', sprintf($txt[$context['can_register'] ? 'welcome_guest_register' : 'welcome_guest'], $txt['guest_title'], $scripturl . '?action=login'), '</div>
							<input type="text" name="user" size="10" class="input_text" />
							<input type="password" name="passwrd" size="10" class="input_password" />
							<select name="cookielength">
								<option value="60">', $txt['one_hour'], '</option>
								<option value="1440">', $txt['one_day'], '</option>
								<option value="10080">', $txt['one_week'], '</option>
								<option value="43200">', $txt['one_month'], '</option>
								<option value="-1" selected="selected">', $txt['forever'], '</option>
							</select>
							<input type="submit" value="', $txt['login'], '" class="button_submit" /><br />
							<div class="info">', $txt['quick_login_dec'], '</div>';

		if (!empty($modSettings['enableOpenID']))
			echo '
							<br /><input type="text" name="openid_identifier" size="25" class="input_text openid_login" />';

		echo '
							<input type="hidden" name="hash_passwrd" value="" />
							<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
							<input type="hidden" name="', $context['login_token_var'], '" value="', $context['login_token'], '" />
						</form>';
	}

	// Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news']))
		echo '
							<h2>', $txt['news'], ': </h2>
							<p>', $context['random_news_line'], '</p>';

	echo '
					</div>
				</div>';

	// Define the upper_section toggle in JavaScript.
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			var oMainHeaderToggle = new smc_Toggle({
				bToggleEnabled: true,
				bCurrentlyCollapsed: ', empty($options['collapse_header']) ? 'false' : 'true', ',
				aSwappableContainers: [
					\'upper_section\'
				],
				aSwapImages: [
					{
						sId: \'upshrink\',
						srcExpanded: smf_images_url + \'/upshrink.png\',
						altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
						srcCollapsed: smf_images_url + \'/upshrink2.png\',
						altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
					}
				],
				oThemeOptions: {
					bUseThemeSettings: smf_member_id == 0 ? false : true,
					sOptionName: \'collapse_header\',
					sSessionVar: smf_session_var,
					sSessionId: smf_session_id
				},
				oCookieOptions: {
					bUseCookie: smf_member_id == 0 ? true : false,
					sCookieName: \'upshrink\'
				}
			});
		// ]]></script>';

	// Show the menu here, according to the menu sub template.
	template_menu();

	echo '
				<br class="clear" />
			</div>
		</div>
	</div>';

	// The main content should go here.
	echo '
	<div id="content_section">
		<div class="wrapper">
			<div class="frame">';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
	theme_linktree();

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
			</div>
		</div>
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<div id="footer_section">
		<div class="wrapper">
			<div class="frame">
				<ul>
					<li class="copyright">', theme_copyright(), '</li>
					<li><a id="button_xhtml" href="http://validator.w3.org/check?uri=referer" target="_blank" class="new_win" title="', $txt['valid_xhtml'], '"><span>', $txt['xhtml'], '</span></a></li>
					', !empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']) ? '<li><a id="button_rss" href="' . $scripturl . '?action=.xml;type=rss" class="new_win"><span>' . $txt['rss'] . '</span></a></li>' : '', '
					<li class="last"><a id="button_wap2" href="', $scripturl , '?wap2" class="new_win"><span>', $txt['wap2'], '</span></a></li>
				</ul>
				<a class="topmost" href="#top_most"></a>';

	// Show the load time?
	if ($context['show_load_time'])
	echo '
				<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	// Note: A bottom-of-page anchor has been inserted here to match the top-of-page anchor. 
	echo '
			</div>
		</div>
	</div><a id="bottom_most"></a>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	template_javascript(true);

	echo '
</body></html>';
}

/**
 * Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
 * @param bool $force_show = false
 */
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree, $txt, $scripturl;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
		<div class="navigate_section">
			<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
				<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
					<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
				</li>';
	}
	echo '
			</ul>';

	if ($context['user']['is_logged'])
	{
	echo '
			<ul class="unread_links">
				<li><a href="', $scripturl, '?action=unread">'. $txt['unread_since_visit']. '</a>&nbsp;|</li>
				<li><a href="', $scripturl, '?action=unreadreplies">'. $txt['show_unread_replies']. '</a></li>
			</ul>';
	}
	echo '
		</div>';

	$shown_linktree = true;
}

/**
 * Show the menu up top. Something like [home] [help] [profile] [logout]...
 */
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div id="main_menu">
			<ul class="dropmenu" id="menu_nav">';

	// Note: Menu markup has been cleaned up to remove unnecessary spans and classes. 
	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active' : '', !empty($button['is_last']) ? ' last' : '', '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						', $button['title'], '
					</a>';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '" ', isset($childbutton['is_last']) ? 'class="last"' : '' , isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								', $childbutton['title'], '
							</a>';
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '" ', isset($grandchildbutton['is_last']) ? ' class="last"' : '' , isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										', $grandchildbutton['title'], '
									</a>
								</li>';

					echo '
							</ul>';
				}

				echo '
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			</ul>
		</div>';
}

/**
 * Generate a strip of buttons.
 * @param array $button_strip
 * @param string $direction = ''
 * @param array $strip_options = array()
 */
function template_button_strip($button_strip, $direction = '', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Can we have a first one too?.
	$buttons[0] = str_replace('" href="', ' first" href="', $buttons[0]);

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('" href="', ' last" href="', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

/**
 * Output the Javascript files
 */
function template_javascript($do_defered = false)
{
	global $context;

	// Use this hook to minify/optimize Javascript files
	call_integration_hook('pre_javascript_output');

	foreach ($context['javascript_files'] as $filename => $options)
		if ((!$do_defered && empty($options['defer'])) || ($do_defered && !empty($options['defer'])))
			echo '
		<script type="text/javascript" src="', $filename, '"></script>';
}

/**
 * Output the Javascript vars
 */
function template_javascript_vars()
{
	global $context;

	call_integration_hook('pre_javascript_vars_output');

	foreach ($context['javascript_vars'] as $key => $value)
		echo '
		var ', $key, ' = ', $value;
}

/**
 * Output the CSS files
 */
function template_css()
{
	global $context;

	// Use this hook to minify/optimize CSS files
	call_integration_hook('pre_css_output');

	foreach ($context['css_files'] as $filename => $options)
		echo '
	<link rel="stylesheet" type="text/css" href="', $filename, '" />';
}

?>