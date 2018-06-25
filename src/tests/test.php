<?php

$scoutOrg = new \Org\Controllers\ScoutGroupController('s',0);

$scoutGroup = $scoutOrg->loadScoutGroup();
?>
<ul>
    <?php foreach ($scoutGroup->getTroops() as $troop) : ?>
        <li>
            <?php
            echo $troop->getName();
            if (($troopLeader = $troop->getTroopLeader()) !== null) {
                $pInfo = $troopLeader->getPersonInfo();
                echo " (Avdelningsledare: {$pInfo->getFirstname()} {$pInfo->getLastname()})";
            }
            ?>
        </li>
    <?php endforeach; ?>
</ul>