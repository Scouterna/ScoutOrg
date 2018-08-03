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
    /** @var string The api path for the custom lists */
    const API_CUSTOMLISTS_PATH = 'api/group/customlists';

    /** @var string The api path for the member lists */
    const API_MEMBERLIST_PATH = 'api/group/memberlist';

    /** @var string The url variables for fetching the waiting list. */
    const API_MEMBERLIST_WAITING_URLVARS = 'waiting=1';

    /** @var string The domain/address of the server with the scoutnet api. */
    private static $domain = 'www.scoutnet.se';

    /** @var int The ttl of the long lived cache in seconds. zero = disabled. */
    private static $cacheLifeTime = 0;

    const CACHE_DISABLE = 0;

    /** @var Scoutnet[] The list of multitons for each scout group. */
    private static $multitons = [];

    /** @var int The scout group id to use on the scoutnet api. */
    private $groupId;

    /** @var MemberEntry[] The short lived cache of members. */
    private $loadedMemberList = null;

    /** @var WaitingMemberEntry[] The short lived cache of waiting members. */
    private $loadedWaitingList = null;

    /** @var CustomListEntry[] The short lived cache of custom lists. */
    private $loadedCustomLists = null;

    /** @var CustomListMemberEntry[][] The short lived cache of custom lists' members. */
    private $loadedCustomMemberLists = null;

    /** @var string The api key for the member list api path. */
    private $memberListApiKey = null;

    /** @var string The api key for the custom lists api path. */
    private $customListsApiKey = null;

    /**
     * Creates a new scoutnet group link.
     * @param int $groupId The group scoutnet id.
     */
    private function __construct(int $groupId) {
        $this->groupId = $groupId;
    }

    /**
     * Gets an instance of the scoutnet controller.
     * @static
     * @param string $groupId The group's scoutnet id.
     * @return ScoutnetController
     */
    public static function getMultiton(string $groupId) {
        if (isset(ScoutnetController::$multitons[$groupId])) {
            return ScoutnetController::$multitons[$groupId];
        }
        $multiton = new ScoutnetController($groupId);
        ScoutnetController::$multitons[$groupId] = $multiton;
        return $multiton;
    }

    /**
     * Gets the group scoutnet id.
     * @return int
     */ 
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * Sets the api key for fetching the member list.
     * @param string $key The api key.
     * @return void
     */
    public function setMemberListApiKey(string $key) {
        $this->memberListApiKey = $key;
    }

    /**
     * Sets the api key for fetching the mailing lists.
     * @param string $key The api key.
     * @return void
     */
    public function setCustomListsApiKey(string $key) {
        $this->customListsApiKey = $key;
    }

    /**
     * Gets the group member list.
     * @return MemberEntry[]|false
     */
    public function getMemberList() {
        if ($this->loadedMemberList !== null) {
            return $this->loadedMemberList;
        }

        if (($memberList = $this->fetchMemberListApi('')) === false) {
            return false;
        }

        $returnList = [];
        foreach ($memberList->data as $member) {
            $returnList[] = new MemberEntry($member);
        }

        $this->loadedMemberList = $returnList;
        return $returnList;
    }

    /**
     * Gets the group waiting list.
     * @return WaitingMemberEntry[]|false
     */
    public function getWaitingList() {
        if ($this->loadedWaitingList !== null) {
            return $this->loadedWaitingList;
        }

        if (($waitingList = $this->fetchMemberListApi(self::API_MEMBERLIST_WAITING_URLVARS)) === false) {
            return false;
        }

        $returnList = [];
        foreach ($waitingList->data as $member) {
            $returnList[] = new WaitingMemberEntry($member);
        }
        
        $this->loadedWaitingList = $returnList;
        return $returnList;
    }

    /**
     * Gets all custom mailing lists from scoutnet.
     * @return CustomListEntry[]|false
     */
    public function getCustomLists() {
        if ($this->loadedCustomLists !== null) {
            return $this->loadedCustomLists;
        }

        if (($customLists = $this->fetchCustomListsApi('')) === false) {
            return false;
        }

        $returnList = [];
        foreach ($customLists as $customList) {
            $returnList[] = new CustomListEntry($customList);
        }

        $this->loadedCustomLists = $returnList;
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
        $listKey = "{$listId},{$ruleId}";
        if (isset($this->loadedCustomMemberLists[$listKey])) {
            return $this->loadedCustomMemberLists[$listKey];
        }

        $urlVars = self::getCustomListVars($listId, $ruleId);
        if (($customMemberList = $this->fetchCustomListsApi($urlVars)) === false) {
            return false;
        }

        $returnList = [];
        foreach ($customMemberList->data as $member) {
            $returnList[] = new CustomListMemberEntry($member);
        }

        $this->loadedCustomMemberLists[$listKey] = $returnList;
        return $returnList;
    }

    /**
     * Gets all members in all specified custom lists and their rules.
     * @param CustomMemberListId[] $memberListIds
     * Set rule id to CustomListRuleEntry::NO_RULE_ID if the whole list is wanted.
     * @return CustomListMemberEntry[][][]|false
     * List of entrys indexed firstly by list id and secondly by rule id.
     */
    public function getMultiCustomListMembers(array $memberListIds) {
        $returnList = [];

        $urlVarsList = [];
        foreach ($memberListIds as $memberListId) {
            $listId = $memberListId->listId;
            $ruleId = $memberListId->ruleId;

            if (!isset($returnList[$listId])) {
                $returnList[$listId] = [];
            }

            $listKey = "{$listId},{$ruleId}";
            if (isset($this->loadedCustomMemberLists[$listKey])) {
                $returnList[$listId][$ruleId] = $this->loadedCustomMemberLists[$listKey];
                continue;
            }
            
            $urlVarsList[$listKey] = self::getCustomListVars($listId, $ruleId);
        }
        if (($customMemberLists = $this->fetchMultiCustomListsApi($urlVarsList)) === false) {
            return false;
        }

        foreach ($memberListIds as $memberListId) {
            $listId = $memberListId->listId;
            $ruleId = $memberListId->ruleId;

            if (isset($returnList[$listId][$ruleId])) {
                continue;
            }

            $listKey = "{$listId},{$ruleId}";
            $customMemberList = $customMemberLists[$listKey];

            if ($customMemberList === false) {
                return false;
            }

            $returnList[$listId][$ruleId] = [];
            foreach ($customMemberList->data as $member) {
                $returnList[$listId][$ruleId][] = new CustomListMemberEntry($member);
            }

            $this->loadedCustomMemberLists[$listKey] = $returnList[$listId][$ruleId];
        }
        return $returnList;
    }

    /**
     * Fetches the resulting json object using
     * the scoutnet server's member list api.
     * @param string $urlVars The url variables to apply.
     * @return object|false
     */
    private function fetchMemberListApi(string $urlVars) {
        $url = $this->getMemberListApiUrl($urlVars);
        $memberList = ScoutnetHelper::fetchWebPage($url);

        if ($memberList === false || strlen($memberList) === 0) {
            return false;
        }

        return json_decode($memberList);
    }

    /**
     * Fetches the resulting json object using
     * the scoutnet server's custom list api.
     * @param string $urlVars The url variables to apply.
     * @return object|false
     */
    private function fetchCustomListsApi(string $urlVars) {
        $url = $this->getCustomListsApiUrl($urlVars);
        $customList =  ScoutnetHelper::fetchWebPage($url);

        if ($customList === false || strlen($customList) === 0) {
            return false;
        }

        return json_decode($customList);
    }

    /**
     * Fetches several json objects from a list of requests using
     * the scoutnet server's custom list api.
     * The objects will be indexed by the same keys as the input array.
     * @param string[] $urlVarsList The list of url variables to apply.
     * @return object[]|false
     */
    private function fetchMultiCustomListsApi(array $urlVarsList) {
        $urls = [];
        foreach ($urlVarsList as $key => $urlVars) {
            $urls[$key] = $this->getCustomListsApiUrl($urlVars);
        }
        $customLists = ScoutnetHelper::fetchWebPages($urls);

        if ($customLists === false) {
            return false;
        }

        $decodes = [];
        foreach ($customLists as $key => $customList) {
            if (strlen($customList) === 0) {
                return false;
            }
            $decode = json_decode($customList);
            if ($decode === false) {
                return false;
            }
            $decodes[$key] = $decode;
        }
        return $decodes;
    }

    /**
     * Builds a string from the list id and rule id for
     * using when fetching a custom member list with the
     * custom list api.
     * @param int $listId
     * @param int $ruleId
     * @return string
     */
    private static function getCustomListVars(int $listId, int $ruleId) {
        $retVal = "list_id=$listId";
        if ($ruleId !== CustomListRuleEntry::NO_RULE_ID) {
            $retVal .= "&rule_id=$ruleId";
        }
        return $retVal;
    }

    /**
     * Builds a url for fetching a webpage using the
     * member list api and the specified url variables.
     * @param string $urlVars
     * @return string
     */
    private function getMemberListApiUrl(string $urlVars) {
        return $this->getApiUrl($this->memberListApiKey, self::API_MEMBERLIST_PATH, $urlVars);
    }

    /**
     * Build a url for fetching a webpage using the
     * custom lists api and the specified url variables.
     * @param string $urlVars
     * @return string
     */
    private function getCustomListsApiUrl(string $urlVars) {
        return $this->getApiUrl($this->customListsApiKey, self::API_CUSTOMLISTS_PATH, $urlVars);
    }

    /**
     * Builds a url for fetching a page from the scoutnet api.
     * @param string $apiKey
     * @param string $apiPath
     * @param string $urlVars
     * @return string
     */
    private function getApiUrl(string $apiKey, string $apiPath, string $urlVars) {
        $domain = ScoutnetController::$domain;
        return "https://{$this->groupId}:{$apiKey}@{$domain}/{$apiPath}?{$urlVars}&format=json";
    }

    /**
     * Gets the domain to be used.
     * @static
     * @return string
     */ 
    public static function getDomain() {
        return ScoutnetController::$domain;
    }

    /**
     * Sets the domain to be used.
     * @static
     * @param string $domain
     * @return void
     */ 
    public static function setDomain($domain) {
        ScoutnetController::$domain = $domain;
    }

    /**
     * Gets the life time in seconds of the scoutnet cached data.
     * @static
     * @return int
     */ 
    public static function getCacheLifeTime() {
        return ScoutnetController::$cacheLifeTime;
    }

    /**
     * Sets the life time in seconds of the scoutnet cached data.
     * @static
     * @param int $cacheLifeTime Must be positive.
     * When setting to zero cache will not be used.
     * @return bool True if integer is positive and false if not.
     */ 
    public static function setCacheLifeTime(int $cacheLifeTime) {
        if ($cacheLifeTime >= 0 || $cacheLifeTime == self::CACHE_DISABLE) {
            ScoutnetController::$cacheLifeTime = $cacheLifeTime;
            return true;
        } else {
            return false;
        }
    }
}