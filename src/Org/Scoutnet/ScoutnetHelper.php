<?php
/**
 * Contains ScoutnetHelper class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Helper class for use in scoutnet implementation.
 */
class ScoutnetHelper {
    private static $sem = null;

    /**
     * Fetches a webpage from the url.
     * @param string $url
     * @return string|false
     */
    public static function fetchWebPage(string $url) {
        if (ScoutnetController::getCacheLifeTime() == ScoutnetController::CACHE_DISABLE) {
            return self::performPageRequest($url);
        } else {
            self::lock();
            $result = self::getCacheResource($url);
            if (!$result) {
                $result = self::performPageRequest($url);
                self::setCacheResource($url, $result);
            }
            self::unlock();
            return $result;
        }
    }

    /**
     * Forces a webpage request and caches it if cache is enabled.
     * @param string $url
     * @return string|false
     */
    public static function forceFetchWebPage(string $url) {
        $result = self::performPageRequest($url);
        if (!$result) {
            return false;
        }
        if (ScoutnetController::getCacheLifeTime() != ScoutnetController::CACHE_DISABLE) {
            self::setCacheResource($url, $result);
        }
        return $result;
    }

    /**
     * Performs one page request.
     * @param string $url
     * @return string|false
     */
    private static function performPageRequest(string $url) {
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
     * Fetches multiple webpages concurrently.
     * Returns array where each result is stored on
     * the same key as in the input array.
     * Fails if any page request fails.
     * @param string[] $urls
     * @return string[]|false
     */
    public static function fetchWebPages(array $urls) {
        if (ScoutnetController::getCacheLifeTime() == ScoutnetController::CACHE_DISABLE) {
            return self::performPageRequests($urls);
        } else {
            self::lock();
            $results = array();
            $subsetToFetch = array();
            foreach ($urls as $key => $url) {
                $result = self::getCacheResource($url);
                if (!$result) {
                    $subsetToFetch[$key] = $url;
                } else {
                    $results[$key] = $result;
                }
            }
            if (!empty($subsetToFetch)) {
                $subsetResults = self::performPageRequests($subsetToFetch);
                if (!$subsetResults) {
                    self::unlock();
                    return false;
                } else {
                    foreach ($subsetToFetch as $key => $url) {
                        self::setCacheResource($url, $subsetResults[$key]);
                    }
                    $results = array_merge($results, $subsetResults);
                }
            }
            self::unlock();
            return $results;
        }
    }

    /**
     * Performs several page requests concurrently.
     * @param string[] $urls
     * @return string[]|false
     */
    private static function performPageRequests($urls) {
        $multiCurl = curl_multi_init();
        $handles = [];
        foreach ($urls as $key => $url) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_RETURNTRANSFER => true,
            ]);
            curl_multi_add_handle($multiCurl, $curl);
            $handles[$key] = $curl;
        }

        $running = null;
        do {
            curl_multi_exec($multiCurl, $running);
        } while ($running);
        
        $results = [];
        $success = true;
        foreach ($handles as $key => $handle) {
            $result = curl_multi_getcontent($handle);
            if ($result === false) {
                $success = false;
            }
            curl_multi_remove_handle($multiCurl, $handle);
            $results[$key] = $result;
        }
        curl_multi_close($multiCurl);
        if (!$success) {
            return false;
        }
        return $results;
    }

    private static function lock() {
        if (!self::$sem) {
            $sem_id = ftok(__FILE__, 's');
            self::$sem = sem_get($sem_id);
        }
        sem_acquire(self::$sem);
    }

    private static function unlock() {
        sem_release(self::$sem);
    }

    private static function getCacheResource($url) {
        return apcu_fetch($url);
    }

    private static function setCacheResource($url, $data) {
        return apcu_store($url, $data, ScoutnetController::getCacheLifeTime());
    }
}
