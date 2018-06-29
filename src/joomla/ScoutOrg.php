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
        $groupId = intval($this->params->get('groupId'));
        $memberListKey = $this->params->get('memberListApiKey');
        $mailingListsKey = $this->params->get('mailingListsApiKey');
        $scoutnetCacheLifeTime = intval($this->params->get('scoutnetCacheLifeTime'));

        $error = false;
        if ($error = gettype($domain) !== 'string') {
            JLog::add('Domain must be a string', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error = gettype($groupId) !== 'integer') {
            JLog::add('Group id must be an integer', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error = gettype($memberListKey) !== 'string') {
            JLog::add('Member list api key must be a string', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error = gettype($mailingListsKey) !== 'string') {
            JLog::add('Mailing lists api key must be a string', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error = gettype($scoutnetCacheLifeTime) !== 'integer') {
            JLog::add('scoutnet cache life time must be an integer', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error = gettype($scoutnetCacheLifeTime) < 0) {
            JLog::add('scoutnet cache life time must be a positive number', JLog::ERROR, 'ScoutOrg-PluginInit');
        }

        if ($error) {
            JLog::add('Scout group can not be created', JLog::ERROR, 'ScoutOrg-PluginInit');
            return;
        }

        \Org\Scoutnet\ScoutnetController::setDomain($domain);
        \Org\Scoutnet\ScoutnetController::setCacheLifeTime($scoutnetCacheLifeTime);

        $factory = new \Org\Models\ScoutnetFactory($groupId);
        $factory->setMemberListApiKey($memberListKey);
        $factory->setMailingListsApiKey($mailingListsKey);

        $scoutOrg = new \Org\Controllers\ScoutGroupController($factory);
    }
}