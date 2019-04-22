<?php
/**
 * Contains ICustomListsProvider interface
 * @author Alexander Krantz
 */

namespace Org\Lib;

/**
 * Interface for a custom list provider
 * For example one that loads from scoutnet or
 * one that loads from an independent database.
 * @internal
 */
interface ICustomListsProvider {
    /**
     * Gets a list of custom lists in some concrete way.
     * @param ScoutGroup $scoutGroup The scoutgroup that contains the members.
     * @return CustomList[]
     */
    public function getCustomLists(ScoutGroup $scoutGroup);
}