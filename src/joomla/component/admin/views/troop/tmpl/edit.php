<?php

defined('_JEXEC') or die('Restricted access');

?>
<form action="<?php echo JRoute::_('index.php?option=com_scoutorg&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form">
	<div class="form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
			<fieldset class="adminform">
				<legend><?php echo JText::_($fieldset->label); ?></legend>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</fieldset>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="troop.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
