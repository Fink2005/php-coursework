<section id="tags" class="content-section">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Tags Management</h3>
            <button id="addtagBtn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Add tag
            </button>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
            <div class="relative w-full md:w-64">
                <input type="text" id="tagSearch" placeholder="Search tags..."
                    class="w-full py-2 pl-10 pr-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <!-- <div class="flex space-x-2 w-full md:w-auto">
                <select
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="tag">tag</option>
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
                            Id</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            name</th>
                        <th
                            class="px-6 py-3 text-nowrap text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User's tag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created at</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="tags-tbody" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            <div class="flex justify-between items-center mt-6">
                <div id="currentPage" class="text-sm text-gray-500"></div>
                <div id="pagination" class="flex space-x-1"></div>
            </div>
        </div>
    </div>
    <?php 
         include "mvc/views/components/admin/tagModal.php";
           ?>



</section>


<script>
let currentPage = 1;
let isEditing = false;
let editingTagId = null;

const fetchTags = (page = 1) => {
    $('#tags-tbody').html('<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>');

    $.ajax({
        url: `/course-work/Admin/Tags/${page}`,
        type: 'GET',
        dataType: "json",
        success: function(response) {
            const tags = response.tags || [];
            currentPage = parseInt(response.currentPage) || 1;
            const totalPages = response.totalPages || 1;

            const currentTagPage =
                `<p>Showing ${response.currentPage} to ${response.totalPages} of ${response.totalPages} entries</p>`;

            let paginationButtons = '';
            for (let i = 1; i <= totalPages; i++) {
                const isActive = i === currentPage ? 'bg-blue-500 text-white' :
                    'bg-gray-200 text-gray-600';
                paginationButtons +=
                    `<button id="pagination${i}" class="px-3 py-1 rounded-md ${isActive} hover:bg-gray-300">${i}</button>`;
            }

            const tagPagination = `
                    <button id="previous" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage <= 1 ? 'disabled' : ''}>Previous</button>
                    ${paginationButtons}
                    <button id="next" class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300" ${currentPage >= totalPages ? 'disabled' : ''}>Next</button>
                `;

            let html = '';
            if (tags.length === 0) {
                html =
                    '<tr><td colspan="6" class="text-center py-4">No tags found</td></tr>';
            } else {
                tags.forEach(tag => {
                    const formattedDate = dateFns.format(dateFns.parseISO(tag
                        .created_at), 'MMM dd, yyyy');
                    html += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${tag.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[200px]"><p>${tag.name}</p></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[200px]"><p>${tag.user_name}</p></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[200px]"><p>${tag.description || ''}</p></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="created-date px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${formattedDate}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button data-tag-id="${tag.id}" class="text-blue-600 hover:text-blue-900 mr-3 edit-tag-btn"><i class="fas fa-edit"></i></button>
                                    <button data-tag-id="${tag.id}" class="text-red-600 hover:text-red-900 delete-tag-btn"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                });
            }

            $('#tags-tbody').html(html);
            $('#currentPage').html(currentTagPage);
            $('#pagination').html(tagPagination);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching tags:', error);
            console.error('Status:', status);
            console.error('Response Text:', xhr.responseText);
            showToast('Failed to load tags', 'error');
        }
    });
};

// Pagination handlers
$('#pagination').on('click', '#previous', function() {
    if (currentPage > 1) {
        fetchTags(currentPage - 1);
    }
});

$('#pagination').on('click', '#next', function() {
    fetchTags(currentPage + 1);
});

$('#pagination').on('click', '[id^="pagination"]', function() {
    const page = parseInt($(this).attr('id').replace('pagination', ''));
    fetchTags(page);
});

// Add tag button
// $('#addtagBtn').on('click', function() {
//     isEditing = false;
//     editingTagId = null;
//     $('#tagModalTitle').text('Add Tag');
//     $('#tagForm')[0].reset();
//     $('#tagModal').removeClass('hidden');
// });

//   $('#closeTagModal').on('click', function() {
//     $('#tagModal').addClass('hidden');
//     $('#tagForm')[0].reset();
//     isEditing = false;
//     editingTagId = null;
// });

// // Cancel button
// $('#cancelTagBtn').on('click', function() {
//     $('#tagModal').addClass('hidden');
//     $('#tagForm')[0].reset();
//     isEditing = false;
//     editingTagId = null;
// });

// Edit tag button
$('#tags-tbody').on('click', '.edit-tag-btn', function() {
    isEditing = true;
    editingTagId = $(this).data('tag-id');
    console.log('Editing tag ID:', editingTagId);

    $.ajax({
        url: `/course-work/Admin/GetTag/${editingTagId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('GetTag response:', response);
            const tag = response.tag;
            if (tag) {
                $('#tagName').val(tag.name);
                $('#tagSlug').val(''); // Ignore slug
                $('#tagDescription').val(tag.description || '');
                $('#tagModalTitle').text('Edit Tag');
                $('#tagModal').removeClass('hidden');
            } else {
                showToast('Tag not found', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching tag:', error);
            console.error('Status:', status);
            console.error('Response Text:', xhr.responseText);
            showToast('Failed to load tag: ' + (xhr.responseJSON?.message || error),
                'error');
        }
    });
});

// Delete tag button
$('#tags-tbody').on('click', '.delete-tag-btn', function() {
    const tagId = $(this).data('tag-id');
    console.log('Deleting tag ID:', tagId);



    Swal.fire({
        title: "Do you want to delete this tag?",
        showDenyButton: true,
        confirmButtonText: "Yes",
        denyButtonText: `No`
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('_method', 'DELETE');

            console.log('FormData contents:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            $.ajax({

                url: `/course-work/Admin/DeleteTag/${tagId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        Swal.fire(response.message);
                        fetchTags(currentPage);
                    } else {
                        Swal.fire(response.message);

                    }
                },

            });

        }
    })



});

// Close modal (X button)


// Save tag (create or update)
// $('#tagForm').on('submit', function(e) {
//     e.preventDefault();

//     const name = $('#tagName').val().trim();
//     const description = $('#tagDescription').val().trim();
//     const userId = 


//     // Client-side validation
//     if (!name) {
//         showToast('Name is required', 'error');
//         return;
//     }

//     let formData = new FormData();
//     formData.append('name', name);
//     formData.append('description', description);
//     formData.append('userId', userId);
//     if (isEditing) {
//         formData.append('_method', 'PUT');
//     }

//     for (let [key, value] of formData.entries()) {
//         console.log(`${key}: ${value}`);
//     }

//     const url = isEditing ? `/course-work/Admin/UpdateTag/${editingTagId}` :
//         '/course-work/Admin/CreateTag';
//     const method = 'POST';

//     $.ajax({
//         url: url,
//         type: method,
//         data: formData,
//         processData: false,
//         contentType: false,
//         dataType: 'json',
//         success: function(response) {
//             if (response.success) {
//                 showToast(isEditing ? 'Tag updated successfully' :
//                     'Tag created successfully', 'success');
//                 $('#tagModal').addClass('hidden');
//                 $('#tagForm')[0].reset();
//                 isEditing = false;
//                 editingTagId = null;
//                 fetchTags(currentPage);
//             } else {
//                 showToast(response.message || (isEditing ? 'Tag update failed' :
//                     'Tag creation failed'), 'error');
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('Error:', error);
//             console.error('Status:', status);
//             console.error('Response Text:', xhr.responseText);
//             showToast('Failed to save tag: ' + (xhr.responseJSON?.message || error),
//                 'error');
//         }
//     });
// });

// Initial fetch
fetchTags();
</script>