<?php
/**
 * Contains ScoutnetConnection class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * A scoutnet connection for fetching data from scoutnet.
 * Also handles cache.
 */
class ScoutnetConnection {
    /** @var string The api path for the custom lists */
    const API_CUSTOMLISTS_PATH = 'api/group/customlists';

    /** @var string The api path for the member lists */
    const API_MEMBERLIST_PATH = 'api/group/memberlist';
    
    /** @var int The value of the scoutnet cache when it is disabled. */
    const CACHE_DISABLE = 0;

    /** @var mixed Semaphore for concurrent webpage fetching. */
    private static $sem = null;

    /** @var string The domain/address of the server with the scoutnet api. */
    private $domain = "www.scoutnet.se";

    /** @var int The ttl of the long lived cache in seconds. zero = disabled. */
    private $cacheLifeTime;

    /** @var ScoutGroupConfig The scoutnet group configuration. */
    private $groupConfig;

    /**
     * Creates a new connection.
     * @param ScoutGroupConfig $groupConfig
     * @param string $domain
     * @param int $cacheLifeTime
     */
    public function __construct(ScoutGroupConfig $groupConfig, string $domain, int $cacheLifeTime = self::CACHE_DISABLE) {
        $this->groupConfig = $groupConfig;
        $this->domain = $domain;
        if ($cacheLifeTime < 0) {
            $this->cacheLifeTime = self::CACHE_DISABLE;
        } else {
            $this->cacheLifeTime = $cacheLifeTime;
        }
    }

    /**
     * Gets the domain to be used.
     * @return string
     */ 
    public function getDomain() {
        return $this->domain;
    }

    /**
     * Sets the domain to be used.
     * @param string $domain
     * @return void
     */ 
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * Gets the life time in seconds of the scoutnet cached data.
     * @return int
     */ 
    public function getCacheLifeTime() {
        return $this->cacheLifeTime;
    }

    /**
     * Sets the life time in seconds of the scoutnet cached data.
     * @param int $cacheLifeTime Must be positive.
     * When setting to zero cache will not be used.
     * @return bool True if value is valid, false if not.
     */ 
    public function setCacheLifeTime(int $cacheLifeTime) {
        if ($cacheLifeTime >= 0 || $cacheLifeTime == self::CACHE_DISABLE) {
            $this->cacheLifeTime = $cacheLifeTime;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets the scout group configuration.
     * @return ScoutGroupConfig
     */
    public function getGroupConfig() {
        return $this->groupConfig;
    }

    /**
     * Fetches the resulting json object using
     * the scoutnet server's member list api.
     * @param string $uriVars The uri variables to apply.
     * @param bool $force Wether to force to fetch and cache.
     * @return object|false
     */
    public function fetchMemberListApi(string $uriVars, bool $force = false) {
        $memberListKey = $this->groupConfig->getMemberListKey();
        $uri = $this->getApiUri(self::API_MEMBERLIST_PATH, $uriVars);
        if ($force) {
            $memberList = $this->forceFetchWebPage($memberListKey, $uri);
        } else {
            $memberList = $this->fetchWebPage($memberListKey, $uri);
        }

        if ($memberList === false || strlen($memberList) === 0) {
            return false;
        }

        return json_decode($memberList);
    }

    /**
     * Fetches the resulting json object using
     * the scoutnet server's custom list api.
     * @param string $uriVars The uri variables to apply.
     * @param bool $force Wether to force to fetch and cache.
     * @return object|false
     */
    public function fetchCustomListsApi(string $uriVars, bool $force = false) {
        $customListsKey = $this->groupConfig->getCustomListsKey();
        $uri = $this->getApiUri(self::API_CUSTOMLISTS_PATH, $uriVars);
        if ($force) {
            $customList = $this->forceFetchWebPage($customListsKey, $uri);
        } else {
            $customList = $this->fetchWebPage($customListsKey, $uri);
        }

        if ($customList === false || strlen($customList) === 0) {
            return false;
        }

        return json_decode($customList);
    }

    /**
     * Builds a uri for a resource to be fetched from the scoutnet api.
     * @param string $apiPath
     * @param string $uriVars
     * @return string
     */
    private function getApiUri(string $apiPath, string $uriVars) {
        return "{$apiPath}?{$uriVars}&format=json";
    }

    /**
     * Builds a url for fetching a page from the scoutnet api.
     * @param string $apiKey
     * @param string $apiUri
     * @return string
     */
    private function getApiUrl(string $apiKey, string $apiUri) {
        $groupId = $this->groupConfig->getGroupId();
        return "https://{$groupId}:{$apiKey}@{$this->domain}/{$apiUri}";
    }

    /**
     * Fetches a webpage from the url.
     * @param string $apiKey
     * @param string $apiUri
     * @return string|false
     */
    private function fetchWebPage(string $apiKey, string $apiUri) {
        $url = $this->getApiUrl($apiKey, $apiUri);
        if ($this->cacheLifeTime == self::CACHE_DISABLE) {
            return $this->performPageRequest($url);
        } else {
            $this->lock();
            $result = $this->getCacheResource($apiUri);
            if (!$result) {
                $result = $this->performPageRequest($url);
                $this->setCacheResource($apiUri, $result);
            }
            $this->unlock();
            return $result;
        }
    }

    /**
     * Forces a webpage request and caches it if cache is enabled.
     * @param string $apiKey
     * @param string $apiUri
     * @return string|false
     */
    private function forceFetchWebPage(string $apiKey, string $apiUri) {
        $url = $this->getApiUrl($apiKey, $apiUri);
        $result = $this->performPageRequest($url);
        if (!$result) {
            return false;
        }
        if ($this->cacheLifeTime != self::CACHE_DISABLE) {
            $this->setCacheResource($apiUri, $result);
        }
        return $result;
    }

    /**
     * Performs one page request.
     * @param string $url
     * @return string|false
     */
    private function performPageRequest(string $url) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * Locks the current file's semaphore.
     * Waits until another process calls unlock()
     * if lock() has already been called.
     * @return void
     */
    private function lock() {
        if (!self::$sem) {
            $semId = ftok(__FILE__, 's');
            self::$sem = sem_get($semId);
        }
        sem_acquire(self::$sem);
    }

    /**
     * Unlocks the current file's semaphore.
     * @return void
     */
    private function unlock() {
        sem_release(self::$sem);
    }

    /**
     * Gets a cache resource from the APCu.
     * @param string $uri The resource uri (name of the resource in scoutnet).
     * @return mixed
     */
    private function getCacheResource(string $uri) {
        return apcu_fetch($this->getCacheKey($uri));
    }

    /**
     * Sets a cache resource in the APCu.
     * @param string $uri The resource uri (name of the resource inte scoutnet).
     * @param mixed $data The data to store.
     * @return mixed
     */
    private function setCacheResource(string $uri, $data) {
        return apcu_store($this->getCacheKey($uri), $data, $this->cacheLifeTime);
    }

    /**
     * Builds a cache key from a uri
     * @param string $uri
     * @return string
     */
    private function getCacheKey(string $uri) {
        $groupId = $this->groupConfig->getGroupId();
        return __FILE__.":{$groupId}:{$uri}";
    }
}