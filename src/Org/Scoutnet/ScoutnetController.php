<?php
/**
 * Contains ScoutnetController class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Contains methods for getting data from scoutnet.
 */
class ScoutnetController {
    /** @var string The url variables for fetching the waiting list. */
    const API_MEMBERLIST_WAITING_URLVARS = 'waiting=1';

    /** @var ScoutnetConnection The scoutnet connection. */
    private $connection;

    /**
     * Creates a new scoutnet group link.
     * @param ScoutnetConnection $connection
     */
    public function __construct(ScoutnetConnection $connection) {
        $this->connection = $connection;
    }

    /**
     * Gets the group scoutnet id.
     * @return int
     */
    public function getGroupId() {
        return $this->connection->getGroupConfig()->getGroupId();
    }

    /**
     * Gets the group member list.
     * @return MemberEntry[]|false
     */
    public function getMemberList() {
        if (($memberList = $this->connection->fetchMemberListApi('')) === false) {
            return false;
        }

        $returnList = [];
        foreach ($memberList->data as $member) {
            $returnList[] = new MemberEntry($member);
        }

        return $returnList;
    }

    /**
     * Gets the group waiting list.
     * @return WaitingMemberEntry[]|false
     */
    public function getWaitingList() {
        if (($waitingList = $this->connection->fetchMemberListApi(self::API_MEMBERLIST_WAITING_URLVARS)) === false) {
            return false;
        }

        $returnList = [];
        foreach ($waitingList->data as $member) {
            $returnList[] = new WaitingMemberEntry($member);
        }
        
        return $returnList;
    }

    /**
     * Gets all custom mailing lists from scoutnet.
     * @return CustomListEntry[]|false
     */
    public function getCustomLists() {
        if (($customLists = $this->connection->fetchCustomListsApi('')) === false) {
            return false;
        }

        $returnList = [];
        foreach ($customLists as $customList) {
            $returnList[] = new CustomListEntry($customList);
        }

        return $returnList;
    }

    /**
     * Gets all members in a custom mailinng list or one of its rules.
     * @param int $listId The custom mailing list id.
     * @param int $ruleId The rule id.
     * Leave to default (CustomListRuleEntry::NO_RULE_ID) if the whole mailing list is wanted.
     * @return CustomListMemberEntry[]|false
     */
    public function getCustomListMembers(int $listId, int $ruleId = CustomListRuleEntry::NO_RULE_ID) {
        $urlVars = $this->getCustomListVars($listId, $ruleId);
        if (($customMemberList = $this->connection->fetchCustomListsApi($urlVars)) === false) {
            return false;
        }

        $returnList = [];
        foreach ($customMemberList->data as $member) {
            $returnList[] = new CustomListMemberEntry($member);
        }

        return $returnList;
    }

    /**
     * Builds a string from the list id and rule id for
     * using when fetching a custom member list with the
     * custom list api.
     * @param int $listId
     * @param int $ruleId
     * @return string
     */
    private function getCustomListVars(int $listId, int $ruleId) {
        $retVal = "list_id=$listId";
        if ($ruleId !== CustomListRuleEntry::NO_RULE_ID) {
            $retVal = $retVal . "&rule_id=$ruleId";
        }
        return $retVal;
    }
}