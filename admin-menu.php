<?php

/*
	Plugin Name: Enhanced Admin Links in Multisite 'My Sites' Drop-Downs
	Plugin URI: http://aarontgrogg.com/2013/03/26/wordpress-plugin-enhanced-admin-links-in-multisite-my-sites-drop-downs/
	Description: For multisite installations, adds 'Posts', 'Pages', 'Themes', 'Plugins', 'Settings', and 'Tools' links to all of the Admin 'My Sites' drop-down menus.
	Version: 1.2
	Author: Aaron T. Grogg
	Author URI: http://aarontgrogg.com/
	License: GPLv2 or later
*/

/*	Function to add links to My Sites drop-downs */
	if ( !function_exists( 'add_links_to_my_sites' ) ) {

		function add_links_to_my_sites() {

			// make sure user is allowed to do this
			if (current_user_can('manage_network')) {

				// grab a couple variables...
				global $wp_admin_bar;
				$all_nodes = $wp_admin_bar->get_nodes();
				$links_to_add = array( 'Posts', 'Pages', 'Themes', 'Plugins', 'Settings', 'Tools' );
				$i = -1;

				// loop through all nodes
				foreach ($all_nodes as $node) {

					++$i;
					// if we encounter a Dashboard, we want to add the new links
					if ($node->title === 'Dashboard') {
						// grab a couple variables...
						$parent = $node->parent;
						$href = $node->href;

						// loop through and add the new menu links
						foreach ($links_to_add as $link) {
							// Network doesn't have a POsts, Pages, or Tools page, so skip them
							if (strpos($href, 'wp-admin/network') && ($link === 'Posts' || $link === 'Pages' || $link === 'Tools')) {
								continue;
							}
							// for everything else, push the new link to the menu array
							$wp_admin_bar->add_menu( array(
								'parent' => $parent,
								'id' => $parent . '-' . strtolower($link),
								'title' => $link,
								'href' => $href . strtolower($link) . '.php',
							));

						} // foreach

					} // if ($node->title === 'Dashboard')

				} // foreach

			} // if (current_user_can('manage_network'))

		} // function add_links_to_my_sites

	} // if ( !function_exists( 'add_links_to_my_sites' ) )

	// if we're in admin mode, add action to admin_bar_menu hook
	if (is_admin()) {
		add_action('admin_bar_menu', 'add_links_to_my_sites', 200);
	} // is_admin

?>
