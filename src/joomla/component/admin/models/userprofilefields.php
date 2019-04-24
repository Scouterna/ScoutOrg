<?php

class ScoutOrgModelUserprofilefields extends JModelList {
    /**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
    protected function getListQuery() {
        // Initialize variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('upf.*')
			->from('#__scoutorg_userprofilefields AS upf');

		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = upf.access');

		$query->order('ordering ASC');

        return $query;
	}
	
	/**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = false)
    {
        // Get the form.
        $form = $this->loadForm(
            'com_scoutorg.userprofilefield',
            'userprofilefield',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Saves the manually set order of records.
     *
     * @param   array    $pks    An array of primary key ids.
     * @param   array  $order  Order position
     *
     * @return  boolean|JException  Boolean true on success, boolean false or JException instance on error
     */
    public function saveorder($pks, $order) {
        $table = JTable::getInstance('Userprofilefield', 'ScoutOrgTable');

        foreach ($pks as $i => $pk) {
			$table->load((int) $pk);
			
            if ($table->ordering != $order[$i]) {
				$table->ordering = $order[$i];
				
                if (!$table->store()) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }
		
        return true;
    }
}
