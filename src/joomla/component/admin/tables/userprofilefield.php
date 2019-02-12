<?php

class ScoutorgTableUserprofilefield extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__scoutorg_userprofilefields', 'id', $db);
    }
}
