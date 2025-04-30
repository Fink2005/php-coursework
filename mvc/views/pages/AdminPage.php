<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar"
            class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-4 px-4 mb-6">
                <div class="font-bold text-xl">Admin Panel</div>
                <button id="closeSidebar" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav>
                <a href="/course-work/Home"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 sidebar-item"
                    data-target="dashboard">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="/course-work/Admin/Users"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 sidebar-item"
                    data-target="users">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="/course-work/Admin/Posts"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 sidebar-item"
                    data-target="posts">
                    <i class="fas fa-file-alt mr-2"></i>Posts
                </a>
                <a href="/course-work/Admin/Tags"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 sidebar-item"
                    data-target="tags">
                    <i class="fas fa-tags mr-2"></i>Tags
                </a>
                <a href="/course-work/Auth/logOut"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 sidebar-item"
                    data-target="tags">
                    <i class="fa-solid fa-right-from-bracket"></i> <span class="ml-1">Sign out</span>
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-md flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button id="openSidebar" class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 id="pageTitle" class="text-xl font-semibold text-gray-800 ml-4">Dashboard</h2>
                </div>
                <div class="flex items-center space-x-4">

                    <div class="flex items-center space-x-2">
                        <span class="text-gray-700">Admin User</span>
                        <img class="h-8 w-8 rounded-full"
                            src="<?php
            echo $_SESSION['user']['avatar'] ? $_SESSION['user']['avatar'] : 'https://ui-avatars.com/api/?name=' . $_SESSION['user']['username'] ?>"
                            alt="User avatar">
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php
        include "./mvc/views/Admin/" . $data["Manage"] . ".php"
        ?>

            </main>
        </div>
    </div>

    <!-- User Modal -->


    <script>
    $(document).ready(function() {

        // Sidebar toggle
        $('#openSidebar').click(function() {
            $('#sidebar').removeClass('-translate-x-full');
        });

        $('#closeSidebar').click(function() {
            $('#sidebar').addClass('-translate-x-full');
        });

        // Navigation
        // $('.sidebar-item').click(function(e) {
        //     e.preventDefault();

        //     // Update active state
        // $('.sidebar-item').removeClass('bg-gray-700');
        // $(this).addClass('bg-gray-700');

        //     // Show corresponding section
        //     const target = $(this).data('target');
        //     $('#pageTitle').text($(this).text().trim());
        //     $('.content-section').addClass('hidden');
        //     $(`#${target}`).removeClass('hidden');

        //     // Close sidebar on mobile
        //     if (window.innerWidth < 768) {
        //         $('#sidebar').addClass('-translate-x-full');
        //     }
        //         <?php 
        //   include "mvc/views/components/admin/UserModal.php"

        //   ?>

        //     <!-- Post Modal -->
        //     <?php 
        //   include "mvc/views/components/admin/PostModal.php"
        //   ?>

        //     <!-- Tag Modal -->
        //     <?php 
        //   include "mvc/views/components/admin/TagModal.php"
        //   ?>

        //     <!-- Delete Confirmation Modal -->
        //     <?php 
        //   include "mvc/views/components/admin/DeleteModal.php"
        //   ?>
        // });

        // User Modal
        // $('#addUserBtn').click(function() {
        //     $('#userModalTitle').text('Add User');
        //     $('#userForm')[0].reset();
        //     $('#userModal').removeClass('hidden');
        // });

        // $('.edit-user-btn').click(function() {
        //     $('#userModalTitle').text('Edit User');
        //     // In a real app, you would populate the form with user data here
        //     $('#userModal').removeClass('hidden');
        // });

        // $('#closeUserModal, #cancelUserBtn').click(function() {
        //     $('#userModal').addClass('hidden');
        //     $('#isNewPassword').addClass('hidden');
        //     $('#isCheckbox').addClass('hidden');
        // });

        // $('#userForm').submit(function(e) {
        //     e.preventDefault();
        //     // In a real app, you would handle form submission here
        //     $('#userModal').addClass('hidden');
        //     // You could show a success message or update the table
        // });

        // Post Modal
        // $('#addPostBtn').click(function() {
        //     $('#postModalTitle').text('Add Post');
        //     $('#postForm')[0].reset();
        //     $('#postModal').removeClass('hidden');
        // });

        $('.edit-post-btn').click(function() {
            $('#postModalTitle').text('Edit Post');
            // In a real app, you would populate the form with post data here
            $('#postModal').removeClass('hidden');
        });

        // $('#closePostModal, #cancelPostBtn').click(function() {
        //     $('#postModal').addClass('hidden');
        // });

        // $('#postForm').submit(function(e) {
        //     e.preventDefault();
        //     // In a real app, you would handle form submission here
        //     $('#postModal').addClass('hidden');
        //     // You could show a success message or update the table
        // });

        // Tag Modal
        $('#addTagBtn').click(function() {
            $('#tagModalTitle').text('Add Tag');
            $('#tagForm')[0].reset();
            $('#tagModal').removeClass('hidden');
        });

        $('.edit-tag-btn').click(function() {
            $('#tagModalTitle').text('Edit Tag');
            // In a real app, you would populate the form with tag data here
            $('#tagModal').removeClass('hidden');
        });

        $('#closeTagModal, #cancelTagBtn').click(function() {
            $('#tagModal').addClass('hidden');
        });

        $('#tagForm').submit(function(e) {
            e.preventDefault();
            // In a real app, you would handle form submission here
            $('#tagModal').addClass('hidden');
            // You could show a success message or update the table
        });

        // Delete Modal
        $('.delete-user-btn').click(function() {
            $('#deleteModalTitle').text('Delete User');
            $('#deleteModalText').text(
                'Are you sure you want to delete this user? This action cannot be undone.');
            $('#deleteModal').removeClass('hidden');
        });

        $('.delete-post-btn').click(function() {
            $('#deleteModalTitle').text('Delete Post');
            $('#deleteModalText').text(
                'Are you sure you want to delete this post? This action cannot be undone.');
            $('#deleteModal').removeClass('hidden');
        });

        $('.delete-tag-btn').click(function() {
            $('#deleteModalTitle').text('Delete Tag');
            $('#deleteModalText').text(
                'Are you sure you want to delete this tag? This action cannot be undone.');
            $('#deleteModal').removeClass('hidden');
        });

        $('#closeDeleteModal, #cancelDeleteBtn').click(function() {
            $('#deleteModal').addClass('hidden');
        });

        $('#confirmDeleteBtn').click(function() {
            // In a real app, you would handle deletion here
            $('#deleteModal').addClass('hidden');
            // You could show a success message or update the table
        });

        // Search functionality
        $('#userSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            // In a real app, you would filter the table based on the search term
            // This is a simplified example
            $('#users table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#postSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#posts table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#tagSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#tags table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Auto-generate slug from tag name
        $('#tagName').on('keyup', function() {
            const slug = $(this).val()
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#tagSlug').val(slug);
        });
    });
    </script>
</body>