<?php
/**
 * Contains CustomListEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * Contains fields that are equivalent to custom lists from scoutnet.
 */
class CustomListEntry {
    /** @var int Custom list id. */
    public $id;

    /** @var string Custom list title. */
    public $title;

    /** @var string Custom list description. */
    public $description;

    /** @var string The email key to be used with @scouterna.se. */
    public $list_email_key;

    /** @var string[] A list of aliases. */
    public $aliases;

    /** @var CustomListRuleEntry[] The list of rules that the members are filtered through. */
    public $rules;

    /**
     * Creates a new custom list entry from a scoutnet entry.
     * @param object $entry
     */
    public function __construct($entry) {
        $this->id = $entry->id;
        $this->title = $entry->title;
        $this->description = $entry->description;
        $this->list_email_key = $entry->list_email_key;
        $this->aliases = $entry->aliases;
        $this->rules = [];
        foreach ($entry->rules as $rule) {
            $this->rules[] = new CustomListRuleEntry($rule);
        }
    }

    /**
     * Creates a Lib\CustomList instance from this object.
     * @return Lib\CustomList
     */
    public function getCustomList() {
        return new Lib\CustomList($this->id, $this->title, $this->description);
    }
}