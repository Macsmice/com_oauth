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

<? $i = 0; $m = 0; ?>
<? foreach (@$sites as $site) : ?>
<tr class="<?php echo 'row'.$m; ?>">
	<td align="center">
		<?= $i + 1; ?>
	</td>
	<td align="center">
		<?= @helper('grid.checkbox', array('row'=>$site))?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit site') ?>::<?= @escape($site->message); ?>">
		<? if($site->locked) : ?>
			<span>
				<?= @escape($site->title); ?>
			</span>
		<? else : ?>
			<a href="<?= @route('view=site&id='.$site->id); ?>">
				<?= @escape($site->title); ?>
			</a>
		<? endif; ?>
		</span>
	</td>
</tr>
<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>