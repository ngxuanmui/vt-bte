<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_bte
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_bte')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// include helper
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'bte_helper.php';

// Execute the task.
$controller	= JControllerLegacy::getInstance('Bte');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
