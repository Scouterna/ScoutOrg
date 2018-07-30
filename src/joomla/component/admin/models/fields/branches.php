<?php

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldBranches extends JFormFieldList
{
    /**
     * The field type.
     * @var         string
     */
    protected $type = 'Branches';

    /**
     * Method to get a list of options for a list input.
     * @return  array  An array of JHtml options.
     */
    protected function getOptions()
    {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id, name');
        $query->from('#__scoutorg_branches');
        
        $db->setQuery((string) $query);
        $results = $db->loadObjectList();
        $options  = array();

        if ($results) {
            foreach ($results as $result) {
                $options[] = JHtml::_('select.option', $result->id, $result->name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
