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

    /** @var CustomListEntry[] */
    private $loadedCustomLists = null;

    /** @var CustomListMemberEntry[][] */
    private $loadedCustomMemberLists = null;

    /** @var string */
    private $memberListApiKey = null;

    /** @var string */
    private $mailingListsApiKey = null;

    /**
     * Creates a new scoutnet link with the specified domain.
     * @param string $domain The domain of the scoutnet server to fetch data from.
     */
    private function __construct(string $groupId) {
        $this->groupId = $groupId;
    }

    /**
     * Gets an instance of the scoutnet controller.
     * @param string $groupId The group's scoutnet id.
     * @return Scoutnet
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
    public function setMailingListsApiKey(string $key) {
        $this->mailingListsApiKey = $key;
    }

    /**
     * Gets the member list of a scout group.
     * @param int $groupId
     * @param string $key
     * @return MemberEntry[]
     */
    public function getMemberList() {
        if ($this->loadedMemberList !== null) {
            return $this->loadedMemberList;
        }

        $memberList = $this->fetchMemberList($this->groupId, $this->memberListApiKey);
        $returnList = [];
        foreach ($memberList->data as $member) {
            $newMemberEntry = new MemberEntry();
            foreach ($member as $dataFieldName => $dataField) {
                $classFieldName = str_replace('-', '', $dataFieldName);
                if (isset($dataField->raw_value)) {
                    $newValue = new ValueAndRaw();
                    $newValue->rawValue = $dataField->raw_value;
                    $newValue->value = $dataField->value;
                    $newMemberEntry->{$classFieldName} = $newValue;
                } else {
                    $newValue = new Value();
                    $newValue->value = $dataField->value;
                    $newMemberEntry->{$classFieldName} = $newValue;
                }
            }
            $returnList[] = $newMemberEntry;
        }

        $this->loadedMemberList = $returnList;
        return $returnList;
    }

    /**
     * Fetches member list from scoutnet.
     * @return array A json parsed member list.
     */
    private function fetchMemberList() {
        $domain = ScoutnetController::$domain;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://{$this->groupId}:{$this->memberListApiKey}@{$domain}/api/group/memberlist?format=json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        $memberList = curl_exec($curl);
        curl_close($curl);

        return json_decode($memberList);
    }

    /**
     * Gets all custom mailing lists from scoutnet.
     * @return CustomListEntry[]
     */
    public function getMailingLists() {
        if ($this->loadedCustomLists !== null) {
            return $this->loadedCustomLists;
        }

        $customLists = $this->fetchMailingLists();
        $returnList = [];
        foreach ($customLists as $customList) {
            $newCustomList = new CustomListEntry();
            $newCustomList->id = $customList->id;
            $newCustomList->title = $customList->title;
            $newCustomList->description = $customList->description;
            $newCustomList->aliases = $customList->aliases;
            $newCustomList->list_email_key = $customList->list_email_key;
            $newCustomList->rules = [];
            foreach ($customList->rules as $customListRule) {
                $newCustomListRule = new CustomListRuleEntry();
                $newCustomListRule->id = $customListRule->id;
                $newCustomListRule->title = $customListRule->title;
                $newCustomListRule->link = $customListRule->link;
                $newCustomList->rules[] = $newCustomListRule;
            }
            $returnList[] = $newCustomList;
        }

        $this->loadedCustomLists = $returnList;
        return $returnList;
    }

    private function fetchMailingLists() {
        $domain = ScoutnetController::$domain;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://{$this->groupId}:{$this->mailingListsApiKey}@{$domain}/api/group/customlists?format=json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        $mailingLists = curl_exec($curl);
        curl_close($curl);

        return json_decode($mailingLists);
    }

    /**
     * Gets all members in a custom mailinng list or one of its rules.
     * @param int $listId The custom mailing list id.
     * @param int $ruleId The rule id.
     * Leave to default (-1) if the whole mailing list is wanted.
     * @return CustomListMemberEntry[]
     */
    public function getMailingMemberList(int $listId, int $ruleId = -1) {
        $listKey = "{$listId},{$ruleId}";
        if (isset($this->loadedCustomMemberLists[$listKey])) {
            return $this->loadedCustomMemberLists[$listKey];
        }

        $customMemberList = $this->fetchMailingMemberList($listId, $ruleId);
        $returnList = [];
        foreach ($customMemberList->data as $member) {
            $newMemberEntry = new CustomListMemberEntry();
            foreach ($member as $dataFieldName => $dataField) {
                $newValue = new Value();
                $newValue->value = $dataField->value;
                $newMemberEntry->{$dataFieldName} = $newValue;
            }
            $returnList[] = $newMemberEntry;
        }

        $this->loadedCustomMemberLists[$listKey] = $returnList;
        return $returnList;
    }

    private function fetchMailingMemberList(int $listId, int $ruleId = -1) {
        $domain = ScoutnetController::$domain;
        $urlVars = "list_id={$listId}" . ($ruleId >= 0 ? "&rule_id={$ruleId}" : '');
        echo "$urlVars\n";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://{$this->groupId}:{$this->mailingListsApiKey}@{$domain}/api/group/customlists?{$urlVars}&format=json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        $memberList = curl_exec($curl);
        curl_close($curl);

        return json_decode($memberList);
    }

    

    /**
     * Gets the domain to be used.
     * @static
     * @return string
     */ 
    public static function getDomain()
    {
        return ScoutnetController::$domain;
    }

    /**
     * Sets the domain to be used.
     * @static
     * @param string $domain
     * @return void
     */ 
    public static function setDomain($domain)
    {
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