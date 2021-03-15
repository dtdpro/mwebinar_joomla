<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$app	= JFactory::getApplication();
$user	= JFactory::getUser();
$userId	= $user->get('id');
$saveOrder = ($this->state->get('filter.webinar'));
$published = $this->state->get('filter.published');
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_mwebinar&task=pages.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'MAMSArtList', 'adminForm', null, $saveOrderingUrl);
}
?>
<script type="text/javascript">
    Joomla.orderTable = function()
    {
        table = document.getElementById("sortTable");
        order = table.options[table.selectedIndex].value;
        dirn = 'asc';
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_mwebinar&view=pages'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
		<?php else : ?>
        <div id="j-main-container">
			<?php endif;?>
            <div id="filter-bar" class="btn-toolbar">

            </div>

            <div class="clearfix"> </div>

			<?php if (empty($this->items)) { ?>
				<?php if ($this->webinarSelected) { ?>
                    <div class="alert alert-no-items">
						<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
				<?php } else { ?>
                    <div class="alert alert-no-items">
						<?php echo 'Select a webinar from the left'; ?>
                    </div>
				<?php } ?>
			<?php } else { ?>

                <table class="adminlist table table-striped" id="MAMSArtList">
                    <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 's.ordering', null, null, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">Published</th>
                        <th>Title</th>
                        <th width="15%">Type</th>
                        <th width="1%">ID</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach($this->items as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->webionar_id?>">
                            <td class="order nowrap center hidden-phone">
								<?php
								$disableClassName = '';
								$disabledLabel	  = '';
								if (!$saveOrder) :
									$disabledLabel    = JText::_('JORDERINGDISABLED');
									$disableClassName = 'inactive tip-top';
								endif; ?>
                                <span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>"><i class="icon-menu"></i></span>
                                <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
                            </td>
                            <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
                            <td class="center">
                                <div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->published, $i, 'pages.', true); ?>
                                </div>
                            </td>
                            <td class="has-context">
                                <a href="<?php echo JRoute::_('index.php?option=com_mwebinar&task=page.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>"><?php echo $this->escape($item->title); ?></a>
                            </td>
                            <td><?php echo $item->type; ?></td>
                            <td><?php echo $item->id; ?></td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
			<?php } ?>


            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
        </div>
</form>
