<?php
/**
 * Contains CustomSubList class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A custom sublist.
 */
class CustomSubList {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var Member[] */
    private $members;

    /**
     * Creates a new custom sub list.
     * @param int $id
     * @param string $title
     */
    public function __construct(int $id, string $title) {
        $this->id = $id;
        $this->title = $title;
        $this->members = new \ArrayObject();
    }

    /**
     * Gets the sublist id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the sublist title.
     * @return string
     */ 
    public function getTitle() {
        return $this->title;
    }

    /**
     * Gets the members in the sublist.
     * @return Member[]
     */ 
    public function getMembers() {
        return $this->members->getArrayCopy();
    }
}