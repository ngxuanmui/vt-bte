<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_bte
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'banner.cancel' || document.formvalidator.isValid(document.id('banner-form'))) {
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_bte&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="banner-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo 'URL'; ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('url'); ?>
				<?php echo $this->form->getInput('url'); ?></li>
				
				<?php if ($this->item->id): ?>
				<li><label>Website</label>
					<span style="float: left; line-height: 23px;">
						<?php echo $this->item->website; ?>
					</span>
				</li>
				<li><label>Content before extract</label>
					<span style="float: left; line-height: 23px;">
						<a href="<?php echo JRoute::_('index.php?option=com_bte&view=detail&field=html_content&tmpl=component&id=' . $this->item->id); ?>" class="modal">
							View
						</a>
					</span>
				</li>
				<li><label>Content after extract</label>
					<span style="float: left; line-height: 23px;">
						<a href="<?php echo JRoute::_('index.php?option=com_bte&view=detail&tmpl=component&id=' . $this->item->id); ?>" class="modal">
							View
						</a>
					</span>
				</li>
				<li><label>Time process</label>
					<span style="float: left; line-height: 23px;">
						<?php 
						echo round (($this->item->end_time - $this->item->start_time) / 60, 2);
						?>
					</span>
				</li>
				<?php endif; ?>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

<div class="width-40 fltrt">
	<?php echo JHtml::_('sliders.start', 'banner-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

	<?php echo JHtml::_('sliders.panel', JText::_('Publishing'), 'publishing-details'); ?>
		<fieldset class="panelform">
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('publish') as $field): ?>
				<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>

	<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'), 'metadata'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset('metadata') as $field): ?>
					<li><?php echo $field->label; ?>
						<?php echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>

	<?php echo JHtml::_('sliders.end'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</div>

<div class="clr"></div>
</form>
