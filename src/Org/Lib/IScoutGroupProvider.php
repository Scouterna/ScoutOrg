<?php
/**
 * Contains IScoutGroupProvider interface
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * Contains interface for a scout group provider.
 * For example one that loads from scoutnet or
 * one that loads from an independent database.
 * @internal
 */
interface IScoutGroupProvider {
    /**
     * Gets a scout group in some concrete way.
     * @return ScoutGroup
     */
    public function getScoutGroup();
}