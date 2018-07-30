<?php

class ScoutOrgModelBranches extends JModelList {
    /**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')->from($db->quoteName('#__scoutorg_branches'));

		return $query;
	}
}
