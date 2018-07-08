<?php
/**
 * Contains CustomListMemberEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Contains fields from that are equivalent to custom list members from scoutnet.
 */
class CustomListMemberEntry {
    /** @var Value The member's email. */
    public $email;

    /** @var Value The member's other emails. */
    public $extra_emails;

    /** @var Value The member's scoutnet id. */
    public $member_no;

    /** @var Value The member's first name. */
    public $first_name;

    /** @var Value The member's last name. */
    public $last_name;
    
    /**
     * Creates a new custom list member from a scoutnet entry.
     * @param object $entry
     */
    public function __construct($entry) {
        foreach ($entry as $dataFieldName => $dataField) {
            $this->{$dataFieldName} = new Value($dataField);
        }
    }
}