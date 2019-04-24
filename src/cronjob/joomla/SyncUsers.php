<?php

require_once "InitJoomla.php";

define('GROUPNAME', 'Scoutnet');
define('UNIPASSWORD', '');
define('SALTSTRENGTH', 4); // Between 4 and 31. Default is 10. Higher may take too long.

jimport('scoutorg.loader');
jimport('joomla.table');
jimport('joomla.user');
jimport('joomla.user.helper');

/**
 * Get or create joomla group
 * @param string $title
 * @param int $parentId
 */
function createJoomlaGroup(string $title, int $parentId) {
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
 * Adds a new joomla user
 * @param \Org\Lib\Member $member
 * @param int $groupId
 */
function addUser(\Org\Lib\Member $member, int $groupId) {
    $personInfo = $member->getPersonInfo();
    echo "Adding user {$member->getId()} {$personInfo->getFirstname()} {$personInfo->getLastname()}\n<br/>";
    $user = new JUser();
    $user->name = "{$personInfo->getFirstname()} {$personInfo->getLastname()}";
    $user->username = "{$member->getId()}";
    $user->password = JUserHelper::hashPassword(UNIPASSWORD, PASSWORD_BCRYPT, ['cost' => SALTSTRENGTH]); // default salted password
    if (isset($member->getContactInfo()->getEmailAddresses()[0])) {
        $user->email = $member->getContactInfo()->getEmailAddresses()[0];
    } else {
        $user->email = 'none@example.com';
    }
    $user->block = 0;
    $user->groups = array($groupId);
    $user->save();
}

/**
 * Updates an existing user with the corresponding member data
 * @param JUser $user
 * @param \Org\Lib\Member $member
 */
function updateUser($user, \Org\Lib\Member $member) {
    $personInfo = $member->getPersonInfo();
    echo "Updating user {$user->username} {$user->name}";
    echo " -> {$member->getId()} {$personInfo->getFirstname()} {$personInfo->getLastname()}\n<br/>";
    $user->name = "{$personInfo->getFirstname()} {$personInfo->getLastname()}";
    if (isset($member->getContactInfo()->getEmailAddresses()[0])) {
        $user->email = $member->getContactInfo()->getEmailAddresses()[0];
    } else {
        $user->email = 'none@example.com';
    }
    $user->save();
}

$scoutOrg = ScoutOrgLoader::load();
$scoutGroup = $scoutOrg->getScoutGroup();

$registeredGroup = JTable::getInstance('Usergroup');
if (!$registeredGroup->load(['title' => 'Registered'])) {
    die('There needs to be a group named "Registered"');
}

// Create root group
$scoutorgGroup = createJoomlaGroup(GROUPNAME, $registeredGroup->id);

// Get all current users (exist in root group).
$userIds = JAccess::getUsersByGroup($scoutorgGroup->id);

// Find member for every user and determine which ones don't ealready exist
$membersToAdd = $scoutGroup->getMembers();
foreach ($userIds as $userId) {
    $user = JFactory::getUser($userId);
    if (isset($membersToAdd[intval($user->username)])) {
        updateUser($user, $membersToAdd[intval($user->username)]);
        unset($membersToAdd[intval($user->username)]); // user already exists
    } else {
        echo "Removing user {$user->username} {$user->name}\n<br/>";
        $user->delete();
    }
}

// Add missing users
foreach ($membersToAdd as $member) {
    addUser($member, $scoutorgGroup->id);
}
