<div class=" w-[90%] bg-dark-bg rounded-lg overflow-hidden">
    <!-- Header with tabs -->





    <div class="flex items-center border-b border-border-color">
        <div class="flex">
            <button id="write-tab" class="px-6 py-4 font-medium text-white border-b-2 border-white">Write</button>
            <button id="preview-tab" class="px-6 py-4 font-medium text-gray-500">Preview</button>
        </div>
        <button class="ml-auto px-4 text-gray-400 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Comment input area -->
    <div class="p-4 min-h-[200px]">
        <div id="textField" class="flex">
            <div class="mr-3">
                <div class="w-10 h-10 rounded rounded-lg overflow-hidden flex items-center justify-center">

                    <img src="https://avatars.githubusercontent.com/u/171264038?v=4&s=64" class="size-full" alt="">
                </div>
            </div>
            <div class="flex-1">
                <textarea id="comment-input"
                    class="w-full  bg-transparent text-gray-300 text-lg focus:outline-none resize-none"
                    placeholder="Share your thoughts"></textarea>
            </div>
        </div>

        <div id="previewField" class="hidden">
            <img width="250" height="250" class="rounded-lg hidden" id="previewImg" src="" alt="">
        </div>

    </div>

    <!-- Action buttons -->

</div>
<script>

</script>