<div class="flex flex-row items-center justify-between text-[#A8B3CF]">
    <div class="downAndUpVote flex items-center  bg-[#272B33] rounded-xl overflow-hidden">
        <button data-post-id="<?php echo $post['id']; ?>" aria-label="Upvote" aria-pressed="false" class="<?php echo $post['user_vote_type'] === 'upvote' ? 'text-green-500' : ''; ?> upvote-btn inline-flex hover:text-green-500 cursor-pointer flex-row
        items-center    
        h-8 px-3">

            <?php include './mvc/views/components/icons/UpVote.php' ?>

            <span data-post-id="<?php echo $post['id']; ?>" class="vote-count pl-3">
                <?php echo $post['upvotes'] - $post['downvotes']; ?>
            </span>
        </button>
        <button data-post-id="<?php echo $post['id']; ?>" class="downvote-btn  hover:text-red-500  -rotate-180 focus-outline inline-flex cursor-pointer flex-row
        items-center    
        iconOnly h-8 w-8 p-0 pointer-events-auto">
            <?php include './mvc/views/components/icons/DownVote.php' ?>

        </button>
    </div>
    <div class="small btn-quaternary flex flex-row items-stretch btn-tertiary-blueCheese"><button aria-label="Comments"
            id="comment-btn" aria-pressed="false" class="btn focus-outline inline-flex cursor-pointer flex-row
        items-center    
        iconOnly h-8 w-8 p-0 rounded-10">

            <?php include './mvc/views/components/icons/Comment.php' ?>

        </button><label for="comment-btn" class="flex cursor-pointer items-center pl-1 font-bold typo-callout"><span
                class="flex h-5 min-w-[1ch] flex-col overflow-hidden">0</span></label></div>
    <div class="small  flex flex-row items-stretch"><button type="button" aria-label="Bookmark" id="bookmark-btn"
            aria-pressed="false" class="btn focus-outline inline-flex cursor-pointer flex-row
        items-center   
        iconOnly h-8 w-8 p-0 rounded-10 ">

            <?php include './mvc/views/components/icons/BookMark.php' ?>


        </button></div><button aria-label="Copy link" class="btn focus-outline inline-flex cursor-pointer flex-row
        items-center   
        iconOnly h-8 w-8 p-0 rounded-10 btn-tertiary-cabbage">
        <?php include './mvc/views/components/icons/Link.php' ?>


    </button>
</div>

<script>
$(".downAndUpVote").on("click", function(event) {
    event.stopPropagation();
});
</script>