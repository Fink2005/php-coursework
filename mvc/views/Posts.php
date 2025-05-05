<section class="p-10">

    <div class="grid lg:grid-cols-3 2xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
        <?php 



    include "mvc/views/components/client/PostModalDetail.php"
    ?>
        <?php foreach ($data["allPosts"] as $post): ?>
        <div data-id="<?php echo $post['id']; ?>" data-modal-target="default-modal" data-modal-toggle="default-modal"
            class="display-post-detail group space-y-3 w-[340px] border border-[#383D48] h-[400px] rounded-xl p-3 m-4 bg-[#1E2125] text-white flex flex-col justify-between cursor-pointer">
            <div>
                <div class="flex w-full justify-between items-center h-10">
                    <img class="size-8 rounded-full"
                        src="<?php echo htmlspecialchars($post['avatar'] ?$post['avatar'] :   'https://ui-avatars.com/api/?name=' . $post['username']  ); ?>"
                        alt="">

                    <a target="_blank" href="<?php echo $post['post_url']; ?>"
                        class=" rounded-lg text-black bg-white cursor-pointer select-none flex-row
        items-center border no-underline shadow-none transition
        duration-200 ease-in-out typo-callout justify-center font-bold h-8 px-3 rounded-10 hidden group-hover:inline-flex mr-2" data-modal-hide="default-modal">Read
                        post <?php include './mvc/views/components/icons/NewLink.php' ?></a>
                </div>

                <!-- <p class="text-white">
                    <?php print_r($post) ?>

                </p> -->
                <h2 class="text-lg font-bold"><?php echo htmlspecialchars($post['name']); ?></h2>


                <?php if (!empty($post['tags']) && is_array($post['tags'])): ?>
                <div class="flex flex-wrap gap-2 my-1">
                    <?php
                $tagCount = count($post['tags']);
                $displayTags = array_slice($post['tags'], 0, 3); // Take first 3 tags
                foreach ($displayTags as $tag):
                    if ($tag !== null && is_string($tag) && $tag !== ''):
                ?>
                    <span
                        class="inline-block text-[#6C7386] border border-[#6C7386] text-white text-xs px-2 py-1 rounded-lg">
                        #<?php echo htmlspecialchars($tag); ?>
                    </span>
                    <?php
                    endif;
                endforeach;
                // Show +N if more than 3 tags
                if ($tagCount > 3):
                ?>
                    <span
                        class="inline-block text-[#6C7386] border border-[#6C7386] text-white text-xs px-2 py-1 rounded-lg">
                        +<?php echo $tagCount - 3; ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>


                <p> <span class="post-created-at text-[#A8B3CF] text-xs font-light"
                        data-created-at="<?php echo htmlspecialchars($post['created_at']); ?>"></span></p>
            </div>

            <div class="space-y-3">
                <img class="w-full object-cover h-40 rounded-xl"
                    src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post image">
                <?php 
                    include 'mvc/views/components/client/CardController.php'
                    ?>
            </div>
        </div>

        <?php endforeach; ?>



    </div>
    <script>
    let currentPage = 1;
    let postId = null
    $(document).ready(function() {
        $('.post-created-at').each(function() {
            const createdAt = $(this).data('created-at');
            if (createdAt) {
                const date = formattedDate(createdAt)
                $(this).text(date);
            }
        });

        $(".display-post-detail").click(function() {
            postId = $(this).data("id");

            $.ajax({
                url: "/course-work/Home/getPostId",
                type: "POST",
                data: {
                    id: postId
                },
                success: function(response) {
                    let post = JSON.parse(response);
                    console.log(post)
                    const date = formattedDate(post.created_at)
                    $(this).text(formattedDate);
                    $("#titleDetail").text(post.name);
                    $("#desDetail").text(post.des);
                    $("#imageDetail").attr("src", post.image_url);
                    $("#createdDetail").text(date);
                    $("#vote-count-detail").text(post.upvotes - post.downvotes);


                    // if (post.user_vote_type === '#') {
                    //     $('#upvote-btn-detail').addClass('text-green-500');
                    // }




                    let tags = ''
                    post.tags.forEach((tag) => {
                        tags += `<span
                        class="inline-block text-[#6C7386] border border-[#6C7386] text-white text-xs px-2 py-1 rounded-lg">
                     
                          #${tag}</span>`
                    })
                    $("#tagsDetail").html(tags);

                    loadComments(postId, 1);

                },

            });

        });




        function loadComments(postId, page) {
            $.ajax({
                url: '/course-work/Home/getComments/' + page,
                type: 'POST',
                data: {
                    id: postId
                },
                success: function(response) {
                    try {
                        const res = typeof response === 'string' ?
                            JSON.parse(response) : response;
                        if (res.status === 'success') {
                            console.log(res)
                            renderComments(res.data.comments);
                            console.log('res', res)
                            // updatePagination(res.data.pagination);
                            currentPage = page;
                        } else {
                            console.log('fail', response)
                            $("#comments-container").html(
                                '<p class="text-gray-500">Be the first to comment.</p>'
                            );
                            $("#pagination-controls").hide();
                        }
                    } catch (e) {
                        $("#comments-container").html(
                            '<p class="text-red-500">Error loading comments.</p>'
                        );
                    }
                },
                error: function() {
                    $("#comments-container").html(
                        '<p class="text-red-500">Error loading comments.</p>'
                    );
                }
            });
        }

        $("#comment-submit").click(function() {
            const content = $("#comment-input").val().trim();
            const image_url = $("#previewImg").attr('src') ?? ''

            const filterComment = content.replace(/url\([^)]+\)/g, '');
            if (!filterComment) {
                showToast("Please enter a comment.", 'error');
                return;
            }

            $.ajax({
                url: '/course-work/Home/comment/' + postId,
                type: 'POST',
                dataType: 'json',
                data: {
                    data: {
                        content: filterComment,
                        image_url
                    }
                },
                success: function(response) {
                    const res = typeof response === 'string' ? JSON.parse(response) :
                        response;
                    if (res.success) {
                        $("#comment-input").val(''); // Clear input
                        loadComments(postId, currentPage); // Refresh comments
                    } else {
                        alert('Error processing res.');
                    }

                },
            });
        });

        // Render comments in the container
        function renderComments(comments) {
            if (comments.length === 0) {
                $("#comments-container").html(
                    '<p class="text-gray-500 text-center">Be the first to comment.</p>');
                return;
            }

            let html = '';
            comments.forEach(comment => {
                html += `
                <div class="border border-[#2D323C] rounded-xl w-[90%] rounded-lg p-4 mb-2">
                    <div class="mr-3 flex items-center space-x-1">
                        <div class="size-9 rounded-xl overflow-hidden">
                            <img src="${comment.avatar ? comment.avatar : `https://ui-avatars.com/api/?name=${comment.username}`}" class="size-full object-cover" alt="${comment.username}">
                        </div>
                       <div>
                             <p class="text-white font-medium">${comment.username}</p>
                        <p class="text-gray-500 text-sm">${formattedDate(comment.created_at,'formatDistanceToNow')}</p>
                       
                       </div>
                        
                    </div>
                        <p class="text-gray-300 mt-2">${comment.content}</p>

                ${comment.image_url ? `<img src="${comment.image_url}" class="mt-2 size-28 rounded-lg" alt="Comment image">` : ''}
                </div>
            `;
            });
            $("#comments-container").html(html);
            $("#pagination-controls").show();
        }


        $('.upvote-btn').click(function() {
            const postId = $(this).data('post-id');
            $.post(`/course-work/Home/vote/${postId}/upvote`, function(response) {
                if (response.success) {
                    console.log(response)

                    $(`.vote-count[data-post-id=${postId}]`).text(response.vote_count.upvotes -
                        response.vote_count.downvotes);
                    const upvoteIcon = $(`.upvote-btn[data-post-id=${postId}]`);
                    const downvoteIcon = $(`.downvote-btn[data-post-id=${postId}]`);
                    upvoteIcon.removeClass('text-green-500');
                    upvoteIcon.addClass(response.user_vote_type === 'upvote' ?
                        'text-green-500' :
                        '');
                    downvoteIcon.removeClass(response.user_vote_type === 'upvote' &&
                        'text-red-500');
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            });
        });

        $('.downvote-btn').click(function() {
            const postId = $(this).data('post-id');
            $.post(`/course-work/Home/vote/${postId}/downvote`, function(response) {
                if (response.success && response.message !== 'No action taken') {
                    $(`.vote-count[data-post-id=${postId}]`).text(response.vote_count.upvotes -
                        response.vote_count.downvotes);
                    const upvoteIcon = $(`.upvote-btn[data-post-id=${postId}]`);
                    const downvoteIcon = $(`.downvote-btn[data-post-id=${postId}]`);
                    upvoteIcon.removeClass('text-green-500');
                    upvoteIcon.addClass(response.user_vote_type === 'upvote' ?
                        'text-green-500' :
                        '');
                    downvoteIcon.addClass(response.user_vote_type !== 'upvote' &&
                        'text-red-500');
                    showToast(response.message, 'success');
                } else if (response.success) {
                    showToast('No vote to remove', 'info');
                } else {
                    showToast(response.message, 'error');
                }
            });
        });


    });
    </script>
</section>