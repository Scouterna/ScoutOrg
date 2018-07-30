<?php

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldTroops extends JFormFieldList
{
    /**
     * The field type.
     * @var         string
     */
    protected $type = 'Troops';

    /**
     * Method to get a list of options for a list input.
     * @return  array  An array of JHtml options.
     */
    protected function getOptions()
    {
        jimport('scoutorg.loader');
        $scoutOrg = ScoutOrgLoader::load();
        $troops = $scoutOrg->getScoutGroup()->getTroops(true);

        $options  = array();

        foreach ($troops as $id => $troop) {
            $options[] = JHtml::_('select.option', $id, $troop->getName());
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
