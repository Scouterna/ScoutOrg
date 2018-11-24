<?php
/**
 * Contains CustomList class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A custom member list with sub lists.
 */
class CustomList {
    use InternalTrait;

    /** @var int The list id. */
    private $id;

    /** @var string The list name. */
    private $title;

    /** @var string The description of the list. */
    private $description;

    /** @var Member[] The members of the list indexed by their id. */
    private $members;

    /** @var CustomList[] The sub lists of the list indexed by their id. */
    private $subListsIdIndexed;

    /** @var CustomList[] The sub lists of the list indexed by their name. */
    private $subListsTitleIndexed;

    /**
     * Creates a new custom list.
     * @internal
     * @param int $id
     * @param string $title
     * @param string $description
     */
    public function __construct(int $id, string $title, string $description) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->members = array();
        $this->subListsIdIndexed = array();
        $this->subListsTitleIndexed = array();
    }

    /** 
     * Adds a member.
     * @param Member $member
     * @return void
     */
    private function addMember(Member $member) {
        $this->members[$member->getId()] = $member;
    }

    /**
     * Adds a sub list.
     * @param CustomList $subList
     * @return void
     */
    private function addSubList(CustomList $subList) {
        $this->subListsIdIndexed[$subList->getId()] = $subList;
        $this->subListsTitleIndexed[$subList->getTitle()] = $subList;
    }

    /**
     * Gets the list id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the list title.
     * @return string
     */ 
    public function getTitle() {
        return $this->title;
    }

    /**
     * Gets the list description.
     * @return string
     */ 
    public function getDescription() {
        return $this->description;
    }

    /**
     * Gets the members of the list.
     * @return Member[]
     */ 
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Gets a list of sublists.
     * @param bool $idIndexed Wether to index the list by id or title.
     * @return CustomList[]
     */ 
    public function getSubLists(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->subListsIdIndexed;
        } else {
            return $this->subListsTitleIndexed;
        }
    }
}