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

<style src="media://com_default/css/form.css" />
<style src="media://com_default/css/admin.css" />

<?= @helper('behavior.tooltip'); ?>

<script>
	function checksubmit(form) {
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.title.value == ""){
			alert( "<?= @text('Site must have a title', true); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}
</script>

<form action="<?= @route('&id='.$site->id)?>" method="post" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="site_type" class="mainlabel"><?= @text('Title'); ?></label>
			<input id="title" type="text" name="title" value="<?= $site->title; ?>" style="width:50%" />
<br />
			<label for="site_type" class="mainlabel"><?= @text('consumer_key'); ?></label>
			<input id="consumer_key" type="text" name="consumer_key" value="<?= $site->consumer_key; ?>" style="width:50%" />
<br />
			<label for="site_type" class="mainlabel"><?= @text('consumer_secret'); ?></label>
			<input id="consumer_secret" type="text" name="consumer_secret" value="<?= $site->consumer_secret; ?>" style="width:50%" />
<br />
			<label for="site_type" class="mainlabel"><?= @text('redirect_url'); ?></label>
			<input id="redirect_url" type="text" name="redirect_url" value="<?= $site->redirect_url; ?>" style="width:50%" />
		</fieldset>
	</div>
</form>
