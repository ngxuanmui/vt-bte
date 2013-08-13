<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Bte master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_bte
 * @since		1.6
 */
class BteController extends JControllerLegacy
{
	protected $default_view = 'links';
	
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/bte.php';

		// Load the submenu.
		BteHelper::addSubmenu(JRequest::getCmd('view', 'links'));

		$view	= JRequest::getCmd('view', 'links');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');

		parent::display();

		return $this;
	}
}
