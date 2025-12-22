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
    <title>Venue Details | Atlas Core</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Venue Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Clock Warehouse</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i data-feather="map-pin" class="w-3 h-3 mr-1"></i>
                            Training Venue
                        </span>
                        <span class="text-gray-600">ID 44</span>
                    </div>
                </div>
                <a href="https://atlas.redrom.co.uk/wp-admin/post.php?post=44&action=edit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-feather="edit" class="w-4 h-4 mr-2"></i>
                    Edit in wp-admin
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Core Section -->
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
                                <a href="https://atlas.redrom.co.uk/locations/detail?id=43" class="text-blue-600 hover:text-blue-800">Derby</a>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Plan Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">Plan A</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Venue Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                                    Confirmed
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Associated Companies</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <a href="https://atlas.redrom.co.uk/companies/detail?id=66" class="hover:text-indigo-900">Marstons</a>
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Facilities Section -->
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
                            <dd class="mt-1 text-sm text-green-600 font-medium">
                                <i data-feather="check" class="w-4 h-4 inline mr-1"></i> Yes
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Ground Floor or Lift Access</dt>
                            <dd class="mt-1 text-sm text-red-600 font-medium">
                                <i data-feather="x" class="w-4 h-4 inline mr-1"></i> No
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Hotel on Site</dt>
                            <dd class="mt-1 text-sm text-red-600 font-medium">
                                <i data-feather="x" class="w-4 h-4 inline mr-1"></i> No
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Breakfast on Site</dt>
                            <dd class="mt-1 text-sm text-red-600 font-medium">
                                <i data-feather="x" class="w-4 h-4 inline mr-1"></i> No
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Financial Section -->
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
                            <dd class="mt-1 text-sm text-gray-900">Yes</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Deposit Amount (£)</dt>
                            <dd class="mt-1 text-sm text-gray-900">50</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Deposit Due Rule</dt>
                            <dd class="mt-1 text-sm text-gray-900">On booking</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Final Payment Due</dt>
                            <dd class="mt-1 text-sm text-gray-900">On completion</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Allowed Payment Methods</dt>
                            <dd class="mt-1 text-sm text-gray-900">Invoice, Card after completion</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Catering Section -->
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
                            <dd class="mt-1 text-sm text-gray-900">In-house venue catering</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Dietary Requirements Deadline</dt>
                            <dd class="mt-1 text-sm text-gray-900">X days before course</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Dietary – Days Before Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">5</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Catering Arrangements Section -->
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
                            <dd class="mt-1 text-sm text-gray-900">Buffet</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Food Charging Basis</dt>
                            <dd class="mt-1 text-sm text-gray-900">Full course capacity (13)</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Food Cost (£)</dt>
                            <dd class="mt-1 text-sm text-gray-900">198</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tea & Coffee Charging Basis</dt>
                            <dd class="mt-1 text-sm text-gray-900">Per person</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tea & Coffee Cost (£)</dt>
                            <dd class="mt-1 text-sm text-gray-900">1.50</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Catering Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 italic">TESTING HERE</dd>
                        </div>
                    </dl>
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
