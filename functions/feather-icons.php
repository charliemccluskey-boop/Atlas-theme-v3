<?php

/**
 * Initialize Feather icons on every page.
 */
function joints_init_feather_icons() {
	?>
	<script type="text/javascript">
		if (window.feather && typeof window.feather.replace === 'function') {
			window.feather.replace();
		}
	</script>
	<?php
}

add_action('wp_footer', 'joints_init_feather_icons');
