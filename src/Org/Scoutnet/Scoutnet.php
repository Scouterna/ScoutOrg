<?php
namespace Org\Scoutnet;

/**
 * Contains methods for getting data from scoutnet.
 */
class Scoutnet {
    /** @var Scoutnet[] */
    private static $multitons = [];

    /** @var string */
    private $domain;

    /** @var array[] */
    private $loadedMemberLists = [];

    /**
     * Creates a new scoutnet link with the specified domain.
     * @param string $domain The domain of the scoutnet server to fetch data from.
     */
    private function __construct(string $domain) {
        $this->domain = $domain;
    }

    /**
     * Gets instance of scoutnet link with the specified domain.
     * @param string $domain The domain of the scoutnet server to fetch data from.
     * @return Scoutnet
     */
    public static function getMultiton(string $domain) {
        if (isset(Scoutnet::$multitons[$domain])) {
            return Scoutnet::$multitons[$domain];
        }
        $multiton = new Scoutnet($domain);
        Scoutnet::$multitons[$domain] = $multiton;
        return $multiton;
    }

    /**
     * Gets the member list of a scout group.
     * @return MemberEntry[] A list of members.
     */
    public function getMemberList(int $groupId, string $key) {
        if (isset($this->loadedMemberLists[$groupId])) {
            return $this->loadedMemberLists[$groupId];
        }

        $memberList = $this->fetchMemberList($groupId, $key);
        $returnList = [];
        foreach ($memberList->data as $member) {
            $newMemberEntry = new MemberEntry();
            foreach ($member as $dataFieldName => $memberField) {
                $classFieldName = str_replace('-', '', $dataFieldName);
                if (isset($memberField->raw_value)) {
                    $newValue = new ValueAndRaw();
                    $newValue->rawValue = $memberField->raw_value;
                    $newValue->value = $memberField->value;
                    $newMemberEntry->{$classFieldName} = $newValue;
                } else {
                    $newValue = new Value();
                    $newValue->value = $memberField->value;
                    $newMemberEntry->{$classFieldName} = $newValue;
                }
            }
            $returnList[] = $newMemberEntry;
        }

        $this->loadedMemberLists[$groupId] = $returnList;
        return $returnList;
    }

    /**
     * Fetches member list from scoutnet.
     * @param int $groupId The scout group scoutnet id.
     * @param string $key The api key.
     * @return array A json parsed member list.
     */
    private function fetchMemberList(int $groupId, string $key) {
        if (file_exists('data.json')) {
            return json_decode(file_get_contents('data.json'));
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://{$groupId}:{$key}@{$this->domain}/api/group/memberlist?format=json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);
        $memberList = curl_exec($curl);

        file_put_contents('data.json',$memberList);

        return json_decode($memberList);
    }
}