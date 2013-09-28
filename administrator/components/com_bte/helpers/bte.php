<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Bte component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_bte
 * @since		1.6
 */
class BteHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('Links'),
			'index.php?option=com_bte&view=links',
			$vName == 'links'
		);

		JSubMenuHelper::addEntry(
			JText::_('URL Extraction'),
			'index.php?option=com_bte&view=crawler',
			$vName == 'crawler'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_bte';
		$level = 'component';

		$actions = JAccess::getActions('com_bte', $level);

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
