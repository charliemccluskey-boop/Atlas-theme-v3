<?php
get_header();

if ( ! function_exists( 'atlas_venue_status_classes' ) ) {
	/**
	 * Map venue status labels to Tailwind badge classes.
	 *
	 * @param string $status Status label.
	 *
	 * @return string
	 */
	function atlas_venue_status_classes( $status ) {
		$status = strtolower( (string) $status );

		switch ( $status ) {
			case 'confirmed':
				return 'bg-green-100 text-green-800';
			case 'provisional':
				return 'bg-yellow-100 text-yellow-800';
			case 'inactive':
				return 'bg-gray-200 text-gray-700';
			default:
				return 'bg-blue-100 text-blue-800';
		}
	}
}

if ( ! function_exists( 'atlas_venue_linked_text' ) ) {
	/**
	 * Display a linked value if a matching post ID exists, otherwise plain text.
	 *
	 * @param string|int $value Value to render.
	 *
	 * @return string
	 */
	function atlas_venue_linked_text( $value ) {
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

if ( ! function_exists( 'atlas_venue_boolean_display' ) ) {
	/**
	 * Return a boolean label with an icon.
	 *
	 * @param mixed $value Value to evaluate as boolean.
	 *
	 * @return string
	 */
	function atlas_venue_boolean_display( $value ) {
		if ( '' === $value || null === $value ) {
			return '-';
		}

		$value  = strtolower( trim( (string) $value ) );
		$is_yes = in_array( $value, array( '1', 'yes', 'true', 'on' ), true );

		if ( $is_yes ) {
			return '<span class="text-green-600"><i data-feather="check" class="w-4 h-4 inline mr-1"></i>' . esc_html__( 'Yes', 'jointswp' ) . '</span>';
		}

		return '<span class="text-red-600"><i data-feather="x" class="w-4 h-4 inline mr-1"></i>' . esc_html__( 'No', 'jointswp' ) . '</span>';
	}
}

if ( ! function_exists( 'atlas_venue_default_text' ) ) {
	/**
	 * Return sanitized text or a fallback dash.
	 *
	 * @param string $value Value to format.
	 *
	 * @return string
	 */
	function atlas_venue_default_text( $value ) {
		return $value ? esc_html( $value ) : '-';
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php esc_html_e( 'Venues Archive | Atlas Core', 'jointswp' ); ?></title>
	<link rel="stylesheet" href="style.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
	<div class="max-w-6xl mx-auto p-6">
		<!-- Header -->
		<div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
			<div class="flex justify-between items-center">
				<div>
					<h1 class="text-3xl font-bold text-gray-800"><?php esc_html_e( 'Training Venues', 'jointswp' ); ?></h1>
					<p class="text-gray-600 mt-2"><?php esc_html_e( 'View and manage all training venues', 'jointswp' ); ?></p>
				</div>
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=training_venue' ) ); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
					<i data-feather="plus" class="w-4 h-4 mr-2"></i>
					<?php esc_html_e( 'Add New Venue', 'jointswp' ); ?>
				</a>
			</div>
		</div>

		<!-- Venues Table -->
		<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
				<h2 class="text-lg font-medium text-gray-800 flex items-center">
					<i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
					<?php esc_html_e( 'All Venues', 'jointswp' ); ?>
				</h2>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Venue', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Location', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Company', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Status', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Parking', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Last Used', 'jointswp' ); ?></th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						<?php if ( have_posts() ) : ?>
							<?php
							while ( have_posts() ) :
								the_post();

								$venue_location  = get_post_meta( get_the_ID(), 'venue_location', true );
								$venue_company   = get_post_meta( get_the_ID(), 'venue_company', true );
								$venue_status    = get_post_meta( get_the_ID(), 'venue_status', true );
								$venue_parking   = get_post_meta( get_the_ID(), 'venue_parking', true );
								$venue_last_used = get_post_meta( get_the_ID(), 'venue_last_used', true );

								$status_label    = $venue_status ? $venue_status : __( 'Confirmed', 'jointswp' );
								$last_used_label = $venue_last_used ? $venue_last_used : __( '-', 'jointswp' );
								?>
								<tr class="hover:bg-gray-50">
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
										<a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800"><?php the_title(); ?></a>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_venue_linked_text( $venue_location ); ?></td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_venue_linked_text( $venue_company ); ?></td>
									<td class="px-6 py-4 whitespace-nowrap">
										<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( atlas_venue_status_classes( $status_label ) ); ?>">
											<?php echo esc_html( $status_label ); ?>
										</span>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo wp_kses_post( atlas_venue_boolean_display( $venue_parking ) ); ?></td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_venue_default_text( $last_used_label ); ?></td>
								</tr>
							<?php endwhile; ?>
						<?php else : ?>
							<tr>
								<td colspan="6" class="px-6 py-4 text-sm text-gray-600 text-center"><?php esc_html_e( 'No venues found.', 'jointswp' ); ?></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		feather.replace();
	</script>
</body>
</html>

<?php
get_footer();
