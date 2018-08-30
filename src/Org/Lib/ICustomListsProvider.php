<?php
/**
 * Contains ICustomListsProvider interface
 * @author Alexander Krantz
 */

namespace Org\Lib;

interface ICustomListsProvider {
    /**
     * Gets a list of custom lists in some concrete way.
     * @param ScoutGroup $scoutGroup The scoutgroup that contains the members.
     * @return CustomList[]
     */
    public function getCustomLists(ScoutGroup $scoutGroup);
}