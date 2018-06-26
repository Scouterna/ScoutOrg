<?php
namespace Org\Integrations;

interface iDataSource {
    /**
     * Gets the member list of a scout group.
     * @param int $groupId
     * @param string $key
     * @return MemberEntry[] A list of members.
     */
    public function getMemberList(int $groupId, string $key);
}