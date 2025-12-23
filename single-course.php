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
			default:
				return 'bg-gray-100 text-gray-800';
		}
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
	 * @param string|int $value Post ID or text value.
	 *
	 * @return string
	 */
	function atlas_course_linked_text( $value ) {
		if ( ! $value ) {
			return '-';
		}

		$label = is_numeric( $value ) ? get_the_title( (int) $value ) : (string) $value;
		$url   = is_numeric( $value ) ? get_permalink( (int) $value ) : '';

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

		$course_date          = get_post_meta( get_the_ID(), 'course_date', true );
		$course_type          = get_post_meta( get_the_ID(), 'course_type', true );
		$course_duration      = get_post_meta( get_the_ID(), 'course_duration', true );
		$course_capacity      = get_post_meta( get_the_ID(), 'course_capacity', true );
		$course_status        = get_post_meta( get_the_ID(), 'course_status', true );
		$course_venue         = get_post_meta( get_the_ID(), 'course_venue', true );
		$course_location      = get_post_meta( get_the_ID(), 'course_location', true );
		$venue_provider       = get_post_meta( get_the_ID(), 'course_venue_provider', true );
		$expenses             = get_post_meta( get_the_ID(), 'course_expenses', true );
		$revenue              = get_post_meta( get_the_ID(), 'course_revenue', true );
		$profit               = get_post_meta( get_the_ID(), 'course_profit', true );
		$profit_margin        = get_post_meta( get_the_ID(), 'course_profit_margin', true );
		$attendees_count      = get_post_meta( get_the_ID(), 'course_attendees', true );
		$attendee_entries     = get_post_meta( get_the_ID(), 'course_attendees_list', true );
		$course_date_display  = $course_date ? $course_date : get_the_date( 'Y-m-d' );
		$course_status_label  = $course_status ? $course_status : __( 'Scheduled', 'jointswp' );
		$profit_amount        = $profit;
		$attendees_total_text = $attendees_count ? $attendees_count : '0';

		if ( $course_capacity ) {
			$attendees_total_text = sprintf(
				'%1$s/%2$s',
				$attendees_count ? $attendees_count : '0',
				$course_capacity
			);
		}

		if ( '' === $profit_amount && is_numeric( $revenue ) && is_numeric( $expenses ) ) {
			$profit_amount = (float) $revenue - (float) $expenses;
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
	<title><?php echo esc_html( get_the_title() . ' | Atlas Core' ); ?></title>
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
					<h1 class="text-3xl font-bold text-gray-800"><?php the_title(); ?></h1>
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
						<div class="sm:col-span-1">
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Venue Provider', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900">
								<?php echo wp_kses_post( atlas_course_linked_text( $venue_provider ) ); ?>
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
							<dt class="text-sm font-medium text-gray-500"><?php esc_html_e( 'Expenses', 'jointswp' ); ?></dt>
							<dd class="mt-1 text-sm text-gray-900"><?php echo atlas_course_format_currency( $expenses ); ?></dd>
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
					<?php if ( has_excerpt() ) : ?>
						<p class="text-sm text-gray-700 italic"><?php echo esc_html( get_the_excerpt() ); ?></p>
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
