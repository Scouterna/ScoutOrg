<?php
/**
 * Contains ScoutGroupController class
 * @author Alexander Krantz
 */
namespace Org\Controllers;
use \Org\Models;

/**
 * Is used for getting structured information about scout groups.
 */
class ScoutGroupController {
    /** @var \Org\Models\IScoutGroupProvider */
    private $scoutGroupProvider;

    /** @var \Org\Models\ScoutGroup */
    private $loadedScoutGroup;

    /** @var \Org\Models\IWaitingListProvider */
    private $waitingListProvider;

    /** @var \Org\Models\WaitingMember[] */
    private $loadedWaitingList;

    /**
     * Creates a new controller from a scout group provider.
     * @param \Org\Models\IScoutGroupProvider $scoutGroupProvider
     * @param \Org\Models\IWaitingListProvider $waitingListProvider
     */
    public function __construct(Models\IScoutGroupProvider $scoutGroupProvider,
                                Models\IWaitingListProvider $waitingListProvider) {
        $this->scoutGroupProvider = $scoutGroupProvider;
        $this->waitingListProvider = $waitingListProvider;
    }

    /**
     * Loads the group structure from a provider.
     * @return \Org\Models\ScoutGroup|false
     */
    public function getScoutGroup() {
        if ($this->loadedScoutGroup !== null) {
            return $this->loadedScoutGroup;
        }

        $scoutGroup = $this->scoutGroupProvider->getScoutGroup();

        if ($scoutGroup === false) {
            return false;
        }
        
        $this->loadedScoutGroup = $scoutGroup;
        return $scoutGroup;
    }

    /**
     * Gets the list of members waiting for placement.
     * @return \Org\Models\WaitingMember[]|false
     */
    public function getWaitingList() {
        if ($this->loadedWaitingList !== null) {
            return $this->loadedWaitingList;
        }

        $waitingList = $this->waitingListProvider->getWaitingList();

        if ($waitingList === false) {
            return false;
        }

        $this->loadedWaitingList = $waitingList;
        return $waitingList;
    }
}