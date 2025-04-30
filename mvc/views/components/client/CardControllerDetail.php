<div class="flex items-center justify-between text-[#A8B3CF] w-full">
    <div class="downAndUpVote flex items-center p-3  bg-[#272B33] rounded-xl border overflow-hidden">
        <button id="upvote-btn-detail" aria-label="Upvote" aria-pressed="false" class="  inline-flex hover:text-green-500 cursor-pointer flex-row
        items-center    
        h-8 ">

            <?php include './mvc/views/components/icons/UpVote.php' ?>
            <span id="vote-count-detail" class=" px-3">
            </span>
        </button>
        <button class="downvote-btn-detail  hover:text-red-500  -rotate-180 focus-outline inline-flex cursor-pointer flex-row
        items-center    
        iconOnly h-8 w-8 p-0 pointer-events-auto">
            <?php include './mvc/views/components/icons/DownVote.php' ?>

        </button>
    </div>
    <div class="flex flex-row items-stretch "><button aria-label="Comments" id="comment-btn" aria-pressed="false" class="inline-flex cursor-pointer flex-row
        items-center    
        iconOnly h-8 w-8 p-0 rounded-10">

            <?php include './mvc/views/components/icons/Comment.php' ?>

        </button>
        <label for="comment-btn" class="flex cursor-pointer items-center pl-1 font-bold typo-callout">
            <span class="flex h-5 min-w-[1ch] flex-col overflow-hidden">Comment</span></label>
    </div>
    <div class=" flex "><button type="button" aria-label="Bookmark" id="bookmark-btn" class="flex items-center">

            <div>
                <?php include './mvc/views/components/icons/BookMark.php' ?>

            </div>
            <label for="comment-btn" class="flex cursor-pointer items-center pl-1 font-bold typo-callout">
                <span class="flex h-5 min-w-[1ch] flex-col overflow-hidden">Bookmark</span></label>


        </button>
    </div>
    <button class=" flex cursor-pointer 
        items-center   
        pe-4
         ">
        <div>
            <?php include './mvc/views/components/icons/Link.php' ?>

        </div>
        <label for="comment-btn" class="flex cursor-pointer items-center pl-1 font-bold typo-callout">
            <span class="flex h-5 min-w-[1ch] flex-col overflow-hidden">Copy</span></label>
    </button>
</div>