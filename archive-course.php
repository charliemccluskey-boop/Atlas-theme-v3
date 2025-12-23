<?php
get_header();

if ( ! function_exists( 'atlas_course_status_classes' ) ) {
    /**
     * Map course status labels to Tailwind badge classes.
     *
     * @param string $status
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
     * @param string|float|int $value
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
     * @param string|int|WP_Post|array $value
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses | Atlas Core</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Courses Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800"><?php esc_html_e( 'Courses', 'jointswp' ); ?></h1>
                    <p class="text-gray-600 mt-2"><?php esc_html_e( 'View and manage all training courses', 'jointswp' ); ?></p>
                </div>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=course' ) ); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    <?php esc_html_e( 'Add New Course', 'jointswp' ); ?>
                </a>
            </div>
        </div>

        <!-- Courses Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <i data-feather="calendar" class="w-4 h-4 mr-2 text-blue-600"></i>
                    <?php esc_html_e( 'Upcoming Courses', 'jointswp' ); ?>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Course Date', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Status', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Venue', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Location', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Attendees', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Total Cost', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Revenue', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Profit', 'jointswp' ); ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ( have_posts() ) : ?>
                            <?php
                            while ( have_posts() ) :
                                the_post();

                                $course_core        = function_exists( 'get_field' ) ? get_field( 'course_core' ) : array();
                                $course_venue_group = function_exists( 'get_field' ) ? get_field( 'course_venue' ) : array();
                                $course_financials  = function_exists( 'get_field' ) ? get_field( 'course_financials' ) : array();

                                $course_date_value = isset( $course_core['course_date'] ) ? $course_core['course_date'] : get_post_meta( get_the_ID(), 'course_date', true );
                                $course_status     = isset( $course_core['course_status'] ) ? $course_core['course_status'] : get_post_meta( get_the_ID(), 'course_status', true );
                                $course_venue_val  = isset( $course_venue_group['course_venue'] ) ? $course_venue_group['course_venue'] : get_post_meta( get_the_ID(), 'course_venue', true );
                                $attendees         = function_exists( 'get_field' ) ? get_field( 'course_attendees' ) : get_post_meta( get_the_ID(), 'course_attendees', true );
                                $expenses          = isset( $course_financials['total_course_cost'] ) ? $course_financials['total_course_cost'] : get_post_meta( get_the_ID(), 'course_expenses', true );
                                $revenue           = isset( $course_financials['course_revenue'] ) ? $course_financials['course_revenue'] : get_post_meta( get_the_ID(), 'course_revenue', true );
                                $profit            = get_post_meta( get_the_ID(), 'course_profit', true );

                                $course_date    = $course_date_value ? $course_date_value : get_the_date( 'Y-m-d' );
                                $status_label   = $course_status ? ucfirst( $course_status ) : __( 'Scheduled', 'jointswp' );
                                $profit_amount  = $profit;
                                $course_venue   = atlas_course_get_post_from_value( $course_venue_val );
                                $course_location = $course_venue ? atlas_course_get_post_from_value( function_exists( 'get_field' ) ? get_field( 'linked_location', $course_venue->ID ) : get_post_meta( $course_venue->ID, 'linked_location', true ) ) : null;

                                if ( '' === $profit_amount && is_numeric( $revenue ) && is_numeric( $expenses ) ) {
                                    $profit_amount = (float) $revenue - (float) $expenses;
                                }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800"><?php echo esc_html( $course_date ); ?></a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( atlas_course_status_classes( $status_label ) ); ?>">
                                            <?php echo esc_html( $status_label ); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_course_linked_text( $course_venue ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_course_linked_text( $course_location ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo '' !== $attendees && null !== $attendees ? esc_html( $attendees ) : '-'; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_course_format_currency( $expenses ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_course_format_currency( $revenue ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600"><?php echo atlas_course_format_currency( $profit_amount ); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-sm text-gray-600 text-center"><?php esc_html_e( 'No courses found.', 'jointswp' ); ?></td>
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
