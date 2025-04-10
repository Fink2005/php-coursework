<div class="space-y-10">
    <h1>All Posts</h1>
    <a class="bg-red-500 rounded-xl py-1 px-4 size-10" href="/course-work/Create/createPost">
        + New Post
    </a>
    <a class="bg-green-500 rounded-xl py-1 px-4 size-10" href="/course-work/Auth/signIn">
        Sign in
    </a>


    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="image" id="imageInput" accept="image/*" required>
        <button class="bg-blue-500 rounded-xl py-1 px-4 " type="submit">Upload</button>
    </form>

    <div id="result"></div>


    <?php 
    include "./mvc/views/components/Dialog.php"
    ?>
    <?php foreach ($data["allPosts"] as $post): ?>
    <div data-id="<?php echo $post['id']; ?>" data-modal-target="default-modal" data-modal-toggle="default-modal"
        class="display-post-detail">
        <h2><?php echo htmlspecialchars($post['name']); ?></h2>
        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post image">
        <p><?php echo htmlspecialchars($post['des']); ?></p>
        <p>By: <?php echo htmlspecialchars($post['username']); ?></p>
        <a href="/posts/show/<?php echo $post['id']; ?>">Read More</a>
    </div>
    <?php endforeach; ?>


    <script>
    $(document).ready(function() {
        $('#imageInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                console.log(reader)
                reader.onload = function(event) {
                    $('#result').html(
                        '<h2>Image Preview:</h2>' +
                        '<img src="' + event.target.result +
                        '" alt="Local Preview" style="max-width: 300px;">'
                    );
                };
                reader.readAsDataURL(file); // Convert file to base64 URL for preview
            } else {
                $('#result').html(''); // Clear preview if no file selected
            }
        });
        $(".display-post-detail").click(function() {
            let postId = $(this).data("id"); // Correct way to get post ID
            console.log("Post ID:", postId);

            $.ajax({
                url: "./Ajax/getPostId",
                type: "POST",
                data: {
                    id: postId
                },
                success: function(response) {
                    let post = JSON.parse(response);
                    console.log(post)
                    $("#titleDetail").text(post.name);
                    $("#desDetail").text(post.des);
                    $("#imageDetail").attr("src", post.image_url);
                    // You can update a modal/dialog with the response
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                }
            });

        });

        $('#uploadForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            console.log(this)
            let formData = new FormData(this); // Create FormData object with form data
            console.log(formData)
            $.ajax({
                url: './Ajax/upload', // Your upload endpoint
                type: 'POST',
                data: formData,
                processData: false, // Tell jQuery not to process the data
                contentType: false, // Tell jQuery not to set content type
                success: function(response) {
                    // Assuming the response is JSON with the image URL
                    let data = JSON.parse(response);
                    console.log(data)
                    if (data.success) {
                        $('#result').html(
                            '<h2>Image Uploaded!</h2>' +
                            '<img src="' + data.image_url.url +
                            '" alt="Uploaded Image" style="max-width: 300px;">'
                        );
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
</div>