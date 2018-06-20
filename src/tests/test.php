<?php

$scoutOrg = new \Org\Controllers\ScoutGroupController('s',0);

$scoutGroup = $scoutOrg->loadScoutGroup();
?>
<ul>
    <?php foreach ($scoutGroup->getTroops() as $troop) : ?>
        <li>
            <?php
            echo $troop->getName();
            foreach ($troop->getMembers() as $member) {
                if ($member->getTroopRole() == 'Avdelningsledare') {
                    $pInfo = $member->getPersonInfo();
                    echo " (Avdelningsledare: {$pInfo->getFirstname()} {$pInfo->getLastname()})";
                }
            }
            ?>
        </li>
    <?php endforeach; ?>
</ul>