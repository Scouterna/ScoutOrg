<?php
namespace Org\Controllers;

/**
 * Is used for getting structured information about scout groups.
 */
class ScoutGroupController {
    /** @var \Org\Models\ScoutGroup[] */
    private static $loadedScoutGroups = [];
    
    /** @var string */
    private $domain;

    /** @var \Org\Scoutnet\Scoutnet */
    private $scoutnetController;

    /** @var int */
    private $groupId;

    /** @var string */
    private $memberListApiKey;

    /**
     * Creates a new controller from a database domain and scout group scoutnet id.
     * @param string $domain The domain that the data will be fetched from.
     * @param int $groupId The scoutnet group id.
     */
    public function __construct(string $domain, int $groupId) {
        $this->domain = $domain;
        $this->scoutnetController = \Org\Scoutnet\Scoutnet::getMultiton($domain);
        $this->groupId = $groupId;
    }

    /**
     * Sets the api key for fetching the member list.
     * Must be called before loading the scout group.
     * @param string $key The api key
     * @return void
     */
    public function setMemberListApiKey(string $key) {
        $this->memberListApiKey = $key;
    }

    /**
     * Loads the group structure from scoutnet.
     * Must have called <code>setMemberListApiKey</code> or it will fail.
     * @return \Org\Models\ScoutGroup A scout group.
     */
    public function loadScoutGroup() {
        if (isset(ScoutGroupController::$loadedScoutGroups[$groupId])) {
            return ScoutGroupController::$loadedScoutGroups[$groupId];
        }

        $factory = new \Org\Models\ScoutGroupFactory($this->groupId, $this->memberListApiKey, $this->scoutnetController);
        $scoutGroup = $factory->createObject();
        
        ScoutGroupController::$loadedScoutGroups[$this->groupId] = $scoutGroup;
        return $scoutGroup;
    }
}