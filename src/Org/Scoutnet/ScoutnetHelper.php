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
    /**
     * Fetches a webpage from the url.
     * @param string $url
     * @return string|false
     */
    public static function fetchWebPage(string $url) {
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
     * Fetches multiple webpages concurrently.
     * Returns array where results are stored at each respective key.
     * Fails if any fetch fails.
     * @param string[] $urls
     * @return string[]|false
     */
    /** @return string[]|false */
    public static function fetchWebPages(array $urls) {
        $multiCurl = curl_multi_init();
        $handles = [];
        foreach ($urls as $key => $url) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE,
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
}