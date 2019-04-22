<?php
defined('_JEXEC') or die('Restricted access');
?>

<h1>Medlemsprofil</h1>

<?php if ($this->member === null) : ?>
    <h3>Invalid member id</h3>
<?php else : ?>
    <table class="scoutorg-profile">
        <tbody>
            <?php foreach ($this->fields as $field) : ?>
                <?php if (in_array($field->access, JFactory::getUser()->getAuthorisedViewLevels()) && $field->published == 1) : ?>
                    <tr>
                        <th>
                            <?= $field->title ?>
                        </th>
                        <td>
                            <?= ScoutOrgHelper::renderField($field, $this->member) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<style>
    .scoutorg-profile > tbody > tr {
        margin: 1em;
        border-bottom: solid gray 1px;
    }
    .scoutorg-profile > tbody > tr > th {
        text-align: left;
    }
    .scoutorg-profile > tbody > tr > td {
        padding-left: 1em;
    }
</style>