<?php
/**
 * Contains ScoutnetWaitingListFactory class
 * @author Alexander Krantz
 */
namespace Org\Models;
use Org\Scoutnet;

/**
 * A factory for generating a list of members waiting for placement.
 */
class ScoutnetWaitingListFactory implements IWaitingListProvider {
    /** @var \Org\Scoutnet\ScoutnetController */
    private $scoutnet;

    /**
     * Creates a new waiting list factory.
     * @param \Org\Scoutnet\ScoutnetController $scoutnet The scoutnet link.
     */
    public function __construct(Scoutnet\ScoutnetController $scoutnet) {
        $this->scoutnet = $scoutnet;
    }

    /**
     * Gets the waiting list of the scout group.
     * @return WaitingMember[]
     */
    public function getWaitingList() {
        $waitingListEntrys = $this->scoutnet->getWaitingList();
        $returnList = [];
        foreach ($waitingListEntrys as $entry) {
            $newWaitingMember = new WaitingMember($entry->member_no->value);
            
            $newWaitingMember->personInfo = $this->getPersonInfo($entry);
            $newWaitingMember->contactInfo = $this->getContactInfo($entry);
            $newWaitingMember->accommodation = $this->getAccommodation($entry);
            $newWaitingMember->contacts = $this->getContacts($entry);

            $newWaitingMember->waitingStartdate = $entry->waiting_since;
            $newWaitingMember->note = $entry->note;

            $returnList[] = $newWaitingMember;
        }
        return $returnList;
    }

    /** @return PersonInfo */
    private function getPersonInfo(Scoutnet\WaitingMemberEntry $entry) {
        $personInfo = new PersonInfo($entry->first_name,
            $entry->last_name,
            $entry->ssno,
            $entry->sex,
            $entry->date_of_birth);
        return $personInfo;
    }

    /** @return ContactInfo */
    private function getContactInfo(Scoutnet\WaitingMemberEntry $entry) {
        $phoneNumbers = [];
        $emailAddresses = [];
        if ($entry->email !== NULL) {
            $emailAddresses[] = $entry->email;
        }
        return new ContactInfo($phoneNumbers, $emailAddresses);
    }

    /** @return Location */
    private function getAccommodation(Scoutnet\WaitingMemberEntry $entry) {
        return new Location($entry->address_1, $entry->postcode, $entry->town);
    }

    /** @return Contact[] */
    private function getContacts(Scoutnet\WaitingMemberEntry $entry) {
        $contacts = [];
        // Create contact 1
        if ($entry->contact_mothers_name !== NULL) {
            $phoneNumbers = [
                $entry->contact_mobile_mum,
            ];
            $emails = [
                $entry->contact_email_mum,
            ];
            $contactInfo = new ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Contact($entry->contact_mothers_name, $contactInfo);
        }
        // Create contact 2
        if ($entry->contact_fathers_name !== NULL) {
            $phoneNumbers = [
                $entry->contact_mobile_dad,
            ];
            $emails = [
                $entry->contact_email_dad,
            ];
            $contactInfo = new ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Contact($entry->contact_fathers_name, $contactInfo);
        }
        return $contacts;
    }
}