<?php
get_header();

$plan_labels = array(
	'preferred' => 'Preferred',
	'alternate' => 'Alternate',
	'plan_a'   => 'Plan A',
	'plan_b'   => 'Plan B',
);

$plan_type_field_key = 'venue_plan_type';

$status_styles = array(
	'identified'     => array( 'label' => 'Identified', 'class' => 'bg-yellow-100 text-yellow-800' ),
	'contacted'      => array( 'label' => 'Contacted', 'class' => 'bg-blue-100 text-blue-800' ),
	'quote_received' => array( 'label' => 'Quote Received', 'class' => 'bg-purple-100 text-purple-800' ),
	'confirmed'      => array( 'label' => 'Confirmed', 'class' => 'bg-green-100 text-green-800' ),
	'rejected'       => array( 'label' => 'Rejected', 'class' => 'bg-red-100 text-red-800' ),
);

$deposit_due_labels = array(
	'on_booking' => 'On booking',
	'days_before' => 'X days before course',
	'fixed_date' => 'Fixed date',
);

$payment_due_labels = array(
	'on_booking'   => 'On booking',
	'days_before'  => 'X days before course',
	'on_completion'=> 'On completion',
);

$payment_method_labels = array(
	'invoice'      => 'Invoice',
	'card_on_day'  => 'Card on the day',
	'card_after'   => 'Card after completion',
);

$catering_type_labels = array(
	'in_house' => 'In-house venue catering',
	'external' => 'External caterer',
	'none'     => 'None',
);

$catering_deadline_labels = array(
	'days_before' => 'X days before course',
	'fixed_date'  => 'Fixed date',
);

$food_arrangement_labels = array(
	'menu_selection' => 'Set menu (sandwiches, chips, etc.)',
	'buffet'         => 'Buffet',
	'external'       => 'External catering',
	'none'           => 'No food provided',
);

$food_charge_labels = array(
	'actuals'       => 'Actual attendees',
	'full_capacity' => 'Full course capacity (13)',
);

$drink_charge_labels = array(
	'per_person' => 'Per person',
	'flat_fee'   => 'Flat fee',
);

if ( ! function_exists( 'atlas_bool_display' ) ) {
	function atlas_bool_display( $value ) {
		if ( ! empty( $value ) ) {
			return '<span class="text-green-600 font-medium"><i data-feather="check" class="w-4 h-4 inline mr-1"></i>Yes</span>';
		}

		return '<span class="text-red-600 font-medium"><i data-feather="x" class="w-4 h-4 inline mr-1"></i>No</span>';
	}
}
?>

<?php if ( have_posts() ) : ?>
	<?php
	while ( have_posts() ) :
		the_post();

		$linked_location   = get_field( 'linked_location' );
		$plan_type         = get_field( $plan_type_field_key );
		$plan_type_source  = get_the_ID();

		if ( ! $plan_type ) {
			$plan_type = get_field( 'plan_type' );
		}

		if ( ! $plan_type && $linked_location instanceof WP_Post ) {
			$plan_type_source  = $linked_location->ID;
			$plan_type         = get_field( $plan_type_field_key, $linked_location->ID );
			$legacy_plan_field = ! $plan_type ? get_field( 'plan_type', $linked_location->ID ) : '';
			$plan_type         = $plan_type ? $plan_type : $legacy_plan_field;
		}
		$venue_status      = get_field( 'venue_status' );
		$venue_companies   = get_field( 'venue_companies' );

		$parking_available = get_field( 'atlas_free_parking_adjacent' );
		$ground_floor      = get_field( 'atlas_ground_floor_or_lift' );
		$hotel_on_site     = get_field( 'atlas_hotel_on_site' );
		$breakfast_on_site = get_field( 'atlas_breakfast_on_site' );

		$deposit_required    = get_field( 'deposit_required' );
		$deposit_amount      = get_field( 'deposit_amount' );
		$deposit_due_rule    = get_field( 'deposit_due_rule' );
		$deposit_days_before = get_field( 'deposit_days_before' );
		$payment_due_rule    = get_field( 'payment_due_rule' );
		$payment_days_before = get_field( 'payment_days_before' );
		$payment_methods     = get_field( 'payment_methods_allowed' );

		$catering_type        = get_field( 'catering_type' );
		$catering_deadline    = get_field( 'dietary_deadline_rule' );
		$catering_days_before = get_field( 'dietary_days_before' );

		$food_arrangement   = get_field( 'catering_food_type' );
		$catering_provider  = get_field( 'catering_provider' );
		$food_charge_basis  = get_field( 'food_charging_basis' );
		$food_cost          = get_field( 'food_cost_amount' );
		$drink_charge_basis = get_field( 'drinks_charging_basis' );
		$drink_cost         = get_field( 'drinks_cost_amount' );
		$catering_notes     = get_field( 'catering_notes' );

		$edit_link = get_edit_post_link();
		?>
		<main class="content">
			<div class="max-w-6xl mx-auto p-6">
				<div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
					<div class="flex justify-between items-start">
						<div>
							<h1 class="text-3xl font-bold text-gray-800"><?php the_title(); ?></h1>
							<div class="flex items-center mt-2 space-x-4">
								<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
									<i data-feather="map-pin" class="w-3 h-3 mr-1"></i>
									Training Venue
								</span>
								<span class="text-gray-600">ID <?php echo esc_html( get_the_ID() ); ?></span>
								<?php if ( $venue_status && isset( $status_styles[ $venue_status ] ) ) : ?>
									<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr( $status_styles[ $venue_status ]['class'] ); ?>">
										<i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
										<?php echo esc_html( $status_styles[ $venue_status ]['label'] ); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( $edit_link ) : ?>
							<a href="<?php echo esc_url( $edit_link ); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
								<i data-feather="edit" class="w-4 h-4 mr-2"></i>
								Edit in wp-admin
							</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
						<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 class="text-lg font-medium text-gray-800 flex items-center">
								<i data-feather="info" class="w-4 h-4 mr-2 text-blue-600"></i>
								Core Details
							</h2>
						</div>
						<div class="px-6 py-4">
							<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Linked Location</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php if ( $linked_location instanceof WP_Post ) : ?>
											<a href="<?php echo esc_url( get_permalink( $linked_location ) ); ?>" class="text-blue-600 hover:text-blue-800">
												<?php echo esc_html( get_the_title( $linked_location ) ); ?>
											</a>
										<?php else : ?>
											<span class="text-gray-500">—</span>
										<?php endif; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Plan Type</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php
										$plan_type_label = '—';

										if ( $plan_type ) {
											if ( function_exists( 'atlas_child_choice_label' ) ) {
												$plan_type_label = atlas_child_choice_label( $plan_type, $plan_type_field_key, $plan_type_source );
											}

											if ( ! $plan_type_label && isset( $plan_labels[ $plan_type ] ) ) {
												$plan_type_label = $plan_labels[ $plan_type ];
											}
										}

										echo $plan_type_label ? esc_html( $plan_type_label ) : '—';
										?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Venue Status</dt>
									<dd class="mt-1 text-sm">
										<?php if ( $venue_status && isset( $status_styles[ $venue_status ] ) ) : ?>
											<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo esc_attr( $status_styles[ $venue_status ]['class'] ); ?>">
												<i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
												<?php echo esc_html( $status_styles[ $venue_status ]['label'] ); ?>
											</span>
										<?php else : ?>
											<span class="text-gray-500">—</span>
										<?php endif; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Associated Companies</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php if ( ! empty( $venue_companies ) && is_array( $venue_companies ) ) : ?>
											<div class="flex flex-wrap gap-2">
												<?php foreach ( $venue_companies as $company ) : ?>
													<?php if ( $company instanceof WP_Post ) : ?>
														<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
															<a class="hover:text-indigo-900" href="<?php echo esc_url( get_permalink( $company ) ); ?>">
																<?php echo esc_html( get_the_title( $company ) ); ?>
															</a>
														</span>
													<?php endif; ?>
												<?php endforeach; ?>
											</div>
										<?php else : ?>
											<span class="text-gray-500">—</span>
										<?php endif; ?>
									</dd>
								</div>
							</dl>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
						<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 class="text-lg font-medium text-gray-800 flex items-center">
								<i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
								Facilities
							</h2>
						</div>
						<div class="px-6 py-4">
							<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Sufficient Free Parking Adjacent</dt>
									<dd class="mt-1 text-sm">
										<?php echo wp_kses_post( atlas_bool_display( $parking_available ) ); ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Ground Floor or Lift Access</dt>
									<dd class="mt-1 text-sm">
										<?php echo wp_kses_post( atlas_bool_display( $ground_floor ) ); ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Hotel on Site</dt>
									<dd class="mt-1 text-sm">
										<?php echo wp_kses_post( atlas_bool_display( $hotel_on_site ) ); ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Breakfast on Site</dt>
									<dd class="mt-1 text-sm">
										<?php echo wp_kses_post( atlas_bool_display( $breakfast_on_site ) ); ?>
									</dd>
								</div>
							</dl>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
						<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 class="text-lg font-medium text-gray-800 flex items-center">
								<i data-feather="credit-card" class="w-4 h-4 mr-2 text-blue-600"></i>
								Financial Obligations
							</h2>
						</div>
						<div class="px-6 py-4">
							<dl class="grid grid-cols-2 gap-x-4 gap-y-3">
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Deposit Required?</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $deposit_required ? 'Yes' : 'No'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Deposit Amount (£)</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $deposit_amount !== '' && null !== $deposit_amount ? esc_html( $deposit_amount ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Deposit Due Rule</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $deposit_due_labels[ $deposit_due_rule ] ) ? esc_html( $deposit_due_labels[ $deposit_due_rule ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Deposit – Days Before Course</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $deposit_due_rule === 'days_before' && $deposit_days_before !== '' ? esc_html( $deposit_days_before ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Final Payment Due</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $payment_due_labels[ $payment_due_rule ] ) ? esc_html( $payment_due_labels[ $payment_due_rule ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Final Payment – Days Before Course</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $payment_due_rule === 'days_before' && $payment_days_before !== '' ? esc_html( $payment_days_before ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-2">
									<dt class="text-sm font-medium text-gray-500">Allowed Payment Methods</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php if ( ! empty( $payment_methods ) && is_array( $payment_methods ) ) : ?>
											<?php
											$methods = array();
											foreach ( $payment_methods as $method_key ) {
												if ( isset( $payment_method_labels[ $method_key ] ) ) {
													$methods[] = $payment_method_labels[ $method_key ];
												}
											}
											echo ! empty( $methods ) ? esc_html( implode( ', ', $methods ) ) : '—';
											?>
										<?php else : ?>
											<span class="text-gray-500">—</span>
										<?php endif; ?>
									</dd>
								</div>
							</dl>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
						<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 class="text-lg font-medium text-gray-800 flex items-center">
								<i data-feather="coffee" class="w-4 h-4 mr-2 text-blue-600"></i>
								Catering
							</h2>
						</div>
						<div class="px-6 py-4">
							<dl class="grid grid-cols-1 gap-x-4 gap-y-3">
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Catering Type</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $catering_type_labels[ $catering_type ] ) ? esc_html( $catering_type_labels[ $catering_type ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Dietary Requirements Deadline</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $catering_deadline_labels[ $catering_deadline ] ) ? esc_html( $catering_deadline_labels[ $catering_deadline ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Dietary – Days Before Course</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $catering_deadline === 'days_before' && $catering_days_before !== '' ? esc_html( $catering_days_before ) : '—'; ?>
									</dd>
								</div>
							</dl>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
						<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 class="text-lg font-medium text-gray-800 flex items-center">
								<i data-feather="package" class="w-4 h-4 mr-2 text-blue-600"></i>
								Catering Arrangements
							</h2>
						</div>
						<div class="px-6 py-4">
							<dl class="grid grid-cols-2 gap-x-4 gap-y-3">
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Food Arrangement</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $food_arrangement_labels[ $food_arrangement ] ) ? esc_html( $food_arrangement_labels[ $food_arrangement ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Food Charging Basis</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $food_charge_labels[ $food_charge_basis ] ) ? esc_html( $food_charge_labels[ $food_charge_basis ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Food Cost (£)</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $food_cost !== '' && null !== $food_cost ? esc_html( $food_cost ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Tea &amp; Coffee Charging Basis</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo isset( $drink_charge_labels[ $drink_charge_basis ] ) ? esc_html( $drink_charge_labels[ $drink_charge_basis ] ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Tea &amp; Coffee Cost (£)</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php echo $drink_cost !== '' && null !== $drink_cost ? esc_html( $drink_cost ) : '—'; ?>
									</dd>
								</div>
								<div class="sm:col-span-1">
									<dt class="text-sm font-medium text-gray-500">Catering Provider</dt>
									<dd class="mt-1 text-sm text-gray-900">
										<?php if ( $food_arrangement === 'external' && $catering_provider instanceof WP_Post ) : ?>
											<a href="<?php echo esc_url( get_permalink( $catering_provider ) ); ?>" class="text-blue-600 hover:text-blue-800">
												<?php echo esc_html( get_the_title( $catering_provider ) ); ?>
											</a>
										<?php else : ?>
											<span class="text-gray-500">—</span>
										<?php endif; ?>
									</dd>
								</div>
								<div class="sm:col-span-2">
									<dt class="text-sm font-medium text-gray-500">Catering Notes</dt>
									<dd class="mt-1 text-sm text-gray-900 italic">
										<?php echo $catering_notes ? esc_html( $catering_notes ) : '—'; ?>
									</dd>
								</div>
							</dl>
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
