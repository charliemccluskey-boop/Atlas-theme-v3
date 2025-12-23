<?php
get_header();
?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <?php
        $company_type_field = get_field_object( 'company_type' );
        $company_type_value = get_field( 'company_type' );
        $company_type_label = ( $company_type_value && isset( $company_type_field['choices'][ $company_type_value ] ) )
            ? $company_type_field['choices'][ $company_type_value ]
            : __( 'Not specified', 'atlas-theme' );

        $company_email   = get_field( 'company_email' );
        $company_phone   = get_field( 'company_phone' );
        $company_website = get_field( 'company_website' );

        $associated_venues = get_field( 'company_venues' );
        $associated_venues = is_array( $associated_venues ) ? $associated_venues : [];

        $internal_notes = get_field( 'company_notes' );

        $company_status_object = get_post_status_object( get_post_status() );
        $company_status_label  = $company_status_object ? $company_status_object->label : __( 'Unknown', 'atlas-theme' );
    ?>
    <main class="content">
        <div class="max-w-6xl mx-auto p-6">
            <!-- Company Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?php the_title(); ?></h1>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i data-feather="briefcase" class="w-3 h-3 mr-1"></i>
                                <?php echo esc_html( $company_type_label ); ?>
                            </span>
                            <span class="text-gray-600">ID <?php echo esc_html( get_the_ID() ); ?></span>
                        </div>
                    </div>
                    <?php $edit_link = get_edit_post_link(); ?>
                    <?php if ( $edit_link ) : ?>
                        <a href="<?php echo esc_url( $edit_link ); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i data-feather="edit" class="w-4 h-4 mr-2"></i>
                            Edit in wp-admin
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
                            Company Details
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Company Type</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo esc_html( $company_type_label ); ?></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Primary Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="text-gray-500"><?php esc_html_e( 'Not set', 'atlas-theme' ); ?></span>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Company Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                                        <?php echo esc_html( $company_status_label ); ?>
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-800 flex items-center">
                            <i data-feather="mail" class="w-4 h-4 mr-2 text-blue-600"></i>
                            Contact Information
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $company_email ) : ?>
                                        <a href="mailto:<?php echo esc_attr( $company_email ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( $company_email ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $company_phone ) : ?>
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9\\+]/', '', $company_phone ) ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( $company_phone ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Website</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $company_website ) : ?>
                                        <a href="<?php echo esc_url( $company_website ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( wp_parse_url( $company_website, PHP_URL_HOST ) ?: $company_website ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Associated Venues Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-800 flex items-center">
                            <i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
                            <?php
                            printf(
                                esc_html__( 'Associated Venues (%d)', 'atlas-theme' ),
                                count( $associated_venues )
                            );
                            ?>
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <?php if ( $associated_venues ) : ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php foreach ( $associated_venues as $venue ) : ?>
                                    <?php
                                        $venue_status_object = get_post_status_object( get_post_status( $venue ) );
                                        $venue_status_label  = $venue_status_object ? $venue_status_object->label : __( 'Unknown', 'atlas-theme' );
                                        $venue_type_object   = get_post_type_object( get_post_type( $venue ) );
                                        $venue_type_label    = $venue_type_object ? $venue_type_object->labels->singular_name : '';
                                    ?>
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <h3 class="font-medium text-gray-800">
                                            <a href="<?php echo esc_url( get_permalink( $venue ) ); ?>" class="hover:text-blue-700">
                                                <?php echo esc_html( get_the_title( $venue ) ); ?>
                                            </a>
                                        </h3>
                                        <?php if ( $venue_type_label ) : ?>
                                            <p class="text-sm text-gray-600 mt-1"><?php echo esc_html( $venue_type_label ); ?></p>
                                        <?php endif; ?>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <?php echo esc_html( $venue_status_label ); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-sm text-gray-700"><?php esc_html_e( 'No associated venues set.', 'atlas-theme' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Internal Notes Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-800 flex items-center">
                            <i data-feather="file-text" class="w-4 h-4 mr-2 text-blue-600"></i>
                            Internal Notes
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <?php if ( $internal_notes ) : ?>
                            <p class="text-sm text-gray-700 italic">
                                <?php echo esc_html( $internal_notes ); ?>
                            </p>
                        <?php else : ?>
                            <p class="text-sm text-gray-500"><?php esc_html_e( 'No internal notes provided.', 'atlas-theme' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        if (window.feather) {
            window.feather.replace();
        }
    </script>
    <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
?>
