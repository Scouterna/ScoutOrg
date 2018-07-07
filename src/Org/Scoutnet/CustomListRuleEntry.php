<?php
/**
 * Contains CustomListRuleEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Contains fields that are equivalent to custom list rules from scoutnet.
 */
class CustomListRuleEntry {
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
}