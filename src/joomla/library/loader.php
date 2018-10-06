<?php
/**
 * Contains ScoutOrgLoader class
 * @author Alexander Krantz
 */

defined('_JEXEC') or die('Restricted access');

JLoader::registerNamespace('Org', __DIR__);

class ScoutOrgLoader {
    /** @var \Org\Lib\ScoutOrg Scout organisation instance */
    private static $scoutOrg;
    
    /**
     * Loads the scout org instance according to
     * the scoutorg component params and configs.
     * @return \Org\Lib\ScoutOrg|false
     */
    public static function load() {
        if (self::$scoutOrg) {
            return self::$scoutOrg;
        }

        jimport('scoutorg.scoutnetloader');
        jimport('scoutorg.compositeloader');
        
        $params = self::getParams();

        $scoutGroupDataSource = $params->get('datasources_scoutGroupDataSource', 'scoutnet');
        $customListsDataSource = $params->get('datasources_customListsDataSource', 'scoutnet');
        $waitingListDataSource = $params->get('datasources_waitingListDataSource', 'scoutnet');

        $domain = $params->get('scoutnet_domain', 'www.scoutnet.se');
        $groupId = $params->get('scoutnet_groupId', -1);
        $memberListKey = $params->get('scoutnet_memberListApiKey', '');
        $customListsKey = $params->get('scoutnet_customListsApiKey', '');
        $scoutnetCacheLifeTime = $params->get('scoutnet_cacheLifeTime');

        if ($groupId == -1 ||
            $memberListKey == '' ||
            $customListsKey == ''
        ) {
            return false;
        }

        $groupConfig = new \Org\Scoutnet\ScoutGroupConfig($groupId,
            $memberListKey, $customListsKey);
        $scoutnetConnection = new \Org\Scoutnet\ScoutnetConnection($groupConfig, $domain, $scoutnetCacheLifeTime);
        $scoutnetController = new \Org\Scoutnet\ScoutnetController($scoutnetConnection);

        if ($scoutGroupDataSource == 'scoutnet') {
            $scoutGroupProvider = ScoutOrgScoutnetLoader::getScoutGroupProvider();
        } else if ($scoutGroupDataSource == 'composite') {
            $scoutGroupProvider = ScoutOrgCompositeLoader::getScoutGroupProvider();
            return false; // TODO: Implement.
        }

        if ($customListsDataSource == 'scoutnet') {
            $customListsProvider = ScoutOrgScoutnetLoader::getCustomListsProvider();
        }

        if ($waitingListDataSource == 'scoutnet') {
            $waitingListProvider = ScoutOrgScoutnetLoader::getWaitingListProvider();
        }

        self::$scoutOrg = new \Org\Lib\ScoutOrg($scoutGroupProvider, $customListsProvider, $waitingListProvider);

        return self::$scoutOrg;
    }

    /**
     * Gets the component params.
     * @return JRegistry
     */
    private static function getParams() {
        return JComponentHelper::getParams('com_scoutorg');
    }
}