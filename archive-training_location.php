<?php
get_header();

if ( ! function_exists( 'atlas_location_status_classes' ) ) {
    /**
     * Map location status labels to Tailwind badge classes.
     *
     * @param string $status Status label.
     *
     * @return string
     */
    function atlas_location_status_classes( $status ) {
        $status = strtolower( (string) $status );

        switch ( $status ) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'provisional':
                return 'bg-yellow-100 text-yellow-800';
            case 'inactive':
            case 'archived':
                return 'bg-gray-200 text-gray-700';
            default:
                return 'bg-blue-100 text-blue-800';
        }
    }
}

if ( ! function_exists( 'atlas_location_priority_classes' ) ) {
    /**
     * Map location priority labels to Tailwind badge classes.
     *
     * @param string $priority Priority label.
     *
     * @return string
     */
    function atlas_location_priority_classes( $priority ) {
        $priority = strtolower( (string) $priority );

        switch ( $priority ) {
            case 'high':
                return 'bg-red-100 text-red-800';
            case 'medium':
                return 'bg-yellow-100 text-yellow-800';
            case 'low':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

if ( ! function_exists( 'atlas_location_default_text' ) ) {
    /**
     * Return a formatted string or a placeholder dash when empty.
     *
     * @param string $value Value to display.
     *
     * @return string
     */
    function atlas_location_default_text( $value ) {
        if ( '' === $value || null === $value ) {
            return '-';
        }

        return esc_html( $value );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php esc_html_e( 'Locations Archive | Atlas Core', 'jointswp' ); ?></title>
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
                    <h1 class="text-3xl font-bold text-gray-800"><?php esc_html_e( 'Training Locations', 'jointswp' ); ?></h1>
                    <p class="text-gray-600 mt-2"><?php esc_html_e( 'View and manage all training locations', 'jointswp' ); ?></p>
                </div>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=training_location' ) ); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    <?php esc_html_e( 'Add New Location', 'jointswp' ); ?>
                </a>
            </div>
        </div>

        <!-- Locations Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <i data-feather="map-pin" class="w-4 h-4 mr-2 text-blue-600"></i>
                    <?php esc_html_e( 'All Locations', 'jointswp' ); ?>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Location', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Region', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Country', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Status', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Venues', 'jointswp' ); ?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Last Course', 'jointswp' ); ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ( have_posts() ) : ?>
                            <?php
                            while ( have_posts() ) :
                                the_post();

                                $region           = get_post_meta( get_the_ID(), 'location_region', true );
                                $country          = get_post_meta( get_the_ID(), 'location_country', true );
                                $status           = get_post_meta( get_the_ID(), 'location_status', true );
                                $priority         = get_post_meta( get_the_ID(), 'location_priority', true );
                                $venues_available = get_post_meta( get_the_ID(), 'location_venues_count', true );
                                $last_course      = get_post_meta( get_the_ID(), 'location_last_course_date', true );

                                $status_label   = $status ? $status : __( 'Active', 'jointswp' );
                                $last_course    = $last_course ? $last_course : get_the_date( 'Y-m-d' );
                                $venues_display = $venues_available || '0' === $venues_available ? esc_html( $venues_available ) : '-';
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <div class="flex items-center space-x-2">
                                            <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800"><?php the_title(); ?></a>
                                            <?php if ( $priority ) : ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( atlas_location_priority_classes( $priority ) ); ?>">
                                                    <?php echo esc_html( $priority ); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_location_default_text( $region ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_location_default_text( $country ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( atlas_location_status_classes( $status_label ) ); ?>">
                                            <?php echo esc_html( $status_label ); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $venues_display; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_location_default_text( $last_course ); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-sm text-gray-600 text-center"><?php esc_html_e( 'No locations found.', 'jointswp' ); ?></td>
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
