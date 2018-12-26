<?php

require_once "InitJoomla.php";

define('GROUPNAME', 'Scoutnet');
define('UNIPASSWORD', '');
define('SALTSTRENGTH', 4); // Between 4 and 31. Default is 10. Higher may take too long.

jimport('scoutorg.loader');
$scoutGroup = ScoutOrgLoader::load()->getScoutGroup();

// Get or create root group
jimport('joomla.table');

$registeredGroup = JTable::getInstance('Usergroup');
if (!$registeredGroup->load(['title' => 'Registered'])) {
    die('There needs to be a group named "Registered"');
}
$joomlaGroup = JTable::getInstance('Usergroup');
if (!$joomlaGroup->load(['title' => GROUPNAME])) {
    $joomlaGroup->save([
        'title' => GROUPNAME,
        'parent_id' => $registeredGroup->id,
    ]);
}

// Get all current users.
$userIds = JAccess::getUsersByGroup($joomlaGroup->id);

// Find member for every user and determine correct list of users
$usersToRemove = [];
$membersToAdd = $scoutGroup->getMembers();
$usersToUpdate = [];
foreach ($userIds as $index => $userId) {
    $user = JFactory::getUser($userId);
    if (isset($membersToAdd[intval($user->username)])) {
        unset($membersToAdd[intval($user->username)]); // user already exists
        $usersToUpdate[] = $user; // Update users that already exist
    } else {
        $usersToRemove[] = $user; // member doesn't exist anymore
    }
}

// Remove users
foreach ($usersToRemove as $user) {
    echo "Removing user {$user->username} {$user->name}\n<br/>";
    $user->delete();
}

// Add missing users
jimport('joomla.user');
jimport('joomla.user.helper');
foreach ($membersToAdd as $member) {
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
    $user->groups = array($joomlaGroup->id);
    $user->save();
}

// Update existing users
foreach ($usersToUpdate as $user) {
    $member = $scoutGroup->getMembers()[intval($user->username)];
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

// TODO: Arrange groups? Branches, troops, rolegroups, leaders
// TODO: Set access levels?