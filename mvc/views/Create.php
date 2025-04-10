<div class="w-full max-w-3xl  text-gray-200 rounded-lg overflow-hidden shadow-xl">
    <!-- Tabs -->
    <div class="border-b border-gray-700 px-4">
        <div class="flex">
            <button class="py-4 px-4 relative text-white font-medium">
                New post
                <div class="absolute bottom-0 left-0 w-full h-1 bg-white rounded-t-md"></div>
            </button>
            <!-- <button class="py-4 px-4 text-gray-400">
                Share a link
            </button> -->
        </div>
    </div>

    <!-- Post Form -->
    <div class="p-4 space-y-4">
        <!-- Squad Selector -->


        <!-- Thumbnail -->
        <div class="flex space-x-3">
            <div class="bg-[#1e1f23] hover:bg-[#2a2b30] rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer border-dashed border-2 border-gray-600 size-32 hover:border-gray-500"
                id="myDropzone">
                <i class="fa-solid fa-upload"></i>
            </div>
            <div class="relative">
                <div id="preview"
                    class="bg-[#1e1f23] rounded-lg flex justify-center items-center  overflow-hidden size-32 space-y-1">
                    <div id="thumbnail" class="flex flex-col items-center justify-center h-full w-full">
                        <i class="fa-solid fa-camera"></i>
                        <span class="text-gray-300">Thumbnail</span>
                    </div>
                    <img id="result" class="hidden" alt="Image Preview" class="w-full h-full object-cover">
                    <div id="removePreview" class="absolute hidden -top-3 -right-3 ">
                        <button id="remove"
                            class="flex justify-center items-center  size-8 bg-white rounded-full text-black p-1">
                            <i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
            </div>

            <input type="file" accept="image/*" name="image" id="imageInput" class="hidden" />
        </div>

        <!-- Post Title -->
        <input type="text"
            class="w-full bg-[#1e1f23] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-600"
            id="name" placeholder="Post Title*">

        <input type="text"
            class="w-full bg-[#1e1f23] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-600"
            id="post_url" placeholder="Enter url">


        <!-- Write/Preview Tabs -->
        <div class="border border-gray-700 rounded-lg overflow-hidden">
            <div class="flex justify-between items-center px-4 py-2 border-b border-gray-700">
                <div class="flex">
                    <button class="py-2 px-4 relative text-white font-medium">
                        Write
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-white rounded-t-md"></div>
                    </button>

                </div>
                <div class="flex items-center text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-300" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Saved</span>
                </div>
            </div>

            <!-- Text Area -->
            <textarea class="w-full bg-[#1e1f23] text-white p-4 min-h-[300px] focus:outline-none resize-none" id="des"
                placeholder="Write your post here..."></textarea>

            <!-- Character Count -->
            <div class="flex justify-end p-2 text-gray-400"></div>
        </div>

        <!-- Toolbar -->
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <!-- <button class="text-gray-400 hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    </svg>
                </button> -->
                <!-- <span class="text-gray-400">Attach images by dragging & dropping</span> -->
            </div>
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
                class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-3 px-8 rounded-full" id="uploadPost">
                Post
            </button>
        </div>
    </div>
    <button class="bg-green-500 rounded-xl py-1 px-4 size-10" id="showToast">
        show toast
    </button>
</div>


<script>
// Disable Dropzone auto-discovery (we’ll initialize it manually)
Dropzone.autoDiscover = false;

$(document).ready(function() {
    $("#myDropzone").click(function() {
        $('#imageInput').click();
    });
    $('#imageInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            $('#thumbnail').addClass('hidden'); // Hide thumbnail icon
            $('#removePreview').removeClass('hidden'); // Show remove button
            reader.onload = function(event) {
                $('#result').attr('src', event.target.result).removeClass('hidden'); // Show preview
            };
            reader.readAsDataURL(file); // Convert file to base64 URL for preview

        } else {
            $('#result').html(''); // Clear preview if no file selected
        }
    });


    $("#remove").click(function() {
        $('#imageInput').val(''); // Clear the file input
        $('#result').addClass('hidden'); // Hide the preview
        $('#thumbnail').removeClass('hidden'); // Show thumbnail icon
        $('#removePreview').addClass('hidden'); // Hide remove button
    });

    $('#uploadPost').click(function() {
        const file = $('#imageInput')[0].files[0];
        let formData = new FormData(); // Create empty FormData
        formData.append('file', file);
        console.log(file)
        $("#uploadPost").attr("disabled", true); // Disable the button
        $.ajax({
            url: '/course-work/Ajax/upload', // Your upload endpoint
            type: 'POST',
            data: formData,
            processData: false, // Tell jQuery not to process the data
            contentType: false, // Tell jQuery not to set content type
            success: function(response) {
                // Assuming the response is JSON with the image URL
                console.log(response)
                console.log(response.success)
                if (response.success) {
                    $.ajax({
                        url: '/course-work/Create/createPost', // Your upload endpoint
                        type: 'POST',
                        data: {
                            name: $('#name').val(),
                            image_url: response.image_url.url,
                            post_url: $('#post_url').val(),
                            des: $('#des').val(),
                            user_id: 1 // Replace with actual user ID
                        },
                        success: function(response) {
                            Toastify({
                                text: "Upload successfully",
                                className: "bg-blue-500 rounded-lg",
                                duration: 3000,
                                gravity: "top", // `top` or `bottom`
                                position: "center",

                            }).showToast();
                            $("#uploadPost").attr("disabled",
                                false); // Disable the button

                            console.log(response)
                            // Handle success response
                        },
                        error: function(xhr, status, error) {
                            Toastify({
                                text: "upload fail",
                                className: "bg-blue-500 rounded-lg",
                                duration: 3000,
                                gravity: "top", // `top` or `bottom`
                                position: "center",

                            }).showToast();
                            console.log('Error:', error);
                        }
                    })
                } else {
                    $('#result').html('<p>Error: ' + data.message + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.log('loi')
                $('#result').html('<p>Upload failed: ' + error + '</p>');
            }
        });
    });


});
</script>