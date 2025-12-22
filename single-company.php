<?php
get_header();
?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
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
                                Venue Provider
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
                                <dd class="mt-1 text-sm text-gray-900">Venue Provider</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Primary Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="https://atlas.redrom.co.uk/contacts/detail?id=12" class="text-blue-600 hover:text-blue-800">John Smith</a>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Company Status</dt>
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
                                <dd class="mt-1 text-sm text-gray-900">contact@marstons.com</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">+44 123 456 7890</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Website</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="https://marstons.com" class="text-blue-600 hover:text-blue-800">marstons.com</a>
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
                            Associated Venues (3)
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <h3 class="font-medium text-gray-800">Clock Warehouse</h3>
                                <p class="text-sm text-gray-600 mt-1">Derby</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <h3 class="font-medium text-gray-800">Marstons Hall</h3>
                                <p class="text-sm text-gray-600 mt-1">Manchester</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <h3 class="font-medium text-gray-800">Marstons Tower</h3>
                                <p class="text-sm text-gray-600 mt-1">London</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Prospective
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
                            Preferred venue provider for Midlands region. Good relationship with account manager Sarah Johnson.
                            Provides discounted rates for bulk bookings.
                        </p>
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
