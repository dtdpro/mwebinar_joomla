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

<form action="<?php echo JRoute::_('index.php?option=com_mwebinar&view=results'); ?>" method="post" name="adminForm" id="adminForm">
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
                <?php foreach ($this->results as $k=>$r) { ?>
                    <h3><?php echo $r['question']; ?></h3>
                    <div id="result_chart_<?php echo $k; ?>" style="width: 100%;height:300px;"></div>
                    <hr>
	            <?php } ?>
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


<script>
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
	    <?php foreach ($this->results as $k=>$r) { ?>
        var data_<?php echo $k; ?> = new google.visualization.arrayToDataTable([
            ['', 'Count'],
	        <?php foreach ($r['answers'] as $a) {
                echo "['".$a->title."',".$a->count."],";
	         } ?>
        ]);

        var options_<?php echo $k; ?> = {
            legend: { position: 'none' },
            bars: 'horizontal',
            hAxis: { viewWindow: {min:0,max:<?php echo $r['total']; ?>}},
        };

        var chart_<?php echo $k; ?> = new google.charts.Bar(document.getElementById('result_chart_<?php echo $k; ?>'));
        chart_<?php echo $k; ?>.draw(data_<?php echo $k; ?>, options_<?php echo $k; ?>);

	    <?php } ?>

    };

</script>