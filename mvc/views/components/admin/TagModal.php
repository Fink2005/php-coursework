<div id="tagModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="tagModalTitle" class="text-lg text-black font-semibold">Add Tag</h3>
            <button id="closeTagModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="tagForm">
                <div class="mb-4">
                    <label for="tagName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="tagName"
                        class="w-full text-black px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="tagDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="tagDescription" rows="3"
                        class="w-full text-black px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelTagBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 focus:outline-none">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
const createPostUrl = window.location.href === 'http://localhost/course-work/Create/CreatePosts' ? true : false

$('#addtagBtn').on('click', function() {
    isEditing = false;
    editingTagId = null;
    $('#tagModalTitle').text('Add Tag');
    $('#tagForm')[0].reset();
    $('#tagModal').removeClass('hidden');
});

$('#closeTagModal').on('click', function() {
    $('#tagModal').addClass('hidden');
    $('#tagForm')[0].reset();
    isEditing = false;
    editingTagId = null;
});

// Cancel button
$('#cancelTagBtn').on('click', function() {
    $('#tagModal').addClass('hidden');
    $('#tagForm')[0].reset();
    isEditing = false;
    editingTagId = null;
});


$('#tagForm').on('submit', function(e) {
    e.preventDefault();

    const name = $('#tagName').val().trim();
    const description = $('#tagDescription').val().trim();
    const userId = <?php echo json_encode($_SESSION['user']['id'] ?? null) ?>;


    // Client-side validation
    // if (!name) {
    //     showToast('Name is required', 'error');
    //     return;
    // }

    let formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('userId', userId);
    if (isEditing) {
        formData.append('_method', 'PUT');
    }

    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    const url = isEditing ? `/course-work/Admin/UpdateTag/${editingTagId}` :
        '/course-work/Admin/CreateTag';
    const method = 'POST';

    $.ajax({
        url: url,
        type: method,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(isEditing ? 'Tag updated successfully' :
                    'Tag created successfully', 'success');
                $('#tagModal').addClass('hidden');
                $('#tagForm')[0].reset();
                isEditing = false;
                editingTagId = null;
                !createPostUrl ? fetchTags(currentPage) : fetchTagsDropDown()
            } else {
                showToast(response.message || (isEditing ? 'Tag update failed' :
                    'Tag creation failed'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Status:', status);
            console.error('Response Text:', xhr.responseText);
            showToast('Failed to save tag: ' + (xhr.responseJSON?.message || error),
                'error');
        }
    });

});
</script>