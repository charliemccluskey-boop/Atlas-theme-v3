<?php
get_header();
?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <?php
        $first_name   = get_field( 'first_name' );
        $last_name    = get_field( 'last_name' );
        $job_title    = get_field( 'contact_job_title' );
        $company      = get_field( 'contact_company' );
        $email        = get_field( 'email' );
        $phone        = get_field( 'phone' );
        $last_contact = get_field( 'last_contacted_date' );

        $contact_status_object = get_post_status_object( get_post_status() );
        $contact_status_label  = $contact_status_object ? $contact_status_object->label : __( 'Unknown', 'atlas-theme' );

        $contact_venues = get_field( 'contact_venues' );
        $contact_venues = is_array( $contact_venues ) ? $contact_venues : [];

        $internal_notes = get_the_content();
    ?>
    <main class="content">
        <div class="max-w-6xl mx-auto p-6">
            <!-- Contact Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?php the_title(); ?></h1>
                        <p class="text-gray-600 mt-1">
                            <?php
                            $name_parts = array_filter( [ $first_name, $last_name ] );
                            echo $name_parts ? esc_html( implode( ' ', $name_parts ) ) : esc_html__( 'Name not provided', 'atlas-theme' );
                            ?>
                        </p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i data-feather="user" class="w-3 h-3 mr-1"></i>
                                Contact
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
                            Contact Details
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Company</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $company instanceof WP_Post ) : ?>
                                        <a href="<?php echo esc_url( get_permalink( $company ) ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( get_the_title( $company ) ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not set', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $job_title ) : ?>
                                        <?php echo esc_html( $job_title ); ?>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Contact Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                                        <?php echo esc_html( $contact_status_label ); ?>
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
                                    <?php if ( $email ) : ?>
                                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( $email ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $phone ) : ?>
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9\+]/', '', $phone ) ); ?>" class="text-blue-600 hover:text-blue-800">
                                            <?php echo esc_html( $phone ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Last Contacted</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php if ( $last_contact ) : ?>
                                        <?php echo esc_html( $last_contact ); ?>
                                    <?php else : ?>
                                        <span class="text-gray-500"><?php esc_html_e( 'Not recorded', 'atlas-theme' ); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Venue Scope Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-800 flex items-center">
                            <i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
                            <?php
                            printf(
                                esc_html__( 'Venue Scope (%d)', 'atlas-theme' ),
                                count( $contact_venues )
                            );
                            ?>
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <?php if ( $contact_venues ) : ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ( $contact_venues as $venue ) : ?>
                                    <?php
                                        $venue_status_object = get_post_status_object( get_post_status( $venue ) );
                                        $venue_status_label  = $venue_status_object ? $venue_status_object->label : __( 'Unknown', 'atlas-theme' );
                                        $venue_location      = get_field( 'venue_town', $venue );
                                    ?>
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <h3 class="font-medium text-gray-800">
                                            <a href="<?php echo esc_url( get_permalink( $venue ) ); ?>" class="hover:text-blue-700">
                                                <?php echo esc_html( get_the_title( $venue ) ); ?>
                                            </a>
                                        </h3>
                                        <?php if ( $venue_location ) : ?>
                                            <p class="text-sm text-gray-600 mt-1"><?php echo esc_html( $venue_location ); ?></p>
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
                            <p class="text-sm text-gray-700"><?php esc_html_e( 'No venues linked to this contact.', 'atlas-theme' ); ?></p>
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
                            <p class="text-sm text-gray-700 italic"><?php echo esc_html( wp_strip_all_tags( $internal_notes ) ); ?></p>
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
