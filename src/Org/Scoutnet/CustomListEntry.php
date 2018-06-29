<?php
/**
 * Contains CustomListEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

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
}