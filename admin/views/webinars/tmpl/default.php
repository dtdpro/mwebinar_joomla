<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

?>

<form action="<?php echo JRoute::_('index.php?option=com_mwebinar&view=webinars'); ?>" method="post" name="adminForm" id="adminForm">
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
                <div class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
			<?php } else { ?>

                <table class="adminlist table table-striped" id="MAMSArtList">
                    <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">Published</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th width="1%">ID</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach($this->items as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
                            <td class="center">
                                <div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->published, $i, 'webinars.', true); ?>
                                </div>
                            </td>
                            <td class="has-context">
                                <a href="<?php echo JRoute::_('index.php?option=com_mwebinar&task=webinar.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>"><?php echo $this->escape($item->name); ?></a><br />
                                <a href="#" onclick="return listItemTask('cb<?php echo $i; ?>','pages.listpages')" class="btn btn-micro" title="Pages"><i class="icon-stack"></i> Pages</a>
                            </td>
                            <td><?php echo $item->category_title; ?></td>
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
