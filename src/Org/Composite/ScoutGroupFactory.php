<?php
/**
 * Contains ScoutGroupFactory class
 * @author Alexander Krantz
 */
namespace Org\Composite;
use Org\Scoutnet;
use Org\Lib;

class ScoutGroupFactory implements Lib\IScoutGroupProvider {
    /** @var Scoutnet\ScoutnetController */
    private $scoutnet;
    
    /** @var BranchConfig[] */
    private $branchConfigs;

    /** @var TroopConfig[] */
    private $troopConfigs;

    /** @var RoleGroupConfig[] */
    private $roleGroupConfigs;

    public function __construct(
        Scoutnet\ScoutnetController $scoutnetController,
        array $branchConfigs,
        array $troopConfigs,
        array $roleGroupConfigs
    ) {
        $this->scoutnet = $scoutnetController;
        $this->branchConfigs = $branchConfigs;
        $this->troopConfigs = $troopConfigs;
        $this->roleGroupConfigs = $roleGroupConfigs;
    }

    public function getScoutGroup() {
        $scoutGroup = new Lib\ScoutGroup($this->scoutnet->getGroupId());

        // Fetch members from scoutnet.
        $memberEntrys = $this->scoutnet->getMemberList();
        if ($memberEntrys === false) {
            return false;
        }

        // Add members from scoutnet.
        foreach ($memberEntrys as $memberEntry) {
            $scoutGroup->addMember($memberEntry->getMember());
        }

        // Create troops from configs.
        foreach ($this->troopConfigs as $troopConfig) {
            $troop = new Lib\Troop($troopConfig->getId(), $troopConfig->getName());
            if (isset($scoutGroup->getTroops(true)[$troopConfig->getId()])) {
                $troop = $scoutGroup->getTroops(true)[$troopConfig->getId()];
            }
            $scoutGroup->addTroop($troop);

            foreach ($troopConfig->getMembers() as $memberConfig) {
                $member = $scoutGroup->getMembers()[$memberConfig->getId()];
                if ($member == null) {
                    continue;
                }
                $troop->addMember($member,$memberConfig->getRole());
            }

            // Create patrols in troop from configs.
            foreach ($troopConfig->getPatrols() as $patrolConfig) {
                $patrol = new Lib\Patrol($patrolConfig->getId(), $patrolConfig->getName());
                if (isset($troop->getPatrols(true)[$patrolConfig->getId()])) {
                    $patrol = $troop->getPatrols(true)[$patrolConfig->getId()];
                }
                $troop->addPatrol($patrol);

                foreach ($patrolConfig->getMembers() as $memberConfig) {
                    $member = $scoutGroup->getMembers()[$memberConfig->getId()];
                    if ($member == null) {
                        continue;
                    }
                    $patrol->addMember($member, $memberConfig->getRole());
                }
            }
        }

        // Create branches from configs.
        // Will ignore troops that already have a branch.
        // Will ignore invalid troops.
        // Will not ignore two configs with same branch id.
        foreach ($this->branchConfigs as $branchConfig) {
            $branch = new Lib\Branch($branchConfig->getId(), $branchConfig->getName());
            if (isset($scoutGroup->getBranches(true)[$branchConfig->getId()])) {
                $branch = $branches[$branchConfig->getId()];
            }

            $scoutGroup->addBranch($branch);
            foreach ($branchConfig->getTroops() as $troopId) {
                $troop = $scoutGroup->getTroops(true)[$troopId];
                if ($troop == null) {
                    continue;
                }
                if ($troop->getBranch() === null) {
                    $branch->addTroop($troop);
                    $troop->setBranch($branch);
                }
            }
        }

        // Create role groups from configs.
        foreach ($this->roleGroupConfigs as $roleGroupConfig) {
            $roleGroup = new Lib\RoleGroup($roleGroupConfig->getId(), $roleGroupConfig->getName());
            if (isset($scoutGroup->getRoleGroups(true)[$roleGroupConfig->getId()])) {
                $roleGroup = $scoutGroup->getRoleGroups(true)[$roleGroupConfig->getId()];
            }
            $scoutGroup->addRoleGroup($roleGroup);

            foreach ($roleGroupConfig->getMembers() as $memberId) {
                $member = $scoutGroup->getMembers()[$memberId];
                if ($member == null) {
                    continue;
                }
                $roleGroup->addMember($member);
            }
        }

        return $scoutGroup;
    }
}