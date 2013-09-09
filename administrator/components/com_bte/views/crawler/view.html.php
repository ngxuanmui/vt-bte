<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bte
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of crawlers.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bte
 * @since       1.6
 */
class BteViewCrawler extends JViewLegacy
{
	protected $form;
	protected $item;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
// 		JRequest::setVar('hidemainmenu', true);
	
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= BteHelper::getActions($this->item->catid,0);
	
		JToolBarHelper::title(JText::_('Crawl URL'), 'crawlers.png');
		
		JToolBarHelper::apply('crawler.apply');
		JToolBarHelper::save2new('crawler.save2new');
	}
}
