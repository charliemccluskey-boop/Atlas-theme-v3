<?php
/**
 * Dashboard index template
 */

// Helper to safely count published posts for a post type.
if ( ! function_exists( 'atlas_dashboard_count' ) ) {
	function atlas_dashboard_count( $post_type ) {
		if ( ! $post_type || ! post_type_exists( $post_type ) ) {
			return 0;
		}

		$counts = wp_count_posts( $post_type );

		return isset( $counts->publish ) ? intval( $counts->publish ) : 0;
	}
}

// Helper to get an archive link with a sensible fallback.
if ( ! function_exists( 'atlas_dashboard_archive_link' ) ) {
	function atlas_dashboard_archive_link( $post_type, $fallback = '' ) {
		if ( $post_type && post_type_exists( $post_type ) ) {
			$archive_link = get_post_type_archive_link( $post_type );

			if ( $archive_link ) {
				return $archive_link;
			}
		}

		if ( $fallback ) {
			return $fallback;
		}

		return home_url( '/' );
	}
}

$course_post_type = post_type_exists( 'training_course' ) ? 'training_course' : 'post';
$courses_count    = atlas_dashboard_count( $course_post_type );
$venues_count     = atlas_dashboard_count( 'training_venue' );
$locations_count  = atlas_dashboard_count( 'training_location' );
$companies_count  = atlas_dashboard_count( 'company' );

$dashboard_links = array(
	'courses'   => atlas_dashboard_archive_link( $course_post_type, get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ),
	'venues'    => atlas_dashboard_archive_link( 'training_venue' ),
	'locations' => atlas_dashboard_archive_link( 'training_location' ),
	'companies' => atlas_dashboard_archive_link( 'company' ),
);

wp_enqueue_script( 'atlas-chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true );

$dashboard_inline_script = <<<JS
document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) {
        window.feather.replace();
    }

    if (!window.Chart) {
        return;
    }

    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 18000, 22000, 25000, 28000, 26000, 30000, 28000, 32000, 35000],
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Profit',
                        data: [8000, 12000, 9000, 11000, 15000, 17000, 19000, 18000, 21000, 19000, 23000, 25000],
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        const statusCtx = statusCanvas.getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Scheduled', 'Confirmed', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [15, 22, 35, 3],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }
});
JS;

wp_add_inline_script( 'atlas-chart-js', $dashboard_inline_script );

get_header();
?>

<main class="content bg-gray-50">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Dashboard Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-gray-600 mt-2">Overview of your training operations</p>
                </div>
                <div class="flex space-x-2">
                    <div class="relative">
                        <select class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm">
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>This Year</option>
                            <option>All Time</option>
                        </select>
                        <i data-feather="chevron-down" class="absolute right-3 top-2.5 w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Courses Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <a href="<?php echo esc_url( $dashboard_links['courses'] ); ?>" class="flex items-center group">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i data-feather="calendar" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 group-hover:text-blue-700">Upcoming Courses</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo esc_html( $courses_count ); ?></p>
                </div>
            </a>
            <div class="mt-4">
                <a href="<?php echo esc_url( $dashboard_links['courses'] ); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all courses →</a>
            </div>
        </div>

            <!-- Venues Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i data-feather="home" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Venues</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo esc_html( $venues_count ); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo esc_url( $dashboard_links['venues'] ); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all venues →</a>
                </div>
            </div>

            <!-- Locations Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i data-feather="map-pin" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Training Locations</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo esc_html( $locations_count ); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo esc_url( $dashboard_links['locations'] ); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all locations →</a>
                </div>
            </div>

            <!-- Companies Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i data-feather="briefcase" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Venue Providers</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo esc_html( $companies_count ); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo esc_url( $dashboard_links['companies'] ); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all companies →</a>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Revenue & Profit</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Courses by Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Courses by Status</h2>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Upcoming Courses -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">
                    <a href="<?php echo esc_url( $dashboard_links['courses'] ); ?>" class="flex items-center hover:text-blue-700">
                        <i data-feather="calendar" class="w-4 h-4 mr-2 text-blue-600"></i>
                        Upcoming Courses (Next 30 Days)
                    </a>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendees</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2023-12-15</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Advanced First Aid</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="<?php echo esc_url( $dashboard_links['venues'] ); ?>" class="text-blue-600 hover:text-blue-800">Clock Warehouse</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="<?php echo esc_url( $dashboard_links['locations'] ); ?>" class="text-blue-600 hover:text-blue-800">Derby</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12/12</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2023-12-18</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Fire Safety</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="<?php echo esc_url( $dashboard_links['venues'] ); ?>" class="text-blue-600 hover:text-blue-800">Marstons Hall</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="<?php echo esc_url( $dashboard_links['locations'] ); ?>" class="text-blue-600 hover:text-blue-800">Manchester</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Scheduled
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8/12</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <i data-feather="activity" class="w-4 h-4 mr-2 text-blue-600"></i>
                    Recent Activity
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                <i data-feather="calendar" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium">Course confirmed</span> - Advanced First Aid at Clock Warehouse on Dec 15
                            </p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded-full bg-green-100 text-green-600">
                                <i data-feather="home" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium">New venue added</span> - Marstons Tower in London
                            </p>
                            <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                                <i data-feather="map-pin" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium">Location updated</span> - Derby location marked as high priority
                            </p>
                            <p class="text-xs text-gray-500 mt-1">2 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
