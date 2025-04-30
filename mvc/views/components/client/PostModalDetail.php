<!-- Main modal -->
<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="hidden h-[calc(100vh)] bg-[#211C4B]/45 items-center overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center ">
    <div class="w-full max-w-2xl absolute top-20 ">
        <!-- Modal content -->
        <div class="rounded-lg w-full shadow-sm dark:bg-[#0E1217]">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5  rounded-t dark:border-gray-600 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="titleDetail">
                </h2>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400" id="desDetail">
                </p>

                <div id="tagsDetail" class="flex flex-wrap gap-2 my-1 ">

                </div>

                <p> <span id="createdDetail" class="text-[#A8B3CF] text-xs font-light"></span></p>
                <div class="w-[320px] h-[200px] rounded-lg overflow-hidden flex items-center justify-center">
                    <img class="size-full object-cover rounded-lg" id="imageDetail" alt="">
                </div>



            </div>


            <div class="flex w-full justify-center items-center">
                <div class="w-[90%] my-5 border rounded-xl">
                    <?php
        include_once 'mvc/views/components/client/CardControllerDetail.php';
        ?>
                </div>
            </div>


            <!-- Modal footer -->
            <div class="flex justify-center flex-col items-center w-full">
                <?php
        include_once 'mvc/views/components/client/CommentTextField.php';
        ?>
            </div>



            <div class="px-2  py-3 flex items-center justify-between w-full  ">
                <div class="flex space-x-5 ms-10">
                    <button class="text-gray-500 hover:text-gray-300 transition-colors">
                        <i class="fa-regular fa-image text-lg"></i>
                    </button>
                    <button class="text-gray-500 hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-link text-lg"></i>
                    </button>
                    <button class="text-gray-500 hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-at text-lg"></i>
                    </button>
                    <button id="url" class="text-gray-500 hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-code text-lg"></i>
                    </button>
                </div>
                <button id="comment-submit"
                    class="ml-auto bg-purple-btn hover:bg-purple-hover text-white px-6 py-2 rounded-full font-medium transition-colors">
                    Comment
                </button>
            </div>



            <div id="comments-container" class="w-full flex flex-col items-center mt-4">
            </div>


        </div>
    </div>
</div>

<script src="./public/scripts/AddUrl.js"></script>
<script>
$(document).ready(function() {
    // Assume postId is available (e.g., from PHP or URL)
    // Replace with actual post ID passed from controller or extracted from URL





    $('.upvote-btn-detail').click(function() {
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

    $('.downvote-btn-detail').click(function() {
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



    // Tab switching functionality
    $("#preview-tab").click(function() {
        $(this).removeClass("text-gray-500").addClass("text-white border-b-2 border-white");
        $("#write-tab").removeClass("text-white border-b-2 border-white").addClass("text-gray-500");
        $("#textField").addClass('hidden');
        $("#previewField").removeClass('hidden');
        // Add preview functionality if needed
    });

    $("#write-tab").click(function() {
        $(this).removeClass("text-gray-500").addClass("text-white border-b-2 border-white");
        $("#preview-tab").removeClass("text-white border-b-2 border-white").addClass("text-gray-500");
        $("#textField").removeClass('hidden');
        $("#previewField").addClass('hidden');

    });

    // Focus textarea when clicking anywhere in the input area
    $("textarea").parent().click(function() {
        $("#comment-input").focus();
    });

    // Close button functionality
    $(".fa-xmark").parent().click(function() {
        $("#comment-input").val('');
    });


});
</script>