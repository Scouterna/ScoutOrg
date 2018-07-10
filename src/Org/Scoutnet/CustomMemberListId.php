<?php
/**
 * Contains CustomMemberListId class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Contains pair of list id and rule id.
 */
class CustomMemberListId {
    /** @var int List id */
    public $listId;
    
    /** @var int Rule id */
    public $ruleId;

    /**
     * Creates a new pair of custom list ids
     * @param int $listId
     * @param int $ruleId
     */
    public function __construct(int $listId, int $ruleId = CustomListRuleEntry::NO_RULE_ID) {
        $this->listId = $listId;
        $this->ruleId = $ruleId;
    }
}