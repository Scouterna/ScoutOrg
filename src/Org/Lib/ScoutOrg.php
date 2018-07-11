<?php
/**
 * Contains ScoutOrg class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * Is used for getting structured information about scout groups.
 */
class ScoutOrg {
    /** @var IScoutGroupProvider The object that provides a scout group object. */
    private $scoutGroupProvider;

    /** @var ScoutGroup The cached scout group that will be returned each time one is requested. */
    private $loadedScoutGroup;

    /** @var IWaitingListProvider The object that provides a waiting member list. */
    private $waitingListProvider;

    /** @var WaitingMember[] The cached list of members that will be returned each time the list is requested. */
    private $loadedWaitingList;

    /**
     * Creates a new controller from providers.
     * @internal
     * @param IScoutGroupProvider $scoutGroupProvider
     * @param IWaitingListProvider $waitingListProvider
     */
    public function __construct(IScoutGroupProvider $scoutGroupProvider,
                                IWaitingListProvider $waitingListProvider) {
        $this->scoutGroupProvider = $scoutGroupProvider;
        $this->waitingListProvider = $waitingListProvider;
    }

    /**
     * Gets the scout group structure.
     * @return ScoutGroup|false
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
     * @return WaitingMember[]|false
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