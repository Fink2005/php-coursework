<section class="w-full flex justify-center">
    <div class="w-full max-w-3xl text-gray-200 rounded-lg overflow-hidden shadow-xl bg-[#1e1f23]">
        <!-- Tabs -->
        <div class="border-b border-gray-700 px-4">
            <div class="flex">
                <button class="py-4 px-4 relative text-white font-medium">
                    New post
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-white rounded-t-md"></div>
                </button>
            </div>
        </div>

        <!-- Post Form -->
        <div class="p-4 space-y-4">
            <!-- Thumbnail -->
            <div class="flex space-x-3">
                <label for="imageInput"
                    class="bg-[#1e1f23] hover:bg-[#2a2b30] rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer border-dashed border-2 border-gray-600 size-32 hover:border-gray-500">
                    <i class="fa-solid fa-upload"></i>
                    <input type="file" accept="image/*" name="image" id="imageInput" class="hidden" />
                </label>
                <div class="relative">
                    <div id="preview"
                        class="bg-[#1e1f23] rounded-lg flex justify-center items-center overflow-hidden size-32 space-y-1">
                        <div id="thumbnail" class="flex flex-col items-center justify-center h-full w-full">
                            <i class="fa-solid fa-camera"></i>
                            <span class="text-gray-300">Thumbnail</span>
                        </div>
                        <img id="result" class="hidden w-full h-full object-cover" alt="Image Preview">
                        <div id="removePreview" class="absolute hidden -top-3 -right-3">
                            <button id="remove"
                                class="flex justify-center items-center size-8 bg-white rounded-full text-black p-1">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Title -->
            <input type="text"
                class="w-full bg-[#1e1f23] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-600"
                id="postName" placeholder="Post Title*">

            <!-- Post URL -->
            <input type="text"
                class="w-full bg-[#1e1f23] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-600"
                id="postUrl" placeholder="Enter URL">

            <!-- Tag Dropdown -->
            <?php include_once 'mvc/views/components/DropDownSearch.php'; ?>

            <div id="tagsStore" class="hidden border-2 ps-4 p-1 rounded-lg flex flex-wrap"></div>

            <!-- Write/Preview Tabs -->
            <div class="border border-gray-700 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center px-4 py-2 border-b border-gray-700">
                    <div class="flex">
                        <button class="py-2 px-4 relative text-white font-medium">
                            Write
                            <div class="absolute bottom-0 left-0 w-full h-1 bg-white rounded-t-md"></div>
                        </button>
                    </div>

                </div>

                <!-- Text Area -->
                <textarea class="w-full bg-[#1e1f23] text-white p-4 min-h-[250px] focus:outline-none resize-none"
                    id="postDescription" placeholder="Write your post here..."></textarea>

                <!-- Character Count -->
                <div class="flex justify-end p-2 text-gray-400"></div>
            </div>

            <!-- Toolbar -->
            <div class="flex justify-between items-center">
                <div class="flex space-x-4">
                    <button class="text-gray-400 hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Post Button -->
            <div class="flex justify-end">
                <button type="button"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-3 px-8 rounded-full"
                    id="uploadPost">
                    <span id="postText">Post</span>
                    <span class="hidden" id="loading">
                        <?php include 'mvc/views/components/Spinner.php'; ?>
                    </span>
                </button>
            </div>
        </div>
        <?php include_once 'mvc/views/components/admin/TagModal.php'; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // Image Preview
    $('#imageInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            $('#thumbnail').addClass('hidden');
            $('#removePreview').removeClass('hidden');
            reader.onload = function(event) {
                $('#result').attr('src', event.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            $('#result').attr('src', '').addClass('hidden');
            $('#thumbnail').removeClass('hidden');
            $('#removePreview').addClass('hidden');
        }
    });

    // Remove Image
    $("#remove").click(function() {
        $('#imageInput').val('');
        $('#result').attr('src', '').addClass('hidden');
        $('#thumbnail').removeClass('hidden');
        $('#removePreview').addClass('hidden');
    });

    // Post Submission
    $('#uploadPost').click(async function() {
        // Collect form data
        const file = $('#imageInput')[0].files[0];
        const postName = $('#postName').val().trim();
        const postUrl = $('#postUrl').val().trim();
        const description = $('#postDescription').val().trim();
        const tagIds = checkedValues ? checkedValues.map(tag => tag.id) : [];
        const userId = <?php echo json_encode($_SESSION['user']['id'] ?? null); ?>;

        // Validate inputs
        const isValidation = checkValidation('post', null, postName, null,
            description,
            file || $('#result').attr('src'), tagIds, null)

        if (!isValidation) {
            $('#postText').removeClass('hidden');
            $('#loading').addClass('hidden');
            $("#uploadPost").attr("disabled", false);
            return;
        }

        // Disable button and show loader
        $("#uploadPost").attr("disabled", true);
        $('#postText').addClass('hidden');
        $('#loading')
            .removeClass('hidden');

        // Upload image
        let formData = new FormData();
        if (file) {
            formData.append('file', file);
        }


        const isSuccess = await handleUploadImage(formData)
        console.log('isSuccess', isSuccess)

        if (!isSuccess) {
            $('#postText').removeClass('hidden');
            $('#loading').addClass('hidden');
            $("#uploadPost").attr("disabled", false);

        }

        const postImage = isSuccess;
        console.log(postImage)
        let postFormData = new FormData();
        postFormData.append('postName', postName);
        postFormData.append('postImage', postImage);
        if (postUrl) postFormData.append('postUrl', postUrl);
        postFormData.append('description', description);
        postFormData.append('userId', userId);
        tagIds.forEach((tagId, index) => {
            postFormData.append(`tagIds[${index}]`, tagId);
        });

        // Create post
        $.ajax({
            url: '/course-work/Home/CreatePosts',
            type: 'POST',
            data: postFormData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast('Post added successfully', 'success');
                    $('#tagsStore').removeClass('hidden');
                    $('#loading').addClass('hidden');
                    $("#uploadPost").attr("disabled", false);

                    // Reset form
                    $('#imageInput').val('');
                    $('#result').attr('src', '').addClass('hidden');
                    $('#thumbnail').removeClass('hidden');
                    $('#removePreview').addClass('hidden');
                    $('#postName').val('');
                    $('#postUrl').val('');
                    $('#postDescription').val('');
                    checkedValues = [];
                } else {
                    showToast(response.message || 'Post add failed',
                        'error');
                    $('#postText').removeClass('hidden');
                    $('#loading').addClass('hidden');
                    $("#uploadPost").attr("disabled", false);
                }
            },
            error: function() {
                showToast('Error creating post', 'error');
                $('#postText').removeClass('hidden');
                $('#loading').addClass('hidden');
                $("#uploadPost").attr("disabled", false);
            }
        });

    });
});
</script>