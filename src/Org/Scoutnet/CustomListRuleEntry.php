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
}