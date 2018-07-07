<?php
/**
 * Contains ScoutnetController class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

use Org\Models\WaitingMember;


/**
 * Contains methods for getting data from scoutnet.
 */
class ScoutnetController {
    /** @var string */
    private static $domain = 'www.scoutnet.se';

    /** @var int */
    private static $cacheLifeTime = 0;

    /** @var Scoutnet[] */
    private static $multitons = [];

    /** @var int */
    private $groupId;

    /** @var MemberEntry[] */
    private $loadedMemberList = null;

    /** @var WaitingMemberEntry[] */
    private $loadedWaitingList = null;

    /** @var CustomListEntry[] */
    private $loadedCustomLists = null;

    /** @var CustomListMemberEntry[][] */
    private $loadedCustomMemberLists = null;

    /** @var string */
    private $memberListApiKey = null;

    /** @var string */
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

        if (($waitingList = $this->fetchMemberListApi('waiting=1')) === false) {
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
     * Leave to default (-1) if the whole mailing list is wanted.
     * @return CustomListMemberEntry[]|false
     */
    public function getCustomListMembers(int $listId, int $ruleId = -1) {
        $listKey = "{$listId},{$ruleId}";
        if (isset($this->loadedCustomMemberLists[$listKey])) {
            return $this->loadedCustomMemberLists[$listKey];
        }

        $urlVars = "list_id={$listId}" . ($ruleId >= 0 ? "&rule_id={$ruleId}" : '');
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

    /** @return object|false */
    private function fetchMemberListApi(string $urlVars) {
        $domain = ScoutnetController::$domain;
        $url = "https://{$this->groupId}:{$this->memberListApiKey}@{$domain}/api/group/memberlist?{$urlVars}&format=json";
        $memberList = $this->fetchWebPage($url);

        if ($memberList === false || strlen($memberList) === 0) {
            return false;
        }

        return json_decode($memberList);
    }

    /** @return object|false */
    private function fetchCustomListsApi(string $urlVars) {
        $domain = ScoutnetController::$domain;
        $url = "https://{$this->groupId}:{$this->customListsApiKey}@{$domain}/api/group/customlists?{$urlVars}&format=json";
        $customList = $this->fetchWebPage($url);

        if ($customList === false || strlen($customList) === 0) {
            return false;
        }

        return json_decode($customList);
    }

    /** @return string|false */
    private function fetchWebPage(string $url) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
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
        if ($cacheLifeTime >= 0) {
            ScoutnetController::$cacheLifeTime = $cacheLifeTime;
            return true;
        } else {
            return false;
        }
    }
}