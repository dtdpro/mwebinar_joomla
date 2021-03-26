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

<form action="<?php echo JRoute::_('index.php?option=com_mwebinar&view=sessions'); ?>" method="post" name="adminForm" id="adminForm">
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
	        <?php if ($this->webinarSelected) { ?>
            <table class="adminlist table table-striped" id="MAMSArtList">
                <thead>
                <tr>
                    <th>Date</th>
	                <?php
                    foreach($this->results['pages'] as $p) {
                        if ($p->type=='field') {
                            foreach ($p->content->fields as $f) {
	                            echo '<th>'.$f->title.'</th>';
                            }
                        }
	                    if ($p->type=='question' || $p->type=='rating') {
		                    echo '<th>'.$p->content->question.'</th>';
	                    }
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
		        <?php foreach($this->results['results'] as $i => $item): ?>
                    <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->webinar_id?>">
                        <td><?php echo $item[0]; ?></td>
	                    <?php
	                    foreach($this->results['pages'] as $p) {
		                    if ($p->type=='field') {
			                    foreach ($p->content->fields as $f) {
				                    if (isset($item[$p->id])) echo '<td>'.$item[$p->id][$f->name].'</td>';
				                    else echo '<td></td>';
			                    }
		                    }
		                    if ($p->type=='question' || $p->type=='rating') {
			                    if (isset($item[$p->id]))  echo '<td>'.$item[$p->id].'</td>';
			                    else echo '<td></td>';
		                    }
	                    }
	                    ?>
                    </tr>
		        <?php endforeach; ?>
                </tbody>
            </table>
	        <?php } else { ?>
                <div class="alert alert-no-items">
			        <?php echo 'Select a webinar from the left'; ?>
                </div>
	        <?php } ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
        </div>
</form>