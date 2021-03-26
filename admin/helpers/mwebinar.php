<?php
// No direct access to this file
defined('_JEXEC') or die;

abstract class MWebinarHelper
{
	public static function addSubmenu($submenu,$extension="")
	{
		JHtmlSidebar::addEntry("Webinars", 'index.php?option=com_mwebinar&view=webinars', $submenu == 'webinars');
		JHtmlSidebar::addEntry("Pages", 'index.php?option=com_mwebinar&view=pages', $submenu == 'pages');
		JHtmlSidebar::addEntry("Results - Tally", 'index.php?option=com_mwebinar&view=results', $submenu == 'results');
		JHtmlSidebar::addEntry("Results - By Session", 'index.php?option=com_mwebinar&view=sessions', $submenu == 'sessions');
		JHtmlSidebar::addEntry("Categories", 'index.php?option=com_categories&extension=com_mwebinar', $submenu == 'categories');
	}


}
