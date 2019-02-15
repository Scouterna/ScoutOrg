<?php
defined('_JEXEC') or die('Restricted access');
?>

<h1>Member profile</h1>

<?php if ($this->member === null) : ?>
    <h3>Invalid member id</h3>
<?php else : ?>
    <table>
        <?php foreach ($this->fields as $field) : ?>
            <?php
            if (!in_array($field->access, JFactory::getUser()->getAuthorisedViewLevels())) {
                continue;
            }
            ?>
            <tr>
                <td><?= $field->title ?></td>
                <td><?= ScoutOrgHelper::renderField($field->fieldtype, $this->member) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>