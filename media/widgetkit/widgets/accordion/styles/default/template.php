<?php
/**
* @package   Widgetkit
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

	$widget_id = $widget->id.'-'.uniqid();
	$settings  = $widget->settings;
	
?>

<div id="accordion-<?php echo $widget_id;?>" class="wk-accordion wk-accordion-default clearfix" <?php if (is_numeric($settings['width'])) echo 'style="width: '.$settings['width'].'px;"'; ?>>
	<?php foreach ($widget->items as $key => $item) : ?>
		<h3 class="toggler"><?php echo $item['title'];?></h3>
		<div class="content wk-content clearfix"><?php echo $item['content'];?></div>
	<?php endforeach; ?>
</div>

<script type="text/javascript">
	
	jQuery(function($){
		
		$.widgetkit.lazyloaders.accordion($("#accordion-<?php echo $widget_id; ?>"), <?php echo json_encode($settings); ?>);
	});

</script>