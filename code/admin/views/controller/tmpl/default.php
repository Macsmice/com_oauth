<? 
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */
?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip'); ?>
<style src="media://com_default/css/form.css" />
<style src="media://com_default/css/admin.css" />

<form action="<?= @route('&id='.$controller->id)?>" method="post" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="target_type" class="mainlabel"><?= @text('Controller'); ?></label>
			<input id="controller" type="text" name="controller" value="<?= $controller->controller; ?>" style="width:50%" />
			<br />
			<label for="enabled" class="mainlabel"><?= @text('Target'); ?></label>
			<?= @helper('admin::com.stream.helper.select.target', array('name' => 'stream_target_id', 'selected' => $controller->stream_target_id)); ?>
		</fieldset>
		<fieldset>
			<legend><?= @text('Choose actions to enable'); ?></legend>
			<label for="read" class="mainlabel"><?= @text('Read'); ?></label>
			<?= @helper('admin::com.stream.helper.select.enable', array('name' => 'read', 'selected' => $controller->read)); ?>
			
			<br />
			<label for="add" class="mainlabel"><?= @text('Add'); ?></label>
			<?= @helper('admin::com.stream.helper.select.enable', array('name' => 'add', 'selected' => $controller->add)); ?>
			<br />
			<label for="edit" class="mainlabel"><?= @text('Edit'); ?></label>
			<?= @helper('admin::com.stream.helper.select.enable', array('name' => 'edit', 'selected' => $controller->edit)); ?>
			<br />
			<label for="delete" class="mainlabel"><?= @text('Delete'); ?></label>
			<?= @helper('admin::com.stream.helper.select.enable', array('name' => 'delete', 'selected' => $controller->delete)); ?>
			<br />
		</fieldset>
	</div>
</form>