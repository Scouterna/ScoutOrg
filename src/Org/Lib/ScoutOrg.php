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

    /** @var ScoutGroup The cached scout group. */
    private $loadedScoutGroup;

    /** @var ICustomListsProvider The object that provides a custom list list. */
    private $customListsProvider;

    /** @var CustomList[] The id indexed cached list of custom lists. */
    private $loadedCustomListsIdIndexed;

    /** @var CustomList[] The title indexed cached list of custom lists.*/
    private $loadedCustomListsTitleIndexed;

    /** @var IWaitingListProvider The object that provides a waiting member list. */
    private $waitingListProvider;

    /** @var WaitingMember[] The cached list of members. */
    private $loadedWaitingList;

    /**
     * Creates a new controller from providers.
     * @internal
     * @param IScoutGroupProvider $scoutGroupProvider
     * @param ICustomListsProvider $customListsProvider
     * @param IWaitingListProvider $waitingListProvider
     */
    public function __construct(
        IScoutGroupProvider $scoutGroupProvider,
        ICustomListsProvider $customListsProvider,
        IWaitingListProvider $waitingListProvider
    ) {
        $this->scoutGroupProvider = $scoutGroupProvider;
        $this->customListsProvider = $customListsProvider;
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
     * Gets custom lists.
     * @param bool $idIndexed Wether to index the list by id or title.
     * @return CustomList[]
     */
    public function getCustomLists(bool $idIndexed = false) {
        if ($this->loadedCustomListsIdIndexed !== null) {
            if ($idIndexed) {
                return $this->loadedCustomListsIdIndexed;
            } else {
                return $this->loadedCustomListsTitleIndexed;
            }
        }

        $scoutGroup = $this->getScoutGroup();
        $customLists = $this->customListsProvider->getCustomLists($scoutGroup);

        if ($customLists === false) {
            return false;
        }

        $idIndexedList = [];
        $titleIndexedList = [];

        foreach ($customLists as $customList) {
            $idIndexedList[$customList->getId()] = $customList;
            $titleIndexedList[$customList->getTitle()] = $customList;
        }

        $this->loadedCustomListsIdIndexed = $idIndexedList;
        $this->loadedCustomListsTitleIndexed = $titleIndexedList;

        if ($idIndexed) {
            return $idIndexedList;
        } else {
            return $titleIndexedList;
        }
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

        $idIndexedList = [];

        foreach ($waitingList as $waitingMember) {
            $idIndexedList[$waitingMember->getId()] = $waitingMember;
        }

        $this->loadedWaitingList = $idIndexedList;
        return $idIndexedList;
    }
}