<?php

class ScoutOrgHelper {
    /**
     * Evaluates fieldtype name for given fieldtype
     * @param string $field field type name
     * @param \Org\Lib\Member $member
     * @return string|false
     */
    public static function renderField(string $field, string $code, $member) {
        if ($field === 'org-id') {
            return $member->getId();
        } elseif ($field === 'org-fullname') {
            $pInfo = $member->getPersonInfo();
            return "{$pInfo->getFirstname()} {$pInfo->getLastname()}";
        } elseif ($field === 'org-firstname') {
            return $member->getPersonInfo()->getFirstname();
        } elseif ($field === 'org-lastname') {
            return $member->getPersonInfo()->getLastname();
        } elseif ($field === 'org-age') {
            $now = new DateTime();
            $dateOfBirth = new DateTime($member->getPersonInfo()->getDateOfBirth());
            $age = $now->diff($dateOfBirth);
            return $age->format('%y');
        } elseif ($field === 'org-birthdate') {
            return $member->getPersonInfo()->getDateOfBirth();
        } elseif ($field === 'org-gender') {
            return $member->getPersonInfo()->getGender();
        } elseif ($field === 'org-ssno') {
            return $member->getPersonInfo()->getSsno();
        } elseif ($field === 'org-home') {
            $address = $member->getAccommodation()->getAddress();
            $postcode = $member->getAccommodation()->getPostCode();
            $posttown = $member->getAccommodation()->getPostTown();
            return "$address ($postcode $posttown)";
        } elseif ($field === 'org-emails') {
            return implode('<br/>', $member->getContactInfo()->getEmailAddresses());
        } elseif ($field === 'org-telnrs') {
            return implode('<br/>', $member->getContactInfo()->getPhoneNumbers());
        } elseif ($field === 'org-startdate') {
            return $member->getStartdate();
        } elseif ($field === 'org-contacts') {
            $contacts = array();
            foreach ($member->getContacts() as $contact) {
                $name = $contact->getName();
                $emails = implode('<br/>', $contact->getContactInfo()->getEmailAddresses());
                $telnrs = implode('<br/>', $contact->getContactInfo()->getPhoneNumbers());
                $contacts[] = "<h6>$name</h6><p>$emails<br/>$telnrs</p>";
            }
            return implode('', $contacts);
        } elseif ($field === 'org-troops') {
            $troops = array();
            foreach ($member->getTroops() as $troop) {
                $troops[] = $troop->getName();
            }
            return implode('', $troops);
        } elseif ($field === 'org-rolegroups') {
            $roles = array();
            foreach ($member->getRoleGroups() as $roleGroup) {
                $roles[] = $roleGroup->getRoleName();
            }
            return implode(', ', $roles);
        } elseif ($field === 'org-code') {
            return eval($code);
        } else {
            return false;
        }
    }
}