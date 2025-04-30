<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="deleteModalTitle" class="text-lg font-semibold">Confirm Delete</h3>
            <button id="closeDeleteModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p id="deleteModalText" class="mb-4">Are you sure you want to delete this item? This action cannot be
                undone.</p>
            <div class="flex justify-end space-x-2">
                <button id="cancelDeleteBtn"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">Cancel</button>
                <button id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none">Delete</button>
            </div>
        </div>
    </div>
</div>