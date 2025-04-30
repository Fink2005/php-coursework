<!-- HTML: Post Management Section -->
<section id="posts" class="content-section">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Post Management</h3>
            <button id="addpostBtn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Post
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
            <div class="relative w-full md:w-64">
                <input type="text" id="postSearch" placeholder="Search posts..."
                    class="w-full py-2 pl-10 pr-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <!-- <div class="flex space-x-2 w-full md:w-auto">
                <select id="roleFilter"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="post">Post</option>
                </select>
                <select id="perPage"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div> -->
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            user's post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post
                            URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="posts-tbody" class="bg-white divide-y divide-gray-200"></tbody>
            </table>
        </div>

        <!-- Pagination and Current Page Info -->
        <div class="flex justify-between items-center mt-6">
            <div id="currentPage" class="text-sm text-gray-500"></div>
            <div id="pagination" class="flex space-x-1"></div>
        </div>
    </div>

    <!-- Modal (assumed to be included from postModal.php) -->
    <?php include "mvc/views/components/admin/postModal.php"; ?>
</section>


<script>
let currentPage = 1;
let isEditing = false;
let editingPostId = null;
let posts = [];
// let tags = [];
// let checkedValues = []; // Stores { id, name } for selected tags
let uploadedImageUrl = ''; // Store uploaded image URL

// Debounce function to limit the rate of function calls

// Function to fetch and render selected tags in #tagsStore
// const fetchTags = () => {
//     let tagsHtml = '';
//     checkedValues.forEach((item) => {
//         tagsHtml += `
//             <div class="flex my-1 w-auto justify-between items-center bg-white border-2 text-black space-x-2 text-xs font-medium me-2 px-2.5 py-0.5 rounded-lg py-1" data-id="${item.id}">
//                 <span>${item.name}</span>
//                 <span class="fa-solid fa-xmark text-black text-xs cursor-pointer remove-tag"></span>
//             </div>`;
//     });
//     $('#tagsStore').html(tagsHtml);
//     if (checkedValues.length === 0) {
//         $('#tagsStore').addClass('hidden');
//     } else {
//         $('#tagsStore').removeClass('hidden');
//     }
// };

// Function to update the tag dropdown with provided tags
// function updateTagDropdown(filteredTags) {
//     console.log('Updating dropdown with tags:', filteredTags);
//     let dataTagsDropDown = '';
//     if (filteredTags.length === 0) {
//         dataTagsDropDown = '<li class="py-2 text-sm text-gray-500">No tags found</li>';
//     } else {
//         filteredTags.forEach((tag) => {
//             const isChecked = checkedValues.some(checkedTag => checkedTag.id === tag.id);
//             dataTagsDropDown += `
//                 <li>
//                     <div class="flex items-center ps-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
//                         <input ${isChecked ? 'checked' : ''} id="checkbox-tag-${tag.id}" type="checkbox" class="tag-check w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500" data-id="${tag.id}" data-name="${tag.name}">
//                         <label for="checkbox-tag-${tag.id}" class="w-full py-2 ms-2 text-sm font-medium text-white rounded-sm">${tag.name}</label>
//                     </div>
//                 </li>`;
//         });
//     }
//     $('#tagsDropDownSearch').html(dataTagsDropDown);
// }

// Debounced search function to fetch tags based on query

const fetchPosts = (page = 1) => {
    $('#posts-tbody').html('<tr><td colspan="8" class="text-center py-4">Loading...</td></tr>');

    $.ajax({
        url: `/course-work/Admin/Posts/${page}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            posts = response.posts || [];
            console.log('Posts:', posts);
            currentPage = parseInt(response.currentPage) || 1;
            const totalPages = response.totalPages || 1;

            const currentPostPage =
                `<p>Showing ${response.currentPage} to ${response.totalPages} of ${response.totalPages} entries</p>`;

            let paginationButtons = '';
            for (let i = 1; i <= totalPages; i++) {
                const isActive = i === currentPage ? 'bg-blue-500 text-white' :
                    'bg-gray-200 text-gray-600';
                paginationButtons +=
                    `<button id="pagination${i}" class="px-3 py-1 rounded-md ${isActive} hover:bg-gray-300">${i}</button>`;
            }

            const postPagination = `
                <button id="previous" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage <= 1 ? 'disabled' : ''}>Previous</button>
                ${paginationButtons}
                <button id="next" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage >= totalPages ? 'disabled' : ''}>Next</button>
            `;

            let html = '';
            if (posts.length === 0) {
                html = '<tr><td colspan="8" class="text-center py-4">No posts found</td></tr>';
            } else {
                posts.forEach(post => {
                    const formattedDate = dateFns.format(dateFns.parseISO(post.created_at),
                        'MMM dd, yyyy');
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${post.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[200px]"><p>${post.name}</p></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[200px]"><p>${post.username}</p></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="w-[240px]">
                                    <img class="object-contain" src="${post.image_url || 'https://via.placeholder.com/240'}" alt="Post image">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a class="text-nowrap" href="${post.post_url || '#'}" alt="Post url">${post.post_url || 'N/A'}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><p>${post.des || 'No description'}</p></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${formattedDate}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button data-post-id="${post.id}" class="text-blue-600 hover:text-blue-900 mr-3 edit-post-btn"><i class="fas fa-edit"></i></button>
                                <button data-post-id="${post.id}" class="text-red-600 hover:text-red-900 delete-post-btn"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;
                });
            }

            $('#posts-tbody').html(html);
            $('#currentPage').html(currentPostPage);
            $('#pagination').html(postPagination);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#posts-tbody').html(
                '<tr><td colspan="8" class="text-center py-4">Failed to load posts</td></tr>');
            $('#currentPage').html('<p>Error loading data</p>');
            $('#pagination').html('');
        }
    });
};

$(document).ready(function() {
    fetchPosts();

    // Fetch all tags initially


    // // Handle search input
    // $('#input-group-search').on('input', function() {
    //     const query = $(this).val().trim();
    //     console.log('Input query:', query);
    //     searchTags(query);
    // });

    // Handle tag checkbox clicks
    // $('#tagsDropDownSearch').on('click', '[id^="checkbox-tag-"]', function() {
    //     const tagId = $(this).data('id');
    //     const tagName = $(this).data('name');
    //     const isChecked = $(this).prop('checked');

    //     const findIndex = checkedValues.findIndex((item) => item.id === tagId);
    //     if (isChecked && findIndex === -1 && checkedValues.length < 10) {
    //         checkedValues.push({
    //             id: tagId,
    //             name: tagName
    //         });
    //     } else if (!isChecked && findIndex !== -1) {
    //         checkedValues.splice(findIndex, 1);
    //     } else if (isChecked && checkedValues.length >= 10) {
    //         $(this).prop('checked', false);
    //         showToast('You can select up to 10 tags only.', 'error');
    //     }
    //     fetchTags();
    // });

    // Handle tag removal
    // $('#tagsStore').on('click', '.remove-tag', function() {
    //     const tagId = $(this).parent().data('id');
    //     const findIndex = checkedValues.findIndex((item) => item.id === tagId);

    //     if (findIndex !== -1) {
    //         checkedValues.splice(findIndex, 1);
    //         $(`#checkbox-tag-${tagId}`).prop('checked', false);
    //     }
    //     fetchTags();
    // });

    // Image upload preview
    $('#dropzone-file').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            $('#dropzone').addClass('hidden');
            reader.onload = function(event) {
                $('#postImageUrl').attr('src', event.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
            $('#uploadImage').prop('disabled', false).addClass('!bg-[#2463EB]');
            $('#cancelImage').removeClass('hidden');
        }
    });

    // Cancel image upload
    $('#cancelImage').on('click', function() {
        $('#postImageUrl').attr('src', '').addClass('hidden');
        $('#dropzone').removeClass('hidden');
        $('#uploadImage').prop('disabled', false).addClass('!bg-[#2463EB]');
        $('#cancelImage').addClass('hidden');
        $('#textImage').text('Upload');
        uploadedImageUrl = '';
    });

    // Upload image
    $('#uploadImage').on('click', function() {
        const file = $('#dropzone-file')[0].files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append('file', file);

        $('#uploadImage').prop('disabled', true);
        $('#loading').removeClass('hidden');

        $.ajax({
            url: '/course-work/Ajax/upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    uploadedImageUrl = response.image_url.url;
                    console.log('Uploaded image URL:', uploadedImageUrl);
                    $('#postImageUrl').attr('src', uploadedImageUrl);
                    $('#textImage').text('Uploaded');
                    $('#uploadImage').removeClass('!bg-[#2463EB]');
                    showToast('Uploaded image successfully', 'success');
                } else {
                    showToast('Failed to upload image: ' + (response.message ||
                        'Unknown error'), 'error');
                }
                $('#loading').addClass('hidden');
            },
            error: function(xhr, status, error) {
                console.error('Error uploading image:', error);
                showToast('Failed to upload image', 'error');
                $('#loading').addClass('hidden');
                $('#uploadImage').prop('disabled', false);
            }
        });
    });

    // Pagination controls
    $('#pagination').on('click', '#previous', function() {
        if (currentPage > 1) {
            fetchPosts(currentPage - 1);
        }
    });

    $('#pagination').on('click', '#next', function() {
        fetchPosts(currentPage + 1);
    });

    $('#pagination').on('click', '[id^="pagination"]', function() {
        const page = parseInt($(this).attr('id').replace('pagination', ''));
        fetchPosts(page);
    });

    // Open add post modal
    $('#addpostBtn').on('click', function() {
        isEditing = false;
        editingPostId = null;
        $('#postModal').removeClass('hidden');
        $('#postModalTitle').text('Add Post');
        $('#postForm')[0].reset();
        $('#dropzone').removeClass('hidden');
        $('#postImageUrl').attr('src', '').addClass('hidden');
        $('#uploadImage').prop('disabled', true).removeClass('!bg-[#2463EB]');
        $('#cancelImage').addClass('hidden');
        $('#textImage').text('Upload');
        checkedValues = [];
        fetchTagsDropDown();
        uploadedImageUrl = '';
        updateTagDropdown(tags);
    });

    // Edit post
    $('#posts-tbody').on('click', '.edit-post-btn', function() {
        isEditing = true;
        editingPostId = $(this).data('post-id');
        const postDetail = posts.find(post => post.id === editingPostId);

        if (postDetail) {
            $('#postModalTitle').text('Update Post');
            $('#postName').val(postDetail.name);
            $('#postUrl').val(postDetail.post_url);
            $('#postDescription').val(postDetail.des);
            $('#postImageUrl').attr('src', postDetail.image_url || '').removeClass('hidden');
            $('#dropzone').addClass('hidden');
            $('#uploadImage').prop('disabled', true).removeClass('!bg-[#2463EB]');
            $('#cancelImage').removeClass('hidden');
            $('#textImage').text('Uploaded');
            uploadedImageUrl = postDetail.image_url || '';

            // Load post tags
            checkedValues = postDetail.tags ? tags.filter(tag => postDetail.tags.includes(tag.name))
                .map(tag => ({
                    id: tag.id,
                    name: tag.name
                })) : [];
            fetchTagsDropDown();
            updateTagDropdown(tags);

            $('#postModal').removeClass('hidden');
        } else {
            console.error('Post not found:', editingPostId);
            showToast('Post not found', 'error');
        }
    });

    // Delete post
    $('#posts-tbody').on('click', '.delete-post-btn', function() {
        const postId = $(this).data('post-id');
        console.log('Deleting post ID:', postId);

        Swal.fire({
            title: "Do you want to delete this post?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {

            if (result.isConfirmed) {

                let formData = new FormData();
                formData.append('_method', 'DELETE');
                $.ajax({
                    url: `/course-work/Admin/DeletePost/${postId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);
                        if (response.success) {
                            Swal.fire(response.message);

                            fetchPosts(currentPage);
                        } else {
                            Swal.fire(response.message);

                        }
                    },
                });
            }

        })


    });

    // Close modal
    $('#closePostModal, #cancelPostBtn').on('click', function() {
        $('#postModal').addClass('hidden');
        $('#postForm')[0].reset();
        $('#tagsStore').addClass('hidden');
        $('#postImageUrl').attr('src', '').addClass('hidden');
        $('#dropzone').removeClass('hidden');
        $('#uploadImage').prop('disabled', true).removeClass('!bg-[#2463EB]');
        $('#cancelImage').addClass('hidden');
        $('#textImage').text('Upload');
        checkedValues = [];
        fetchTagsDropDown();
        isEditing = false;
        editingPostId = null;
        uploadedImageUrl = '';
        updateTagDropdown(tags);
    });

    // Submit post form
    $('#savePostbtn').on('click', function(e) {
        e.preventDefault();

        const postName = $('#postName').val().trim();
        const postImage = uploadedImageUrl || undefined;
        const postUrl = $('#postUrl').val().trim();
        const description = $('#postDescription').val().trim();
        const tagIds = checkedValues.map(tag => tag.id);
        const userId = <?php echo json_encode($_SESSION['user']['id'] ?? null); ?>;

        // Validation
        if (!postName || !description || tagIds.length < 1 || !postImage) {
            showToast('All fields are required.', 'error');
            return;
        }


        let formData = new FormData();
        formData.append('postName', postName);
        if (postImage) formData.append('postImage', postImage);
        if (postUrl) formData.append('postUrl', postUrl);
        formData.append('description', description);
        formData.append('userId', userId);
        tagIds.forEach((tagId, index) => {
            formData.append(`tagIds[${index}]`, tagId);
        });

        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        const url = isEditing ? `/course-work/Admin/UpdatePost/${editingPostId}` :
            '/course-work/Home/CreatePosts';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    showToast(isEditing ? 'Post updated successfully' :
                        'Post added successfully', 'success');
                    $('#postModal').addClass('hidden');
                    $('#postForm')[0].reset();
                    $('#tagsStore').addClass('hidden');
                    $('#postImageUrl').attr('src', '').addClass('hidden');
                    $('#dropzone').removeClass('hidden');
                    $('#uploadImage').prop('disabled', true).removeClass('!bg-[#2463EB]');
                    $('#cancelImage').addClass('hidden');
                    $('#textImage').text('Upload');
                    checkedValues = [];
                    fetchTagsDropDown();
                    fetchPosts(currentPage);
                    uploadedImageUrl = '';
                } else {
                    showToast(response.message || (isEditing ? 'Post update failed' :
                        'Post add failed'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
                showToast('Failed to save post: ' + (xhr.responseJSON?.message || error),
                    'error');
            }
        });
    });

    // Ensure dropdown stays open during search
    $('#dropdownSearch').on('click', function(e) {
        e.stopPropagation();
    });

    // Toggle dropdown
    // $('#dropdownSearchButton').on('click', function() {
    //     $('#dropdownSearch').toggleClass('hidden');
    // });

    // Close dropdown when clicking outside
    // $(document).on('click', function(e) {
    //     if (!$(e.target).closest('#dropdownSearch, #dropdownSearchButton').length) {
    //         $('#dropdownSearch').addClass('hidden');
    //     }
    // });
});
</script>