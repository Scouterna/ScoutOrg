<?php
/**
 * Contains ScoutOrgLoader class
 * @author Alexander Krantz
 */

defined('_JEXEC') or die('Restricted access');

JLoader::registerNamespace('Org\\Scoutnet', __DIR__.'/Org');

class ScoutOrgScoutnetLoader {
    /** @var \Org\Scoutnet\ScoutnetController The scoutnet controller */
    private static $scoutnetController;

    /** @var \Org\Lib\ScoutOrg Scout organisation instance */
    private static $scoutOrg;

    /**
     * Loads the scout group from scoutnet.
     * @return \Org\Lib\IScoutGroupProvider|false
     */
    public static function getScoutGroupProvider() {
        $scoutnet = self::getScoutnetController();
        if ($scoutnet === false) {
            return false;
        }
        $branchConfigs = self::getBranchConfigs();
        $scoutGroupFactory = new \Org\Scoutnet\ScoutGroupFactory($scoutnet, $branchConfigs);
        return $scoutGroupFactory;
    }

    /**
     * Loads the custom lists from scoutnet.
     * @return \Org\Lib\ICustomListsProvider|false
     */
    public static function getCustomListsProvider() {
        $scoutnet = self::getScoutnetController();
        if ($scoutnet === false) {
            return false;
        }
        $customListsFactory = new \Org\Scoutnet\CustomListsFactory($scoutnet);
        return $customListsFactory;
    }

    /**
     * Loads the waiting list from scoutnet.
     * @return \Org\Lib\IWaitingListProvider|false
     */
    public static function getWaitingListProvider() {
        $scoutnet = self::getScoutnetController();
        if ($scoutnet === false) {
            return false;
        }
        $waitingListFactory = new \Org\Scoutnet\WaitingListFactory($scoutnet);
        return $waitingListFactory;
    }

    /**
     * Gets the scoutnet controller to be used.
     * @return \Org\Scoutnet\ScoutnetController|false
     */
    public static function getScoutnetController() {
        if (self::$scoutnetController) {
            return self::$scoutnetController;
        }

        $params = self::getParams();
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

        self::$scoutnetController = $scoutnetController;
        return $scoutnetController;
    }

    /**
     * Gets the component params.
     * @return JRegistry
     */
    private static function getParams() {
        return JComponentHelper::getParams('com_scoutorg');
    }

    /**
     * Gets the branch configs from the database.
     * @return \Org\Scoutnet\BranchConfig[]
     */
    private static function getBranchConfigs() {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name')->from($db->quoteName('#__scoutorg_scoutnet_branches'));
        $db->setQuery($query);

        $configs = array();
        foreach ($db->loadObjectList() as $branchRow) {
            $query = $db->getQuery(true);
            $query->select('troop')
                ->from($db->quoteName('#__scoutorg_scoutnet_troops'))
                ->where("branch = {$branchRow->id}");
            $db->setQuery($query);

            $troops = array();
            foreach ($db->loadObjectList() as $troopRow) {
                $troops[] = $troopRow->troop;
            }
            $configs[] = new Org\Scoutnet\BranchConfig($branchRow->id, $branchRow->name, $troops);
        }

        return $configs;
    }
}