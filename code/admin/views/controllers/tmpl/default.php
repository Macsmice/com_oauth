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

<style src="media://com_default/css/grid.css" />
<style src="media://com_default/css/admin.css" />

<table class="adminlist" style="clear: both;">
	<thead>
		<form action="<?= @route()?>" method="get"">
		<input type="hidden" name="option" value="com_stream" />
		<input type="hidden" name="view" value="actions" />
		<tr>
			<th width="5">
				<?= @text('NUM'); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($controllers); ?>);" />
			</th>
			<th>
				<?= @helper('grid.sort', array('column' => 'Controller')); ?>
			</th>
			<th>
				<?= @helper('grid.sort', array('column' => 'Target')); ?>
			</th>
		</tr>
		<tr>
			<td colspan="2">
				<?= @text('Filters'); ?>	
			</td>
			<td>
				<?= @template('admin::com.default.view.list.search_form'); ?>
			</td>
			<td>
				<?= @helper('admin::com.profiles.helper.listbox.enabled',  array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
			</td>
		</tr>
	</thead>
	<tbody>
		<? if (count(@$controllers)) : ?>
			<?= @template('default_items'); ?>
		<? else : ?>
			<tr>
				<td colspan="8" align="center">
					<?= @text('No items found'); ?>
				</td>
			</tr>
		<? endif; ?>
	</tbody>
	<tfoot>
			<tr>
				<td colspan="20">
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
	</tfoot>
</table>
