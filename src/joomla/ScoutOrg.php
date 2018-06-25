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

        $domain = $this->params->get('domain');
        $groupId = $this->params->get('groupId');
        $memberListKey = $this->params->get('memberListApiKey');

        if (gettype($domain) !== 'string') {
            JLog::add('domain must be a string', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if (gettype($groupId) !== 'integer') {
            JLog::add('group id must be an integer', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if (gettype($memberListKey) !== 'string') {
            JLog::add('member list api key must be a string', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        $scoutOrg = new \Org\Controllers\ScoutGroupController($domain, $groupId);
        $scoutOrg->setMemberListApiKey($memberListKey);
    }
}