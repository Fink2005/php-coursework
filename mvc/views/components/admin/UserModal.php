<section id="userModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="userModalTitle" class="text-lg font-semibold">Add User</h3>
            <button id="closeUserModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="userForm">
                <div class="mb-4">
                    <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input placeholder="Type name" type="text" id="userName" name="username"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="userEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input placeholder="Type email" type="email" id="userEmail" name="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="userRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="userRole" name="permission_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                    </select>
                </div>
                <div class="flex items-center mb-4" id="isCheckbox">
                    <input id="userStatus" type="checkbox" name="verify_status"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2">
                    <label for="userStatus" class="ms-2">Verified</label>
                </div>
                <div class="mb-4" id="passwordFields">
                    <label for="userPassword" class="block text-sm font-medium text-gray-700 mb-1">Current
                        Password</label>
                    <input placeholder="Type current password" type="password" id="userPassword" name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4 hidden" id="newPasswordField">
                    <label for="userNewPassword" class="block text-sm font-medium text-gray-700 mb-1">New
                        Password</label>
                    <input placeholder="Type new password" type="password" id="userNewPassword" name="newPassword"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelUserBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">Cancel</button>
                    <button type="submit" id="saveUserBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>