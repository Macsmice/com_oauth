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
<? foreach (@$controllers as $controller) : ?>
<tr class="<?php echo 'row'.$m; ?>">
	<td align="center">
		<?= $i + 1; ?>
	</td>
	<td align="center">
		<?= @helper('grid.checkbox', array('row'=>$controller))?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit controller') ?>::<?= @escape($controller->message); ?>">
		<? if($controller->locked) : ?>
			<span>
				<?= @escape($controller->controller); ?>
			</span>
		<? else : ?>
			<a href="<?= @route('view=controller&id='.$controller->id); ?>">
				<?= @escape($controller->controller); ?>
			</a>
		<? endif; ?>
		</span>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit controller') ?>::<?= @escape($controller->message); ?>">
			<?= KFactory::tmp('site::com.stream.model.targets')->id($controller->stream_target_id)->getItem()->title;?>
		</span>
	</td>
</tr>
<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>