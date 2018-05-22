<?php
namespace Org;
require_once(__DIR__.'/Lib.php');

class Troop {
    private $id;
    private $name;
    private $members;
    private $patrols;
    
    /**
     * Creates a new troop with the specified properties, members, and patrols.
     * @param int $id The troop scoutnet id.
     * @param string $name The troop name.
     * @param Member[] $members The list of members.
     * @param Patrol[] $patrols The list of patrols.
     */
    public function __construct(int $id, string $name, array $members, array $patrols) {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->patrols = $patrols;
    }

    /**
     * Gets the id of the troop.
     * @return int A troop id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the name of the troop.
     * @return string A troop name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the list of member of the troop.
     * @return Member[] A list of members.
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * Gets the list of patrols of the troop.
     * @return Patrol[] A list of patrols.
     */
    public function getPatrols() {
        return $this->patrols;
    }
}