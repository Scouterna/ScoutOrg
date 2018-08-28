<?php

$orgLib = __DIR__.'/../'; // Set to location of ScoutOrg lib
$domain = 'www.scoutnet.se'; // Set to scoutnet server.
$cacheLifeTime = 60 * 45; // 45 minutes. Set to cache ttl in seconds.
$groupId = 0; // Set to the group's scoutnet id.
$memberListKey = ''; // Set to the member list api key.
$customListsKey = ''; // Set to the custom lists api key.

spl_autoload_register(function ($class_name) {
    global $orgLib;
    $file = $orgLib.str_replace('\\', '/', $class_name).'.php';
    if (file_exists($file)) {
        include $file;
    }
});

$groupConfig = new \Org\Scoutnet\ScoutGroupConfig($groupId, $memberListKey, $customListsKey);
$connection = new \Org\Scoutnet\ScoutnetConnection($groupConfig, $domain, $cacheLifeTime);

// Get member list
{
    $uriVars = '';
    $result = $connection->fetchMemberListApi($uriVars, true);
    if ($result) {
        echo "<p>Fetched member list</p>\n";
    } else {
        echo "<p>Failed fetching member list</p>\n";
    }
}

// Get waiting list
{
    $uriVars = 'waiting=1';
    $result = $connection->fetchMemberListApi($uriVars, true);
    if ($result) {
        echo "<p>Fetched waiting list</p>\n";
    } else {
        echo "<p>Failed fetching waiting list</p>\n";
    }
}

// Get Custom lists
{
    $uriVars = '';
    $customLists = $connection->fetchCustomListsApi($uriVars, true);
    if ($result) {
        echo "<p>Fetched custom lists</p>\n";
        foreach ($customLists as $customList) {
            $uriVars = "list_id={$customList->id}";
            $customListMembers = $connection->fetchCustomListsApi($uriVars, true);
            if ($customListMembers) {
                echo "<p>Fetched members of list {$customList->id}</p>\n";
            } else {
                echo "<p>Failed fetching members of list {$customList->id}</p>\n";
            }
            foreach ($customList->rules as $rule) {
                $uriVars = "list_id={$customList->id}&rule_id={$rule->id}";
                $ruleMembers = $connection->fetchCustomListsApi($uriVars, true);
                if ($customListMembers) {
                    echo "<p>Fetched members of list {$customList->id} rule {$rule->id}</p>\n";
                } else {
                    echo "<p>Failed fetching members of list {$customList->id} rule {$rule->id}</p>\n";
                }
            }
        }
    } else {
        echo "<p>Failed fetching custom lists</p>";
    }
}