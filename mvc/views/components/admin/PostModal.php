<section id="postModal"
    class="fixed overflow-y-auto inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="mt-12 bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="postModalTitle" class="text-lg font-semibold"></h3>
            <button id="closePostModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="postForm">
                <div class="mb-4">
                    <label for="postName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input placeholder="Type name" type="text" id="postName" name="postName"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <?php include_once 'mvc/views/components/DropDownSearch.php'?>
                </div>

                <div class="mb-4">
                    <div id="tagsStore" class="hidden border-2  p-1 rounded-lg flex flex-wrap">
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-center w-full relative">
                    <img id="postImageUrl" class="hidden h-64 rounded-lg">
                    <i id="cancelImage"
                        class="fa-solid fa-xmark absolute text-red-700 rounded-full -top-3 -right-1 text-2xl hidden"></i>

                    <label for="dropzone-file" id="dropzone"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click
                                    to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)
                            </p>
                        </div>
                        <input id="dropzone-file" accept="image/*" type="file" class="hidden" />
                    </label>
                </div>
                <div class="mb-4">
                    <button id="uploadImage"
                        class="bg-[#2463EB]/50 text-white py-2 rounded-lg w-full flex items-center justify-center">
                        <span id="textImage" class="me-2">
                            Upload
                        </span>
                        <span id="loading" class="hidden"> <?php include 'mvc/views/components/Spinner.php' ?></span>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="postUrl" class="block text-sm font-medium text-gray-700 mb-1">Post url</label>
                    <input placeholder="Paste url" type="text" id="postUrl" name="postUrl"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4" id="description">
                    <label for="postDescription"
                        class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input placeholder="Type description" type="text" id="postDescription" name="postDescription"
                        class="w-full px-3 py-2 pb-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelPostBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">Cancel</button>
                    <button type="submit" id="savePostbtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>