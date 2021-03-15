<?php


defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_mpoll component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function MWebinarBuildRoute(&$query)
{
	$items = Array();
	$default = 0;
	$founditem = 0;
	$segments = array();
	$app = JFactory::getApplication();
	$menu	= $app->getMenu();
	$items	= $menu->getItems('component', 'com_mwebinar');

	if (isset($query['webinar'])) $webinar = $query['webinar']; else $webinar =0;

	foreach ($items as $mi) {
		if (!$founditem) {
			if (isset($mi->query['webinar'])) {
				if ( ! empty( $mi->query['webinar'] ) && ( (int) $mi->query['webinar'] == (int) $webinar ) ) {
					$founditem = $mi->id;
				}
			}
		}
	}



	if (!$founditem && isset($query['Itemid'])) {
		$default = $query['Itemid'];
	}


	if ($founditem) {
		$query['Itemid'] = $founditem;
		unset ($query['view']);
		unset ($query['webinar']);
	} else {
		$query['Itemid'] = $default;
	}

	return $segments;
}
/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 */
function MWebinarParseRoute($segments)
{
	$vars = array();
	return $vars;
}
