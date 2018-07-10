<?php
/**
 * Contains Branch class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A branch (gren) that contains troops
 */
class Branch {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var Troop[] */
    private $troopsIdIndexed;

    /** @var Troop[] */
    private $troopsNameIndexed;
    
    /**
     * Creates a new branch with an id and name.
     * @internal
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
    }

    /**
     * Adds a troop.
     * @param Troop $troop
     * @return void
     */
    private function addTroop(Troop $troop) {
        $this->troopsIdIndexed[$troop->getId()] = $troop;
        $this->troopsNameIndexed[$troop->getName()] = $troop;
    }

    /**
     * Gets the branch id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the branch name.
     * @return string
     */ 
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the list of troops in the branch.
     * @param bool $idIndexed Wether to index the list by id or name.
     * @return Troop[]
     */ 
    public function getTroops(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->troopsIdIndexed->getArrayCopy();
        } else {
            return $this->troopsNameIndexed->getArrayCopy();
        }
    }
}