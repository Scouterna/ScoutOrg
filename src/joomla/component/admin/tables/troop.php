<?php

class ScoutOrgTableTroop extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__scoutorg_troops', 'id', $db);
    }

    public function check()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id, troop')
            ->from($db->quoteName('#__scoutorg_troops'))
            ->where("troop = {$this->troop}");
        $db->setQuery($query);

        if (($result = $db->loadNextObject()) && $result->id != $this->id) {
            $this->setError(JText::_('COM_SCOUTORG_ERROR_DUPLICATEID'));
            return false;
        }

        return true;
    }
}
