<section id="users" class="content-section">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Users Management</h3>
            <button id="addUserBtn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Add User
            </button>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
            <div class="relative w-full md:w-64">
                <input type="text" id="userSearch" placeholder="Search users..."
                    class="w-full py-2 pl-10 pr-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <!-- <div class="flex space-x-2 w-full md:w-auto">
                <select
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="user">User</option>
                </select>
                <select
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div> -->
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created at</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="users-tbody" class="bg-white divide-y divide-gray-200">

                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            <div id="currentPage" class="text-sm text-gray-500">
            </div>
            <div class="flex space-x-1" id="pagination">

            </div>
        </div>
    </div>

    <?php 
         include "mvc/views/components/admin/UserModal.php";?>

</section>
<script>
$(document).ready(function() {
    let currentPage = 1;
    let users = [];
    let isEditing = false;
    let editingUserId = null;

    // Fetch users
    const fetchUsers = (page = 1) => {
        $('#users-tbody').html('<tr><td colspan="5" class="text-center py-4">Loading...</td></tr>');
        $.ajax({
            url: `/course-work/Admin/Users/${page}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                users = response.users;
                currentPage = parseInt(response.currentPage);
                const totalPages = response.totalPages;

                // Render current page info
                let currentUserPage =
                    `<p>Showing ${currentPage} to ${totalPages} of ${totalPages} entries</p>`;

                // Generate pagination buttons
                let paginationButtons = '';
                for (let i = 1; i <= totalPages; i++) {
                    const isActive = i === currentPage ? 'bg-blue-500 text-white' :
                        'bg-gray-200 text-gray-600';
                    paginationButtons +=
                        `<button id="pagination${i}" class="px-3 py-1 rounded-md ${isActive} hover:bg-gray-300">${i}</button>`;
                }

                let userPagination = `
                    <button id="previous" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage <= 1 ? 'disabled' : ''}>Previous</button>
                    ${paginationButtons}
                    <button id="next" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage >= totalPages ? 'disabled' : ''}>Next</button>
                `;

                // Render user table
                let html = '';
                users.forEach(user => {
                    const formattedDate = dateFns.format(dateFns.parseISO(user
                        .created_at), 'MMM dd, yyyy');
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=${user.username}" alt="User avatar">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${user.username}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.permission_id === 1 ? 'User' : 'Admin'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${formattedDate}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button data-user-id="${user.id}" class="text-blue-600 hover:text-blue-900 mr-3 edit-user-btn"><i class="fas fa-edit"></i></button>
                                <button data-user-id="${user.id}" class="text-red-600 hover:text-red-900 delete-user-btn"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;
                });

                // Update DOM
                $('#users-tbody').html(html);
                $('#currentPage').html(currentUserPage);
                $('#pagination').html(userPagination);
            },
        });
    };

    // Initial fetch
    fetchUsers();



    // Open modal for adding user
    $('#addUserBtn').on('click', function() {
        isEditing = false;
        editingUserId = null;
        $('#userModalTitle').text('Add User');
        $('#userForm')[0].reset();
        $('#isCheckbox').removeClass('hidden');
        $('#newPasswordField').addClass('hidden');
        $('#userModal').removeClass('hidden');

    });

    // Open modal for editing user
    $('#users-tbody').on('click', '.edit-user-btn', function() {
        isEditing = true;
        editingUserId = $(this).data('user-id');
        const userDetail = users.find(user => user.id === editingUserId);
        $('#userModalTitle').text('Update User');
        $('#userName').val(userDetail.username);
        $('#userEmail').val(userDetail.email);
        $('#userRole').val(userDetail.permission_id);
        $('#userStatus').prop('checked', userDetail.verify_status === 1);
        $('#userPassword').val('');
        $('#userNewPassword').val('');
        $('#isCheckbox').removeClass('hidden');
        $('#passwordFields').removeClass('hidden');
        $('#newPasswordField').removeClass('hidden');

        $('#userModal').removeClass('hidden');
    });

    // Close modal
    $('#closeUserModal, #cancelUserBtn').on('click', function() {
        $('#userModal').addClass('hidden');
        $('#userForm')[0].reset();
        isEditing = false;
        editingUserId = null;
    });

    // Handle form submission
    $('#userForm').on('submit', function(e) {
        e.preventDefault();

        // Get form values
        const email = $('#userEmail').val().trim();
        const username = $('#userName').val().trim();
        const password = $('#userPassword').val() || '';
        const newPassword = $('#userNewPassword').val() || '';
        const permission_id = $('#userRole').val();
        const verify_status = $('#userStatus').is(':checked') ? 1 : 0;

        // Validations
        checkValidation('auth', email, username, password)


        if ((password || (isEditing && newPassword)) && (password.length < 6 || (isEditing &&
                newPassword.length < 6))) {
            showToast('Passwords must be at least 6 characters', 'error')

            return;
        }

        // Prepare data
        const data = {
            email,
            username,
            password,
            newPassword,
            permission_id,
            verify_status
        };

        // Determine URL and method
        const url = isEditing ? `/course-work/Admin/UpdateUser/${editingUserId}` :
            '/course-work/Auth/signUp';
        $.ajax({
            url,
            type: 'POST',
            data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success')
                    $('#userModal').addClass('hidden');
                    $('#userForm')[0].reset();
                    fetchUsers(currentPage);

                } else {
                    showToast(response.message, 'error')
                }
            },

        });
    });

    // Handle delete user
    $('#users-tbody').on('click', '.delete-user-btn', function() {
        const userId = $(this).data('user-id');
        Swal.fire({
            title: "Do you want to delete this user?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/course-work/Admin/DeleteUser/${userId}`,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(response.message);
                            fetchUsers(currentPage);

                        } else {
                            Swal.fire(response.message);
                        }
                    },



                });
            }
        });

    });

    // Handle search
    $('#userSearch').on('input', function() {
        const search = $(this).val().toLowerCase();
        const filteredUsers = users.filter(user =>
            user.username.toLowerCase().includes(search) || user.email.toLowerCase().includes(
                search)
        );
        renderUsers(filteredUsers);
    });


    $('#pagination').on('click', '#previous', function() {
        if (currentPage > 1) {
            fetchUsers(currentPage - 1);
        }
    });

    $('#pagination').on('click', '#next', function() {
        fetchUsers(currentPage + 1);
    });
    $('#pagination').on('click', '[id^="pagination"]', function() {
        const page = parseInt($(this).attr('id').replace('pagination', ''));
        fetchUsers(page);
    });


    // Handle role filter
    // $('#roleFilter').on('change', function() {
    //     const role = $(this).val();
    //     fetchUsers(1, role, $('#perPage').val());
    // });

    // Handle per page
    // $('#perPage').on('change', function() {
    //     const perPage = $(this).val();
    //     fetchUsers(1, $('#roleFilter').val(), perPage);
    // });

    // Render users (for search)
    // const renderUsers = (usersToRender) => {
    //     let html = '';
    //     usersToRender.forEach(user => {
    //         const formattedDate = dateFns.format(dateFns.parseISO(user.created_at), 'MMM dd, yyyy');
    //         html += `
    //             <tr>
    //                 <td class="px-6 py-4 whitespace-nowrap">
    //                     <div class="flex items-center">
    //                         <div class="flex-shrink-0 h-10 w-10">
    //                             <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=${user.username}" alt="User avatar">
    //                         </div>
    //                         <div class="ml-4">
    //                             <div class="text-sm font-medium text-gray-900">${user.username}</div>
    //                         </div>
    //                     </div>
    //                 </td>
    //                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.email}</td>
    //                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.permission_id === 1 ? 'User' : 'Admin'}</td>
    //                 <td class="px-6 py-4 whitespace-nowrap">
    //                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${formattedDate}</span>
    //                 </td>
    //                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    //                     <button data-user-id="${user.id}" class="text-blue-600 hover:text-blue-900 mr-3 edit-user-btn"><i class="fas fa-edit"></i></button>
    //                     <button data-user-id="${user.id}" class="text-red-600 hover:text-red-900 delete-user-btn"><i class="fas fa-trash"></i></button>
    //                 </td>
    //             </tr>`;
    //     });
    //     $('#users-tbody').html(html ||
    //         '<tr><td colspan="5" class="text-center py-4">No users found</td></tr>');
    // };
});
</script>