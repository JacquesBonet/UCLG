<div id="widgetkit" class="wrap">

	<?php JToolBarHelper::title('Widgetkit', 'widgetkit'); ?>
	
	<div class="dashboard">
		<ul id="tabs">
			<?php $this['event']->trigger('dashboard'); ?>
		</ul>
	</div>                                                

</div>