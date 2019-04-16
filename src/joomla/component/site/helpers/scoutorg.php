<?php

class ScoutOrgHelper {
    /**
     * Evaluates fieldtype name for given fieldtype
     * @param object $field field type name
     * @param \Org\Lib\Member $member
     * @return string|false
     */
    public static function renderField($field, $member) {
        if ($field->fieldtype === 'org-id') {
            return $member->getId();
        } elseif ($field->fieldtype === 'org-fullname') {
            $pInfo = $member->getPersonInfo();
            return "{$pInfo->getFirstname()} {$pInfo->getLastname()}";
        } elseif ($field->fieldtype === 'org-firstname') {
            return $member->getPersonInfo()->getFirstname();
        } elseif ($field->fieldtype === 'org-lastname') {
            return $member->getPersonInfo()->getLastname();
        } elseif ($field->fieldtype === 'org-age') {
            $now = new DateTime();
            $dateOfBirth = new DateTime($member->getPersonInfo()->getDateOfBirth());
            $age = $now->diff($dateOfBirth);
            return $age->format('%y');
        } elseif ($field->fieldtype === 'org-birthdate') {
            return $member->getPersonInfo()->getDateOfBirth();
        } elseif ($field->fieldtype === 'org-gender') {
            return $member->getPersonInfo()->getGender();
        } elseif ($field->fieldtype === 'org-ssno') {
            return $member->getPersonInfo()->getSsno();
        } elseif ($field->fieldtype === 'org-home') {
            $address = $member->getAccommodation()->getAddress();
            $postcode = $member->getAccommodation()->getPostCode();
            $posttown = $member->getAccommodation()->getPostTown();
            return "$address ($postcode $posttown)";
        } elseif ($field->fieldtype === 'org-emails') {
            return implode('<br/>', $member->getContactInfo()->getEmailAddresses());
        } elseif ($field->fieldtype === 'org-telnrs') {
            return implode('<br/>', $member->getContactInfo()->getPhoneNumbers());
        } elseif ($field->fieldtype === 'org-startdate') {
            return $member->getStartdate();
        } elseif ($field->fieldtype === 'org-contacts') {
            $contacts = array();
            foreach ($member->getContacts() as $contact) {
                $name = $contact->getName();
                $emails = implode('<br/>', $contact->getContactInfo()->getEmailAddresses());
                $telnrs = implode('<br/>', $contact->getContactInfo()->getPhoneNumbers());
                $contacts[] = "<h6>$name</h6><p>$emails<br/>$telnrs</p>";
            }
            return implode('', $contacts);
        } elseif ($field->fieldtype === 'org-troops') {
            $troops = array();
            foreach ($member->getTroops() as $troop) {
                $troops[] = $troop->getName();
            }
            return implode('', $troops);
        } elseif ($field->fieldtype === 'org-rolegroups') {
            $roles = array();
            foreach ($member->getRoleGroups() as $roleGroup) {
                $roles[] = $roleGroup->getRoleName();
            }
            return implode(', ', $roles);
        } elseif ($field->fieldtype === 'org-code') {
            return eval($field->fieldcode);
        } else {
            return false;
        }
    }
}