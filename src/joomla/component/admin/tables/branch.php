<?php

class ScoutOrgTableBranch extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__scoutorg_branches', 'id', $db);
    }

    public function check() {
		if (trim($this->name) == '') {
			$this->setError(JText::_('COM_SCOUTORG_ERROR_MISSINGNAME'));
			return false;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id')
			->from('#__scoutorg_branches')
			->where("`name` = {$db->quote($this->name)}");
		$db->setQuery($query);

		if (($result = $db->loadNextObject()) && $result->id != $this->id) {
			$this->setError(JText::_('COM_SCOUTORG_ERROR_DUPLICATENAME'));
			return false;
		}

		return true;
	}
}
