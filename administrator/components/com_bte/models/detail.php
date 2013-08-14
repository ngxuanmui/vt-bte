<?php

class BteModelDetail extends JModelLegacy
{
	public function getItem()
	{
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$jInput = JFactory::getApplication()->input;
		
		$id = $jInput->getInt('id', 0);
		
		$query->select('*')->from('#__bte_links')->where('id = ' . $id)->where('state = 1');
		
		$db->setQuery($query);
		
		$item = $db->loadObject();
		
		return $item;
	}
}