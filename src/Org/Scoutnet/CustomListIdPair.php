<?php
/**
 * Contains CustomListIdPair class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Contains pair of list id and rule id.
 */
class CustomListIdPair {
    /** @var int List id */
    public $listId;
    
    /** @var int Rule id */
    public $ruleId;

    /**
     * Creates a new pair of custom list ids
     * @param int $listId
     * @param int $ruleId
     */
    public function __construct(int $listId, int $ruleId) {
        $this->listId = $listId;
        $this->ruleId = $ruleId;
    }
}