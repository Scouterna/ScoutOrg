<?php
/**
 * Contains CustomList class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A custom member list with sub lists.
 */
class CustomList {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var Member[] */
    private $members;

    /** @var CustomSubList[] */
    private $subListsIdIndexed;

    /** @var CustomSubList[] */
    private $subListsTitleIndexed;

    /**
     * Creates a new custom list.
     * @param int $id
     * @param string $title
     * @param string $description
     */
    public function __construct(int $id, string $title, string $description) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->members = new \ArrayObject();
        $this->subListsIdIndexed = new \ArrayObject();
        $this->subListsTitleIndexed = new \ArrayObject();
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
        return $this->members->getArrayCopy();
    }

    /**
     * Gets a list of sublists.
     * @param bool $idIndexed Wether to index the list by id or title.
     * @return CustomSubList[]
     */ 
    public function getSubLists(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->subListsIdIndexed->getArrayCopy();
        } else {
            return $this->subListsTitleIndexed->getArrayCopy();
        }
    }
}