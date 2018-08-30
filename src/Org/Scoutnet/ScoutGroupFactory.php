<?php
/**
 * Contains ScoutGroupFactory class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * Builds a scout group from scoutnet.
 */
class ScoutGroupFactory implements Lib\IScoutGroupProvider {
    /** @var ScoutnetController The source of data of which a scout group is built. */
    private $scoutnet;

    /** @var BranchConfig[] The list of configurations for which branches to create. */
    private $branchConfigs;

    /**
     * Creates a new scout group factory.
     * @param ScoutnetController $scoutnet
     * @param BranchConfig[] $branchConfigs
     */
    public function __construct(ScoutnetController $scoutnet,
                                array $branchConfigs = []) {
        $this->scoutnet = $scoutnet;
        $this->branchConfigs = $branchConfigs;
    }

    /**
     * Creates a new scoutgroup from scoutnet.
     */
    public function getScoutGroup() {
        $scoutGroup = new Lib\ScoutGroup($this->scoutnet->getGroupId());

        // Arrays for keeping existing instances so as
        // not to accidentally keep duplicate instances.
        $members = [];
        $troops = [];
        $patrols = [];
        $roleGroups = [];
        $branches = [];

        if (($memberEntrys = $this->scoutnet->getMemberList()) === false) {
            return false;
        }
        foreach ($memberEntrys as $memberEntry) {
            // Create member.
            $member = $memberEntry->getMember();
            $members[$member->getId()] = $member;

            // Assemble scout group and member.
            $scoutGroup->addMember($member);

            // Create troop or get existing.
            // Skips if there is none.
            $memberTroop = $memberEntry->getTroop();
            if ($memberTroop !== null) {
                if (isset($troops[$memberTroop->getId()])) {
                    $memberTroop = $troops[$memberTroop->getId()];
                } else {
                    $troops[$memberTroop->getId()] = $memberTroop;
                }

                // Assemble scout group and troop.
                // May have redundant assignment.
                $scoutGroup->addTroop($memberTroop);

                // Assemble member and troop.
                $memberTroopRole = $memberEntry->unit_role !== null ? $memberEntry->unit_role->value : '';
                $memberTroop->addMember($member, $memberTroopRole);
                $member->addTroop($memberTroop);
            }

            // Create patrol or get existing.
            // Skips if there is none.
            $memberPatrol = $memberEntry->getPatrol();
            if ($memberPatrol !== null) {
                if (isset($patrols[$memberPatrol->getId()])) {
                    $memberPatrol = $patrols[$memberPatrol->getId()];
                } else {
                    $patrols[$memberPatrol->getId()] = $memberPatrol;
                }

                // Assemble member and patrol.
                $memberPatrol->addMember($member);
                $member->addPatrol($memberPatrol);

                // Assemble troop and patrol.
                // May have redundant assignment.
                $memberTroop->addPatrol($memberPatrol);
                $memberPatrol->setTroop($memberTroop);
            }

            // Create role groups or get existing.
            // Skips if there are none.
            $memberRoleGroups = $memberEntry->getRoleGroups();
            if ($memberRoleGroups !== null) {
                $actualMemberRoleGroups = [];
                foreach ($memberRoleGroups as $roleGroupId => $memberRoleGroup) {
                    if (isset($roleGroups[$roleGroupId])) {
                        $actualMemberRoleGroups[$roleGroupId] = $roleGroups[$roleGroupId];
                    } else {
                        $roleGroups[$roleGroupId] = $memberRoleGroup;
                    }
                }
                $memberRoleGroups = $actualMemberRoleGroups;

                // Assemble scout group and role groups.
                // Assemble member and role groups.
                // May have redundant assignments.
                foreach ($memberRoleGroups as $memberRoleGroup) {
                    $scoutGroup->addRoleGroup($memberRoleGroup);
                    $memberRoleGroup->addMember($member);
                    $member->addRoleGroup($memberRoleGroup);
                }
            }
        }

        // Create branches from config.
        // Will ignore troops that already have a branch.
        // Will ignore invalid troops.
        // Will not ignore two configs with same branch id.
        foreach ($this->branchConfigs as $config) {
            $branch = new Lib\Branch($config->getBranchId(), $config->getBranchName());
            if (isset($branches[$config->getBranchId()])) {
                $branch = $branches[$config->getBranchId()];
            }

            $scoutGroup->addBranch($branch);
            foreach ($config->getTroopIds() as $troopId) {
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

        return $scoutGroup;
    }
}