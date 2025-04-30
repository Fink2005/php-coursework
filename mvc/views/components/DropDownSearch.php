<button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch" data-dropdown-placement="bottom"
    class="flex justify-center items-center text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    type="button">
    Select tag <i class="fa-solid fa-chevron-down ms-2"></i>
</button>

<!-- Dropdown menu -->
<div id="dropdownSearch" class="z-10 hidden bg-white rounded-lg shadow-sm w-60 dark:bg-gray-700">
    <div class="p-3">
        <label for="input-group-search" class="sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" id="input-group-search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search tag" />
        </div>
    </div>
    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
        aria-labelledby="dropdownSearchButton" id="tagsDropDownSearch"></ul>
    <div id="addtagBtn" class="py-2 flex space-x-2 ps-2 border-2 rounded-t-lg items-center">
        <i class="fa-solid fa-hashtag"></i>
        <span class="text-sm text-blue-500">Add more tags</span>
    </div>
</div>

<script>
let checkedValues = []; // Stores { id, name } for selected tags
let tags = []

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

const fetchTagsDropDown = () => {
    $.ajax({
        url: "/course-work/Admin/AllTags",
        type: "GET",
        success: function(response) {
            tags = JSON.parse(response).tags || [];
            console.log("Tags:", tags);
            updateTagDropdown(tags);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching tags:", error);
            console.error("Status:", status);
            console.error("Response Text:", xhr.responseText);
            showToast("Failed to load tags", "error");
        },
    });
    let tagsHtml = "";
    checkedValues.forEach((item) => {
        tagsHtml += `
            <div class="flex my-1 w-auto justify-between items-center bg-white border-2 text-black space-x-2 text-xs font-medium me-2 px-2.5 py-0.5 rounded-lg py-1" data-id="${item.id}">
                <span>#${item.name}</span>
                <span class="fa-solid fa-xmark text-black text-xs cursor-pointer remove-tag"></span>
            </div>`;
    });
    $("#tagsStore").html(tagsHtml);
    if (checkedValues.length === 0) {
        $("#tagsStore").addClass("hidden");
    } else {
        $("#tagsStore").removeClass("hidden");
    }
};

function updateTagDropdown(filteredTags) {
    console.log("Updating dropdown with tags:", filteredTags);
    let dataTagsDropDown = "";
    if (filteredTags.length === 0) {
        dataTagsDropDown =
            '<li class="py-2 text-sm text-gray-500">No tags found</li>';
    } else {
        filteredTags.forEach((tag) => {
            const isChecked = checkedValues.some(
                (checkedTag) => checkedTag.id === tag.id
            );
            dataTagsDropDown += `
                <li>
                    <div class="flex items-center ps-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                        <input ${isChecked ? "checked" : ""} id="checkbox-tag-${
          tag.id
        }" type="checkbox" class="tag-check w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500" data-id="${
          tag.id
        }" data-name="${tag.name}">
                        <label for="checkbox-tag-${
                          tag.id
                        }" class="w-full py-2 ms-2 text-sm font-medium text-white rounded-sm">#${
          tag.name
        }</label>
                    </div>
                </li>`;
        });
    }
    $("#tagsDropDownSearch").html(dataTagsDropDown);
}

const searchTags = debounce(function(query) {
    console.log("Searching tags with query:", query);
    $.ajax({
        url: `/course-work/Admin/SearchTags/${encodeURIComponent(query)}`,
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("SearchTags response:", response);
            const filteredTags = response.tags || [];
            updateTagDropdown(filteredTags);
        },
        error: function(xhr, status, error) {
            console.error("Error searching tags:", error);
            console.error("Status:", status);
            console.error("Response Text:", xhr.responseText);
            showToast("Failed to search tags", "error");
            // Fallback to all tags
            updateTagDropdown(tags);
        },
    });
}, 300); // 300ms debounce delayc

$(document).ready(function() {
    fetchTagsDropDown()

    // show modal

    // Handle search input
    $("#input-group-search").on("input", function() {
        const query = $(this).val().trim();
        console.log("Input query:", query);
        searchTags(query);
    });

    $("#tagsDropDownSearch").on("click", '[id^="checkbox-tag-"]', function() {
        const tagId = $(this).data("id");
        const tagName = $(this).data("name");
        const isChecked = $(this).prop("checked");

        const findIndex = checkedValues.findIndex((item) => item.id === tagId);
        if (isChecked && findIndex === -1 && checkedValues.length < 5) {
            checkedValues.push({
                id: tagId,
                name: tagName,
            });
        } else if (!isChecked && findIndex !== -1) {
            checkedValues.splice(findIndex, 1);
        } else if (isChecked && checkedValues.length >= 5) {
            $(this).prop("checked", false);
            showToast("You can select up to 5 tags only.", "error");
        }
        fetchTagsDropDown();
    });

    $("#tagsStore").on("click", ".remove-tag", function() {
        const tagId = $(this).parent().data("id");
        const findIndex = checkedValues.findIndex((item) => item.id === tagId);

        if (findIndex !== -1) {
            checkedValues.splice(findIndex, 1);
            $(`#checkbox-tag-${tagId}`).prop("checked", false);
        }
        fetchTagsDropDown();
    });
    $("#dropdownSearchButton").on("click", function() {
        $("#dropdownSearch").toggleClass("hidden");
    });
});
</script>