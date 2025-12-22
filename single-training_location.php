<?php
get_header();
?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Location | Atlas Core</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Location Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Derby</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i data-feather="map-pin" class="w-3 h-3 mr-1"></i>
                            Training Location
                        </span>
                        <span class="text-gray-600">ID 43</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i data-feather="alert-triangle" class="w-3 h-3 mr-1"></i>
                            High Priority
                        </span>
                    </div>
                </div>
                <a href="https://atlas.redrom.co.uk/wp-admin/post.php?post=43&action=edit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-feather="edit" class="w-4 h-4 mr-2"></i>
                    Edit in wp-admin
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Core Details Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i data-feather="info" class="w-4 h-4 mr-2 text-blue-600"></i>
                        Location Details
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Location Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">DER</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Region</dt>
                            <dd class="mt-1 text-sm text-gray-900">East Midlands</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Country</dt>
                            <dd class="mt-1 text-sm text-gray-900">United Kingdom</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Location Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                                    Active
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i data-feather="bar-chart-2" class="w-4 h-4 mr-2 text-blue-600"></i>
                        Statistics
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Venues Available</dt>
                            <dd class="mt-1 text-sm text-gray-900">5</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Courses Last Year</dt>
                            <dd class="mt-1 text-sm text-gray-900">12</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Average Rating</dt>
                            <dd class="mt-1 text-sm text-gray-900">4.2/5</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Last Course Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">2023-10-15</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Associated Venues Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden col-span-1 md:col-span-2">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i data-feather="home" class="w-4 h-4 mr-2 text-blue-600"></i>
                        Associated Venues (5)
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <h3 class="font-medium text-gray-800">Clock Warehouse</h3>
                            <p class="text-sm text-gray-600 mt-1">Primary Venue</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <h3 class="font-medium text-gray-800">Derby Conference Centre</h3>
                            <p class="text-sm text-gray-600 mt-1">Backup Venue</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <h3 class="font-medium text-gray-800">Pride Park Hotel</h3>
                            <p class="text-sm text-gray-600 mt-1">Accommodation</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Provisional
                                </span>
                            </div>
                        </div>
                    </div>
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
                    <p class="text-sm text-gray-700 italic">
                        High demand location with limited venue options. Need to identify additional suitable venues.
                        Current primary venue (Clock Warehouse) has limited availability in Q1 2024.
                    </p>
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
