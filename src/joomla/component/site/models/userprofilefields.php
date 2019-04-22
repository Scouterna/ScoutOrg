<?php

class ScoutorgModelUserprofilefields extends JModelList {
    protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')->from($db->quoteName('#__scoutorg_userprofilefields'));
		$query->order('ordering ASC');

		return $query;
	}
}