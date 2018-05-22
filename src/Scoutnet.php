<?php
namespace Org;
require_once(__DIR__.'/Lib.php');

class Scoutnet {
    private static $loadedScoutGroups = [];

    private function __construct(){
    }

    /**
     * Loads the group structure from scoutnet.
     * @param int $groupId The scout group's scoutnet id.
     * @param string $key The api key.
     * @return ScoutGroup A scoutgroup.
     */
    public static function loadScoutGroup(int $groupId, string $key) {
        if (isset(Scoutnet::$loadedGroups[$groupId])) {
            return Scoutnet::$loadedGroups[$groupId];
        }

        $memberList = Scoutnet::fetchMemberList($groupId, $key);
        
        $members = [];
        $troops = [];
        $roleGroups = [];

        foreach ($memberList as $memberRow) {
            // Check if troop exists
            if (isset($troops[$memberRow['unit']['value']])) {
                $troop = $troops[$memberRow['unit']['value']];
                // Check if patrol exists
                if (isset($troop->getPatrols()[$memberRow['patrol']['value']])) {

                } else {
                    // Create new patrol

                }
            } else {
                // Create new troop

            }
            // Go through each group role
            foreach (split($memberRow['group_role']['value'],', ') as $role) {
                // Check if role group exists
                if (isset($roleGroups[$role])) {
                    
                } else {
                    // Create new role group

                }
            }
            
        }

        Scoutnet::$loadedScoutGroups[$groupId] = $scoutGroup;
    }
    /**
     * Fetches member list from scoutnet.
     * @param int $groupId The scout group scoutnet id.
     * @param string $key The api key.
     * @return array A json parsed member list.
     */
    private static function fetchMemberList(int $groupId, string $key) {
        $curl = \curl_init();

        \curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://{$groupId}:{$key}@www.scoutnet.se/api/group/memberlist?format=json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        
        $memberList = \curl_exec($curl);

        return \json_decode($memberList);
    }
}