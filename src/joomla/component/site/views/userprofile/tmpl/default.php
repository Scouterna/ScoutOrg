<?php
defined('_JEXEC') or die('Restricted access');
?>

<h1>Medlemsprofil</h1>

<?php if ($this->member === null) : ?>
    <h3>Invalid member id</h3>
<?php else : ?>
    <table class="scoutorg-profile">
        <?php foreach ($this->fields as $field) : ?>
            <?php if (in_array($field->access, JFactory::getUser()->getAuthorisedViewLevels()) && $field->published == 1) : ?>
                <tr>
                    <td style="font-weight: bold;">
                        <?= $field->title ?>
                    </td>
                    <td style="padding-left: 5px;">
                        <?= ScoutOrgHelper::renderField($field, $this->member) ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<style>
    .scoutorg-profile tr {
        margin-top: 10px;
        margin-bottom: 10px;
    };
</style>