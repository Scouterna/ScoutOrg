<?php
defined('_JEXEC') or die;

/**
 * ScoutOrg plugin class.
 * @package     Joomla.plugin
 * @subpackage  System.ScoutOrg
 */
class plgSystemScoutOrg extends JPlugin
{
    /**
     * Register ScoutOrg library and initializes controller.
     * @return void
     */
    public function onAfterInitialise()
    {
        global $scoutOrg;

        JLoader::registerNamespace('Org', __DIR__ );
        $scoutOrg = new \Org\Controllers\ScoutGroupController($this->params->get('domain'), $this->params->get('groupId'));
        $scoutOrg->setMemberListApiKey($this->params->get('memberListApiKey'));
    }
}