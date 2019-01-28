<?php

require_once "InitJoomla.php";

define('GROUPNAME', 'Scoutnet');

jimport('scoutorg.loader');
jimport('joomla.table');
jimport('joomla.user');
jimport('joomla.user.helper');

/**
 * Get or create joomla group
 * @param string $title
 * @param int $parentId
 */
function getJoomlaGroup(string $title, int $parentId) {
    $group = JTable::getInstance('Usergroup');
    if (!$group->load(['title' => $title])) {
        $group->save([
            'title' => $title,
            'parent_id' => $parentId,
        ]);
    }
    return $group;
}

/**
 * Update users in joomla group from custom list data
 * @param JUsergroup $group
 * @param \Org\Lib\CustomList $customList
 * @param JUser[] $allUsers
 */
function updateGroupUsers($group, \Org\Lib\CustomList $customList, array &$allUsers) {
    $userIds = JAccess::getUsersByGroup($group->id);
    $membersToAdd = $customList->getMembers();
    foreach ($userIds as $userId) {
        $user = JFactory::getUser($userId);
        if (isset($membersToAdd[intval($user->username)])) {
            unset($membersToAdd[intval($user->username)]); // user is already in group
        } else {
            echo "Removing user {$user->username} {$user->name} from group {$group->title}<br/>\n";
            JUserHelper::removeUserFromGroup($user->id, $group->id);
        }
    }
    foreach ($membersToAdd as $member) {
        $user = $allUsers[$member->getId()];
        echo "Adding user {$user->username} {$user->name} to group {$group->title}<br/>\n";
        JUserHelper::addUserToGroup($user->id, $group->id);
    }
}

/**
 * Get subgroups of joomla group
 * @param int $parentId
 */
function getSubGroups(int $parentId) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName(['id', 'title']));
    $query->from($db->quoteName('#__usergroups'));
    $query->where($db->quoteName('parent_id').'='.intval($parentId));
    $db->setQuery($query);
    return $db->loadObjectList('title');
}

/**
 * Create joomla groups from custom lists
 * @param \Org\Lib\CustomList[] $customLists
 * @param int $parentId
 * @param JUser $allUsers
 */
function createGroups(array $customLists, int $parentId, array &$allUsers) {
    $subGroupsToRemove = getSubGroups($parentId);
    foreach ($customLists as $customList) {
        $title = "{$customList->getId()}-{$customList->getTitle()}";
        if (isset($subGroupsToRemove[$title])) {
            $subGroup = $subGroupsToRemove[$title];
            updateGroupUsers($subGroup, $customList, $allUsers); // update group as it exist
            createGroups($customList->getSubLists(), $subGroup->id, $allUsers);
            unset($subGroupsToRemove[$title]); // group exists
        } else {
            $subGroup = getJoomlaGroup($title, $parentId);
            updateGroupUsers($subGroup, $customList, $allUsers);
            createGroups($customList->getSubLists(), $subGroup->id, $allUsers);
        }
    }
    foreach ($subGroupsToRemove as $row) {
        $subGroup = JTable::getInstance('Usergroup');
        $subGroup->delete($row['id']);
    }
}

$scoutOrg = ScoutOrgLoader::load();

$registeredGroup = JTable::getInstance('Usergroup');
if (!$registeredGroup->load(['title' => 'Registered'])) {
    error_log('SyncGroups.php: There needs to be a group named "Registered"');
    exit(1);
}

// Get root group and create groups from custom lists
$rootGroup = getJoomlaGroup(GROUPNAME, $registeredGroup->id);

// Get all users indexed by scoutnet id to find them 
// when updating groups based on scoutnet custom lists
$allUserIds = JAccess::getUsersByGroup($rootGroup->id);
$allUsers = array();
foreach ($allUserIds as $userId) {
    $user = JFactory::getUser($userId);
    $allUsers[intval($user->username)] = $user;
}

createGroups($scoutOrg->getCustomLists(), $rootGroup->id, $allUsers);

// TODO: Set access levels?