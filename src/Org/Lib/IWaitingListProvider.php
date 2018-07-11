<?php
/**
 * Contains IWaitingListProvider interface
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * Contains interface for a waiting list provider.
 * For example one that loads from scoutnet or
 * one that loads from an independent database.
 * @internal
 */
interface IWaitingListProvider {
    /**
     * Get a waiting list in some concrete way.
     * @return WaitingMember[]
     */
    public function getWaitingList();
}