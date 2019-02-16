<?php

defined('_JEXEC') or die('Restricted Access');
?>

<?php if ($this->list === null) : ?>
    <table>
        <?php foreach ($this->lists as $list) : ?>
            <tr>
                <td>
                    <a href="<?= JRoute::_("index.php?option=com_scoutorg&view=customlists&id={$list->getId()}") ?>">
                        <?= $list->getTitle() ?>
                    </a>
                </td>
                <td>
                    <?= $list->getDescription() ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <h3>Sub lists</h3>
    <table>
        <?php foreach ($this->list->getSubLists() as $subList) : ?>
            <tr>
                <td>
                    <a href="<?= JRoute::_("index.php?option=com_scoutorg&view=customlists&id={$subList->getId()}") ?>">
                        <?= $subList->getTitle() ?>
                    </a>
                </td>
                <td>
                    <?= $subList->getDescription() ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h3>Members</h3>
    <table>
        <?php foreach ($this->list->getMembers() as $member) : ?>
            <tr>
                <td>
                    <a href="<?= JRoute::_("index.php?option=com_scoutorg&view=userprofile&id={$member->getId()}") ?>">
                        <?php
                            $pInfo = $member->getPersonInfo();
                            echo "{$pInfo->getFirstname()} {$pInfo->getLastname()}";
                        ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif ?>