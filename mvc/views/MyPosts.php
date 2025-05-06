<?php
// Assuming this is a PHP view file that includes the HTML and JavaScript
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Display</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #1a1a1a;
        }
    </style>
</head>
<body>
    <section>
        <div class="flex justify-center w-full">
            <div id="targetBorder" class="md:min-w-[700px] min-h-[600px] rounded-md p-10 ">
                <!-- Include the modal -->
                <?php include "mvc/views/components/admin/postModal.php"; ?>
                <!-- Posts container -->
                <div id="posts-container"></div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Toast notification function
  

            // Function to fetch and display posts
            function loadPosts() {
                $.ajax({
                    url: '/course-work/Home/UserPosts',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const posts = response.data;
                            
                            $('#targetBorder').addClass('border')
                            const $container = $('#posts-container');
                            $container.empty();

                            if (posts.length === 0) {
                                $container.html('<p class="text-white text-center">No posts found for this user.</p>');
                                return;
                            }

                            posts.forEach(function(post) {
                                const avatarUrl = post.avatar ? 
                                    $('<div>').text(post.avatar).html() : 
                                    `https://ui-avatars.com/api/?name=${encodeURIComponent(post.username)}`;
                                const imageUrl = $('<div>').text(post.image_url).html();
                                const postName = $('<div>').text(post.name).html();
                                const totalVotes = parseInt(post.total_votes) || 0;

                                const postHtml = `
                                    <div class="flex justify-between items-center">
                                        <div class="relative my-5 flex items-center space-x-2">
                                            <div>
                                                <img class="absolute top-8 -left-2 size-8 rounded-full"
                                                    src="${avatarUrl}" alt="User avatar">
                                                <img class="object-cover w-44 h-24 rounded-xl"
                                                    src="${imageUrl}" alt="Post image">
                                            </div>
                                            <div>
                                                <p class="text-sm text-white font-semibold">${postName}</p>
                                                <p class="text-xs text-[#A8B3CF] font-light">${totalVotes} upvotes</p>
                                            </div>
                                        </div>
                                        <div class="space-x-2">
                                          
                                            <i class="fa-solid fa-trash text-red-500 text-xl cursor-pointer delete-post" data-post-id="${post.id}"></i>
                                        </div>
                                    </div>
                                `;
                                $container.append(postHtml);
                            });

                            // Attach click handlers
             
                            $('.delete-post').on('click', function() {
                                const postId = $(this).data('post-id');
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

                            loadPosts()
                        } else {
                            Swal.fire(response.message);

                        }
                    },
                });
            }

        })
                            });
                        } else {
                            $('#posts-container').html('<p class="text-white text-center">' + response.message + '</p>');
                        }
                    },
                    error: function(xhr) {
                        $('#targetBorder').removeClass('border')

                        $('#posts-container').html('<p class="text-blue-500 text-center">Add your new post</p>');
                    }
                });
            }

            // Modal cancel button
            $('#cancelBtn').on('click', function() {
                $('#postModal').addClass('hidden');
                $('#postForm')[0].reset();
            });

            // Form submission for updating post
            $('#postForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                $.ajax({
                    url: '/course-work/Home/UpdatePost',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showToast('Post updated successfully', 'success');
                            $('#postModal').addClass('hidden');
                            $('#postForm')[0].reset();
                            loadPosts();
                        } else {
                            showToast(response.message || 'Failed to update post', 'error');
                        }
                    },
                    error: function(xhr) {
                        showToast('Error updating post: ' + (xhr.responseJSON?.message || 'Server error'), 'error');
                    }
                });
            });

            // Initial load
            loadPosts();
        });
    </script>
</body>
</html>