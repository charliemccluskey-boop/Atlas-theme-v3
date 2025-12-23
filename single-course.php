<?php
get_header();

if ( ! function_exists( 'atlas_course_status_classes' ) ) {
	/**
	 * Map course status labels to Tailwind badge classes.
	 *
	 * @param string $status Status label.
	 *
	 * @return string
	 */
	function atlas_course_status_classes( $status ) {
		$status = strtolower( (string) $status );

		switch ( $status ) {
			case 'confirmed':
				return 'bg-green-100 text-green-800';
			case 'scheduled':
				return 'bg-blue-100 text-blue-800';
			case 'completed':
				return 'bg-purple-100 text-purple-800';
			case 'cancelled':
				return 'bg-red-100 text-red-800';
			default:
				return 'bg-gray-100 text-gray-800';
		}
	}
}

if ( ! function_exists( 'atlas_course_get_post_from_value' ) ) {
	/**
	 * Safely resolve a relationship value to a WP_Post object.
	 *
	 * @param mixed $value Post object, ID, or array from ACF.
	 *
	 * @return WP_Post|null
	 */
	function atlas_course_get_post_from_value( $value ) {
		if ( $value instanceof WP_Post ) {
			return $value;
		}

		if ( is_array( $value ) && ! empty( $value ) ) {
			return atlas_course_get_post_from_value( reset( $value ) );
		}

		if ( is_numeric( $value ) ) {
			$post = get_post( (int) $value );

			return $post instanceof WP_Post ? $post : null;
		}

		return null;
	}
}

if ( ! function_exists( 'atlas_course_format_currency' ) ) {
	/**
	 * Format currency values with a pound sign and comma separators.
	 *
	 * @param string|float|int $value Raw currency value.
	 *
	 * @return string
	 */
	function atlas_course_format_currency( $value ) {
		if ( '' === $value || null === $value ) {
			return '-';
		}

		if ( is_numeric( $value ) ) {
			return '£' . number_format( (float) $value, 0, '.', ',' );
		}

		return esc_html( $value );
	}
}

if ( ! function_exists( 'atlas_course_linked_text' ) ) {
	/**
	 * Display a linked value if a matching post ID exists, otherwise plain text.
	 *
	 * @param string|int|WP_Post|array $value Post reference or text value.
	 *
	 * @return string
	 */
	function atlas_course_linked_text( $value ) {
		if ( ! $value ) {
			return '-';
		}

		$post = atlas_course_get_post_from_value( $value );

		if ( $post instanceof WP_Post ) {
			return sprintf(
				'<a href="%1$s" class="text-blue-600 hover:text-blue-800">%2$s</a>',
				esc_url( get_permalink( $post ) ),
				esc_html( get_the_title( $post ) )
			);
		}

		$label = is_string( $value ) ? $value : '';
		$url   = '';

		if ( $url ) {
			return sprintf(
				'<a href="%1$s" class="text-blue-600 hover:text-blue-800">%2$s</a>',
				esc_url( $url ),
				esc_html( $label )
			);
		}

		return esc_html( $label );
	}
}
?>

<?php if ( have_posts() ) : ?>
	<?php
	while ( have_posts() ) :
		the_post();

		$course_core        = function_exists( 'get_field' ) ? get_field( 'course_core' ) : array();
		$course_venue_group = function_exists( 'get_field' ) ? get_field( 'course_venue' ) : array();
		$course_financials  = function_exists( 'get_field' ) ? get_field( 'course_financials' ) : array();
		$course_notes       = function_exists( 'get_field' ) ? get_field( 'course_internal_notes' ) : '';
		$attendees_count    = function_exists( 'get_field' ) ? get_field( 'course_attendees' ) : '';
		$attendee_entries   = get_post_meta( get_the_ID(), 'course_attendees_list', true );

		$course_title        = isset( $course_core['course_title'] ) && $course_core['course_title'] ? $course_core['course_title'] : get_the_title();
		$course_date         = isset( $course_core['course_date'] ) ? $course_core['course_date'] : get_post_meta( get_the_ID(), 'course_date', true );
		$course_type         = isset( $course_core['course_type'] ) ? $course_core['course_type'] : get_post_meta( get_the_ID(), 'course_type', true );
		$course_duration     = isset( $course_core['course_duration'] ) ? $course_core['course_duration'] : get_post_meta( get_the_ID(), 'course_duration', true );
		$course_capacity     = isset( $course_core['course_capacity'] ) ? $course_core['course_capacity'] : get_post_meta( get_the_ID(), 'course_capacity', true );
		$course_status       = isset( $course_core['course_status'] ) ? $course_core['course_status'] : get_post_meta( get_the_ID(), 'course_status', true );
		$course_venue_value  = isset( $course_venue_group['course_venue'] ) ? $course_venue_group['course_venue'] : get_post_meta( get_the_ID(), 'course_venue', true );
		$total_cost          = isset( $course_financials['total_course_cost'] ) ? $course_financials['total_course_cost'] : get_post_meta( get_the_ID(), 'course_expenses', true );
		$revenue             = isset( $course_financials['course_revenue'] ) ? $course_financials['course_revenue'] : get_post_meta( get_the_ID(), 'course_revenue', true );
		$profit              = get_post_meta( get_the_ID(), 'course_profit', true );
		$profit_margin       = get_post_meta( get_the_ID(), 'course_profit_margin', true );
		$course_venue        = atlas_course_get_post_from_value( $course_venue_value );
		$course_location     = $course_venue ? atlas_course_get_post_from_value( function_exists( 'get_field' ) ? get_field( 'linked_location', $course_venue->ID ) : get_post_meta( $course_venue->ID, 'linked_location', true ) ) : null;
		$course_date_display = $course_date ? $course_date : get_the_date( 'Y-m-d' );
		$course_status_label = $course_status ? ucfirst( $course_status ) : __( 'Scheduled', 'jointswp' );
		$profit_amount       = $profit;
		$attendees_total     = is_numeric( $attendees_count ) ? (int) $attendees_count : 0;
		$attendees_total_text = $attendees_total ? (string) $attendees_total : '0';

		if ( $course_capacity ) {
			$attendees_total_text = sprintf(
				'%1$s/%2$s',
				$attendees_total ? $attendees_total : '0',
				$course_capacity
			);
		}

		if ( '' === $profit_amount && is_numeric( $revenue ) && is_numeric( $total_cost ) ) {
			$profit_amount = (float) $revenue - (float) $total_cost;
		}

		if ( '' === $profit_margin && is_numeric( $revenue ) && $revenue > 0 && is_numeric( $profit_amount ) ) {
			$profit_margin = round( ( (float) $profit_amount / (float) $revenue ) * 100, 1 ) . '%';
		}

		$attendees = array();

		if ( is_array( $attendee_entries ) ) {
			$attendees = $attendee_entries;
		} elseif ( is_string( $attendee_entries ) && '' !== trim( $attendee_entries ) ) {
			// Allow comma-separated names as a simple fallback.
			$names = array_map( 'trim', explode( ',', $attendee_entries ) );

			foreach ( $names as $name ) {
				if ( '' === $name ) {
					continue;
				}

				$attendees[] = array(
					'name'    => $name,
					'company' => '',
					'status'  => '',
				);
			}
		}
		?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $course_title . ' | Atlas Core' ); ?></title>
	<link rel="stylesheet" href="style.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
	<div class="max-w-6xl mx-auto p-6">
		<!-- Course Header -->
		<div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
			<div class="flex justify-between items-start">
				<div>
					<h1 class="text-3xl font-bold text-gray-800"><?php echo esc_html( $course_title ); ?></h1>
					<div class="flex items-center mt-2 space-x-4">
						<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
							<i data-feather="calendar" class="w-3 h-3 mr-1"></i>
							<?php esc_html_e( 'Training Course', 'jointswp' ); ?>
						</span>
						<span class="text-gray-600"><?php printf( esc_html__( 'ID %s', 'jointswp' ), esc_html( get_the_ID() ) ); ?></span>
						<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr( atlas_course_status_classes( $course_status_label ) ); ?>">
							<i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
							<?php echo esc_html( $course_status_label ); ?>
						</span>
					</div>
				</div>
				<?php $edit_link = get_edit_post_link(); ?>
				<?php if ( $edit_link ) : ?>
					<a href="<?php echo esc_url( $edit_link ); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
						<i data-feather="edit" class="w-4 h-4 mr-2"></i>
						<?php esc_html_e( 'Edit in wp-admin', 'jointswp' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Main Content Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<!-- Core Details Section -->
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="info" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php esc_html_e( 'Course Details', 'jointswp' ); ?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Course Date', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo esc_html( $course_date_display ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Course Type', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo $course_type ? esc_html( $course_type ) : '-'; ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Duration', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo $course_duration ? esc_html( $course_duration ) : '-'; ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Capacity', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo $course_capacity ? esc_html( $course_capacity ) : '-'; ?></dd>
						</div>
					</dl>
				</div>
			</div>

			<!-- Venue Section -->
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php esc_html_e( 'Venue Information', 'jointswp' ); ?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Venue', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900">
								<?php echo wp_kses_post( atlas_course_linked_text( $course_venue ) ); ?>
							</dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Location', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900">
								<?php echo wp_kses_post( atlas_course_linked_text( $course_location ) ); ?>
							</dd>
						</div>
					</dl>
				</div>
			</div>

			<!-- Financial Section -->
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="credit-card" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php esc_html_e( 'Financials', 'jointswp' ); ?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<dl class="grid grid-cols-2 gap-x-4 gap-y-3">
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Total Cost', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_course_format_currency( $total_cost ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Revenue', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_course_format_currency( $revenue ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Profit', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm font-medium text-green-600"><?php echo atlas_course_format_currency( $profit_amount ); ?></dd>
						</div>
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Profit Margin', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm font-medium text-green-600"><?php echo $profit_margin ? esc_html( $profit_margin ) : '-'; ?></dd>
						</div>
					</dl>
				</div>
			</div>

			<!-- Attendees Section -->
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="users" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php
						printf(
							/* translators: %s: attendees total text. */
							esc_html__( 'Attendees (%s)', 'jointswp' ),
							esc_html( $attendees_total_text )
						);
						?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<?php if ( $attendees ) : ?>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<?php foreach ( $attendees as $attendee ) : ?>
								<?php
								$name    = isset( $attendee['name'] ) ? $attendee['name'] : '';
								$company = isset( $attendee['company'] ) ? $attendee['company'] : '';
								$status  = isset( $attendee['status'] ) ? $attendee['status'] : '';
								?>
								<div class="border rounded-lg p-4 hover:bg-gray-50">
									<h3 class="font-medium text-gray-800"><?php echo $name ? esc_html( $name ) : esc_html__( 'Attendee', 'jointswp' ); ?></h3>
									<?php if ( $company ) : ?>
										<p class="text-sm text-gray-600 mt-1"><?php echo esc_html( $company ); ?></p>
									<?php endif; ?>
									<?php if ( $status ) : ?>
										<div class="mt-2">
											<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
												<?php echo esc_html( $status ); ?>
											</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<p class="text-sm text-gray-600"><?php esc_html_e( 'No attendees have been added yet.', 'jointswp' ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<!-- Internal Notes Section -->
			<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
				<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
					<h2 class="text-lg font-medium text-gray-800 flex items-center">
						<i data-feather="file-text" class="w-4 h-4 mr-2 text-blue-600"></i>
						<?php esc_html_e( 'Internal Notes', 'jointswp' ); ?>
					</h2>
				</div>
				<div class="px-6 py-4">
					<?php
					$notes_content = '';

					if ( $course_notes ) {
						$notes_content = wp_kses_post( wpautop( $course_notes ) );
					} elseif ( has_excerpt() ) {
						$notes_content = esc_html( get_the_excerpt() );
					}

					if ( $notes_content ) :
						?>
						<div class="text-sm text-gray-700 italic"><?php echo $notes_content; ?></div>
					<?php else : ?>
						<p class="text-sm text-gray-700 italic"><?php esc_html_e( 'Add any important course notes here.', 'jointswp' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<script>
		feather.replace();
	</script>
</body>
</html>
	<?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
