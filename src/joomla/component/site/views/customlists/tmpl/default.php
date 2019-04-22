<?php

defined('_JEXEC') or die('Restricted Access');
?>

<?php if ($this->list === null) : ?>
    <table class="table table-striped">
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
    <h1>
        <?= $this->list->getTitle() ?>
    </h1>
    <p>
        <?= $this->list->getDescription() ?>
    </p>
    <?php if (!empty($this->list->getSubLists())) : ?>
        <h3>Underlistor</h3>
        <table class="sublist">
            <tbody>
                <?php foreach ($this->list->getSubLists() as $subList) : ?>
                    <tr>
                        <td>
                            <a href="<?= JRoute::_("index.php?option=com_scoutorg&view=customlists&id={$this->path}.{$subList->getId()}") ?>">
                                <?= $subList->getTitle() ?>
                            </a>
                        </td>
                        <td>
                            <?= $subList->getDescription() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <h3>Medlemmar</h3>
    <p>
        <?= count($this->list->getMembers()) ?> medlemmar i listan.
    </p>
    <table class="memberlist">
        <tbody>
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
        </tbody>
    </table>
<?php endif ?>

<style>
    .scoutorg-sublist {

    }

    .scoutorg-memberlist {

    }
</style>