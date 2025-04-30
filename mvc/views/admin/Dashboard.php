<section id="dashboard" class="content-section">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                    <p class="text-2xl font-semibold">1,250</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                    <i class="fas fa-file-alt text-green-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Posts</h3>
                    <p class="text-2xl font-semibold">5,432</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                    <i class="fas fa-tags text-purple-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Tags</h3>
                    <p class="text-2xl font-semibold">342</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Created Post</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Getting Started
                            with Tailwind</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 minutes ago</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Updated User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 hour ago</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Created Tag</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">JavaScript</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 hours ago</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>