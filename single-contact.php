<?php
get_header();
?>

<?php
$contact_helpers_path = locate_template( 'functions/contact-helpers.php' );

if (
	( ! function_exists( 'atlas_get_contact_field' ) || ! function_exists( 'atlas_contact_status_classes' ) || ! function_exists( 'atlas_contact_format_date' ) )
	&& $contact_helpers_path
) {
	require_once $contact_helpers_path;
}

if ( ! function_exists( 'atlas_get_contact_field' ) ) {
	/**
	 * Retrieve a contact field value with ACF support and post meta fallback.
	 *
	 * @param string   $meta_key Field/meta key.
	 * @param int|null $post_id  Post ID, defaults to current post.
	 * @param mixed    $default  Default value when no data is available.
	 *
	 * @return mixed
	 */
	function atlas_get_contact_field( $meta_key, $post_id = null, $default = '' ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		$value = null;

		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $meta_key, $post_id );
		}

		if ( null === $value || '' === $value ) {
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			if ( '' !== $meta_value || '0' === $meta_value ) {
				$value = $meta_value;
			}
		}

		if ( null === $value || '' === $value ) {
			$value = $default;
		}

		return $value;
	}
}

if ( ! function_exists( 'atlas_contact_status_classes' ) ) {
	/**
	 * Map contact status labels to Tailwind badge classes.
	 *
	 * @param string $status Status label.
	 *
	 * @return string
	 */
	function atlas_contact_status_classes( $status ) {
		if ( is_array( $status ) ) {
			$status = reset( $status );
		}

		$status = strtolower( (string) $status );

		switch ( $status ) {
			case 'active':
				return 'bg-green-100 text-green-800';
			case 'prospective':
			case 'prospect':
				return 'bg-yellow-100 text-yellow-800';
			case 'inactive':
				return 'bg-gray-200 text-gray-700';
			default:
				return 'bg-blue-100 text-blue-800';
		}
	}
}

if ( ! function_exists( 'atlas_contact_format_date' ) ) {
	/**
	 * Format a date string into DD MM YYYY.
	 *
	 * @param string $date_string Date string to format.
	 *
	 * @return string
	 */
	function atlas_contact_format_date( $date_string ) {
		if ( is_array( $date_string ) ) {
			$date_string = reset( $date_string );
		}

		if ( ! $date_string ) {
			return '';
		}

		$timestamp = strtotime( (string) $date_string );

		if ( ! $timestamp ) {
			return (string) $date_string;
		}

		return date_i18n( 'd m Y', $timestamp );
	}
}
?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <?php
		$company_id   = atlas_get_contact_field( 'contact_company_id' );
		$company_name = atlas_get_contact_field( 'contact_company' );
		$job_title    = atlas_get_contact_field( 'contact_job_title' );
		$status       = atlas_get_contact_field( 'contact_status' );
		$email        = atlas_get_contact_field( 'contact_email' );
		$phone        = atlas_get_contact_field( 'contact_phone' );
		$mobile       = atlas_get_contact_field( 'contact_mobile' );
		$last_contact = atlas_get_contact_field( 'contact_last_contact' );
		$notes        = atlas_get_contact_field( 'contact_notes' );

		$status_label = $status ? $status : __( 'Active', 'atlas-theme' );
	?>
    <main class="content">
        <div class="max-w-6xl mx-auto p-6">
            <!-- Contact Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?php the_title(); ?></h1>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i data-feather="user" class="w-3 h-3 mr-1"></i>
                                Primary Contact
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
									<?php if ( $company_id || $company_name ) : ?>
										<?php if ( $company_id ) : ?>
											<?php $company_title = get_the_title( $company_id ); ?>
											<?php $company_link  = get_permalink( $company_id ); ?>
											<?php if ( $company_link ) : ?>
												<a href="<?php echo esc_url( $company_link ); ?>" class="text-blue-600 hover:text-blue-800">
													<?php echo esc_html( $company_title ); ?>
												</a>
											<?php else : ?>
												<?php echo esc_html( $company_title ); ?>
											<?php endif; ?>
										<?php else : ?>
											<?php echo esc_html( $company_name ); ?>
										<?php endif; ?>
									<?php else : ?>
										<span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo esc_attr( atlas_contact_status_classes( $status_label ) ); ?>">
										<i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                                        <?php echo esc_html( $status_label ); ?>
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
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9\\+]/', '', $phone ) ); ?>" class="text-blue-600 hover:text-blue-800">
											<?php echo esc_html( $phone ); ?>
										</a>
									<?php else : ?>
										<span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
									<?php endif; ?>
								</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                                <dd class="mt-1 text-sm text-gray-900">
									<?php if ( $mobile ) : ?>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9\\+]/', '', $mobile ) ); ?>" class="text-blue-600 hover:text-blue-800">
											<?php echo esc_html( $mobile ); ?>
										</a>
									<?php else : ?>
										<span class="text-gray-500"><?php esc_html_e( 'Not provided', 'atlas-theme' ); ?></span>
									<?php endif; ?>
								</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Last Contacted</dt>
                                <dd class="mt-1 text-sm text-gray-900">
									<?php
									$last_contact_display = $last_contact ? $last_contact : get_the_date( 'Y-m-d' );
									echo esc_html( atlas_contact_format_date( $last_contact_display ) );
									?>
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
                            <?php esc_html_e( 'Venue Scope', 'atlas-theme' ); ?>
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-500"><?php esc_html_e( 'Venue scope information is not available.', 'atlas-theme' ); ?></p>
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
                        <?php if ( $notes ) : ?>
                            <p class="text-sm text-gray-700 italic">
								<?php echo esc_html( $notes ); ?>
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
