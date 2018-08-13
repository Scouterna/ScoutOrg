<?php
/**
 * Contains ScoutGroupConfig class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Class that contains settings for a scout group.
 */
class ScoutGroupConfig {
    /** @var int Group scoutnet id. */
    private $groupId;

    /** @var string Member list api key. */
    private $memberListKey;

    /** @var string Custom lists api key. */
    private $customListsKey;

    /**
     * Creates a new configuration.
     * @param int $groupId
     * @param string $memberListApiKey
     * @param string $customListsApiKey
     */
    public function __construct(int $groupId,
                                string $memberListApiKey,
                                string $customListsApiKey) {
        $this->groupId = $groupId;
        $this->memberListKey = $memberListApiKey;
        $this->customListsKey = $customListsApiKey;
    }

    /**
     * Gets the group scoutnet id.
     */ 
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * Gets the member list api key.
     */ 
    public function getMemberListKey() {
        return $this->memberListKey;
    }

    /**
     * Gets the custom lists api key.
     */ 
    public function getCustomListsKey() {
        return $this->customListsKey;
    }
}