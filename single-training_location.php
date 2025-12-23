<?php
get_header();

if ( ! function_exists( 'atlas_location_status_badge' ) ) {
	/**
	 * Map location status values to labels and Tailwind classes.
	 *
	 * @param string $status Status value.
	 *
	 * @return array{label:string,class:string}
	 */
	function atlas_location_status_badge( $status ) {
		$default = array(
			'label' => __( 'Planned', 'jointswp' ),
			'class' => 'bg-blue-100 text-blue-800',
		);

		$status = strtolower( (string) $status );

		$map = array(
			'planned'      => array(
				'label' => __( 'Planned', 'jointswp' ),
				'class' => 'bg-blue-100 text-blue-800',
			),
			'shortlisted'  => array(
				'label' => __( 'Venue Shortlisted', 'jointswp' ),
				'class' => 'bg-yellow-100 text-yellow-800',
			),
			'confirmed'    => array(
				'label' => __( 'Venue Confirmed', 'jointswp' ),
				'class' => 'bg-green-100 text-green-800',
			),
			'delivered'    => array(
				'label' => __( 'Delivered', 'jointswp' ),
				'class' => 'bg-gray-200 text-gray-800',
			),
			'on_hold'      => array(
				'label' => __( 'On Hold', 'jointswp' ),
				'class' => 'bg-red-100 text-red-800',
			),
		);

		return isset( $map[ $status ] ) ? $map[ $status ] : $default;
	}
}

if ( ! function_exists( 'atlas_location_priority_badge' ) ) {
	/**
	 * Map location priority values to labels and Tailwind classes.
	 *
	 * @param string $priority Priority value.
	 *
	 * @return array{label:string,class:string}
	 */
	function atlas_location_priority_badge( $priority ) {
		$default = array(
			'label' => __( 'Medium', 'jointswp' ),
			'class' => 'bg-yellow-100 text-yellow-800',
		);

		$priority = strtolower( (string) $priority );

		$map = array(
			'high'   => array(
				'label' => __( 'High', 'jointswp' ),
				'class' => 'bg-red-100 text-red-800',
			),
			'medium' => array(
				'label' => __( 'Medium', 'jointswp' ),
				'class' => 'bg-yellow-100 text-yellow-800',
			),
			'low'    => array(
				'label' => __( 'Low', 'jointswp' ),
				'class' => 'bg-green-100 text-green-800',
			),
		);

		return isset( $map[ $priority ] ) ? $map[ $priority ] : $default;
	}
}

if ( ! function_exists( 'atlas_location_display_text' ) ) {
	/**
	 * Return sanitized text or a fallback dash.
	 *
	 * @param string $value Value to format.
	 *
	 * @return string
	 */
	function atlas_location_display_text( $value ) {
		return $value ? esc_html( $value ) : '—';
	}
}

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$location_code   = get_field( 'location_code' );
		$city_name       = get_field( 'city_name' );
		$region          = get_field( 'region' );
		$country         = get_field( 'country' );
		$location_status = get_field( 'location_status' );
		$priority        = get_field( 'priority' );

		$status_badge   = atlas_location_status_badge( $location_status );
		$priority_badge = atlas_location_priority_badge( $priority );
		$edit_link      = get_edit_post_link();

		$display_city  = $city_name ? $city_name : get_the_title();
		$page_title    = sprintf(
			/* translators: %s: location name */
			__( 'Training Location: %s', 'jointswp' ),
			$display_city
		);
		?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $page_title ); ?></title>
	<link rel="stylesheet" href="style.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
	<div class="max-w-6xl mx-auto p-6">
		<div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
			<div class="flex justify-between items-start">
				<div>
					<h1 class="text-3xl font-bold text-gray-800"><?php echo esc_html( $display_city ); ?></h1>
					<div class="flex items-center mt-2 space-x-4">
						<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
							<i data-feather="map-pin" class="w-3 h-3 mr-1"></i>
							<?php esc_html_e( 'Training Location', 'jointswp' ); ?>
						</span>
						<span class="text-gray-600">
							<?php
							printf(
								/* translators: %d: post ID */
								esc_html__( 'ID %d', 'jointswp' ),
								absint( get_the_ID() )
							);
							?>
						</span>
						<?php if ( $priority_badge ) : ?>
							<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr( $priority_badge['class'] ); ?>">
								<i data-feather="alert-triangle" class="w-3 h-3 mr-1"></i>
								<?php echo esc_html( $priority_badge['label'] ); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $edit_link ) : ?>
					<a href="<?php echo esc_url( $edit_link ); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
						<i data-feather="edit" class="w-4 h-4 mr-2"></i>
						<?php esc_html_e( 'Edit in wp-admin', 'jointswp' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="info" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php esc_html_e( 'Location Details', 'jointswp' ); ?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Location Code', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_location_display_text( $location_code ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'City Name', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_location_display_text( $city_name ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Region', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_location_display_text( $region ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Country', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_location_display_text( $country ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Location Status', 'jointswp' ); ?></dt>
							<dd class="mt-1">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo esc_attr( $status_badge['class'] ); ?>">
									<i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
									<?php echo esc_html( $status_badge['label'] ); ?>
								</span>
							</dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Priority', 'jointswp' ); ?></dt>
							<dd class="mt-1">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo esc_attr( $priority_badge['class'] ); ?>">
									<i data-feather="activity" class="w-3 h-3 mr-1"></i>
									<?php echo esc_html( $priority_badge['label'] ); ?>
								</span>
							</dd>
						</div>
					</dl>
				</div>
			</div>
		</div>
	</div>
	<script>
		feather.replace();
	</script>
</body>
</html>
		<?php
	endwhile;
endif;

get_footer();
