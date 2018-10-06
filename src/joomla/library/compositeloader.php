<?php
/**
 * Contains ScoutOrgLoader class
 * @author Alexander Krantz
 */

defined('_JEXEC') or die('Restricted access');

JLoader::registerNamespace('Org\\Composite', __DIR__.'/Org');

class ScoutOrgCompositeLoader {
    /** @var \Org\Scoutnet\ScoutnetController The scoutnet controller */
    private static $scoutnetController;

    /** @var \Org\Lib\ScoutOrg Scout organisation instance */
    private static $scoutOrg;

    /**
     * Loads the scout group from scoutnet.
     * @return \Org\Lib\IScoutGroupProvider|false
     */
    public static function getScoutGroupProvider() {
        jimport('scoutorg.scoutnetloader');
        $scoutnet = ScoutOrgScoutnetLoader::getScoutnetController();
        if ($scoutnet === false) {
            return false;
        }

        $scoutGroupFactory = new \Org\Composite\ScoutGroupFactory($scoutnet, 
            $branchConfigs, $troopConfigs, $roleGroupConfigs);

        return $scoutGroupFactory;
    }

    /**
     * Loads the custom lists from scoutnet.
     * @return \Org\Lib\ICustomListsProvider|false
     */
    public static function getCustomListsProvider() {
        return false;
    }

    /**
     * Loads the waiting list from scoutnet.
     * @return \Org\Lib\IWaitingListProvider|false
     */
    public static function getWaitingListProvider() {
        return false;
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