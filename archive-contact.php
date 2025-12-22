<?php
get_header();

if ( ! function_exists( 'atlas_contact_status_classes' ) ) {
	/**
	 * Map contact status labels to Tailwind badge classes.
	 *
	 * @param string $status Status label.
	 *
	 * @return string
	 */
	function atlas_contact_status_classes( $status ) {
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

if ( ! function_exists( 'atlas_contact_linked_text' ) ) {
	/**
	 * Display a linked value if a matching post ID exists, otherwise plain text.
	 *
	 * @param string|int $value Value to display.
	 *
	 * @return string
	 */
	function atlas_contact_linked_text( $value ) {
		if ( ! $value ) {
			return '-';
		}

		if ( is_numeric( $value ) ) {
			$label = get_the_title( (int) $value );
			$url   = get_permalink( (int) $value );

			if ( $url ) {
				return sprintf(
					'<a href="%1$s" class="text-blue-600 hover:text-blue-800">%2$s</a>',
					esc_url( $url ),
					esc_html( $label )
				);
			}
		}

		return esc_html( (string) $value );
	}
}

if ( ! function_exists( 'atlas_contact_default_text' ) ) {
	/**
	 * Return a default dash when the value is empty.
	 *
	 * @param string $value Value to format.
	 *
	 * @return string
	 */
	function atlas_contact_default_text( $value ) {
		return $value ? esc_html( $value ) : '-';
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php esc_html_e( 'Contacts Archive | Atlas Core', 'jointswp' ); ?></title>
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
					<h1 class="text-3xl font-bold text-gray-800"><?php esc_html_e( 'Contacts', 'jointswp' ); ?></h1>
					<p class="text-gray-600 mt-2"><?php esc_html_e( 'View and manage all company contacts', 'jointswp' ); ?></p>
				</div>
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=contact' ) ); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
					<i data-feather="plus" class="w-4 h-4 mr-2"></i>
					<?php esc_html_e( 'Add New Contact', 'jointswp' ); ?>
				</a>
			</div>
		</div>

		<!-- Contacts Table -->
		<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
				<h2 class="text-lg font-medium text-gray-800 flex items-center">
					<i data-feather="users" class="w-4 h-4 mr-2 text-blue-600"></i>
					<?php esc_html_e( 'All Contacts', 'jointswp' ); ?>
				</h2>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Name', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Company', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Job Title', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Status', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Email', 'jointswp' ); ?></th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php esc_html_e( 'Last Contact', 'jointswp' ); ?></th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						<?php if ( have_posts() ) : ?>
							<?php
							while ( have_posts() ) :
								the_post();

								$company_id   = get_post_meta( get_the_ID(), 'contact_company_id', true );
								$company_name = get_post_meta( get_the_ID(), 'contact_company', true );
								$job_title    = get_post_meta( get_the_ID(), 'contact_job_title', true );
								$status       = get_post_meta( get_the_ID(), 'contact_status', true );
								$email        = get_post_meta( get_the_ID(), 'contact_email', true );
								$last_contact = get_post_meta( get_the_ID(), 'contact_last_contact', true );
								$status_label = $status ? $status : __( 'Active', 'jointswp' );
								$company      = $company_id ? $company_id : $company_name;
								$last_contact = $last_contact ? $last_contact : get_the_date( 'Y-m-d' );
								?>
								<tr class="hover:bg-gray-50">
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
										<a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800"><?php the_title(); ?></a>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_contact_linked_text( $company ); ?></td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_contact_default_text( $job_title ); ?></td>
									<td class="px-6 py-4 whitespace-nowrap">
										<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( atlas_contact_status_classes( $status_label ) ); ?>">
											<?php echo esc_html( $status_label ); ?>
										</span>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
										<?php if ( $email ) : ?>
											<a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-blue-600 hover:text-blue-800"><?php echo esc_html( $email ); ?></a>
										<?php else : ?>
											-
										<?php endif; ?>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo atlas_contact_default_text( $last_contact ); ?></td>
								</tr>
							<?php endwhile; ?>
						<?php else : ?>
							<tr>
								<td colspan="6" class="px-6 py-4 text-sm text-gray-600 text-center"><?php esc_html_e( 'No contacts found.', 'jointswp' ); ?></td>
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
