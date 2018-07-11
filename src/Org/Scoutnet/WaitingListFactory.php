<?php
/**
 * Contains ScoutnetWaitingListFactory class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * A factory for generating a list of members waiting for placement.
 */
class WaitingListFactory implements Lib\IWaitingListProvider {
    /** @var ScoutnetController The source of data of which to create the waiting list. */
    private $scoutnet;

    /**
     * Creates a new waiting list factory.
     * @param ScoutnetController $scoutnet
     */
    public function __construct(ScoutnetController $scoutnet) {
        $this->scoutnet = $scoutnet;
    }

    /**
     * Gets the waiting list of the scout group.
     * @return WaitingMember[]|false
     */
    public function getWaitingList() {
        if (($waitingListEntrys = $this->scoutnet->getWaitingList()) === false) {
            return false;
        }
        $returnList = [];
        foreach ($waitingListEntrys as $entry) {
            $returnList[] = $entry->getMember();
        }
        return $returnList;
    }
}