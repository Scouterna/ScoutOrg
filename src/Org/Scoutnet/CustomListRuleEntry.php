<?php
/**
 * Contains CustomListRuleEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * Contains fields that are equivalent to custom list rules from scoutnet.
 */
class CustomListRuleEntry {
    const NO_RULE_ID = -1;

    /** @var int The rule id. */
    public $id;

    /** @var string The rule title. */
    public $title;

    /** @var string The rule api link. */
    public $link;

    /**
     * Creates a new custom list rule entry from a scoutnet entry.
     * @param object $entry
     */
    public function __construct($entry) {
        $this->id = $entry->id;
        $this->title = $entry->title;
        $this->link = $entry->link;
    }

    /**
     * Creates a Lib\CustomList instance from this object.
     * @return Lib\CustomList
     */
    public function getCustomList() {
        return new Lib\CustomList($this->id, $this->title, '');
    }
}