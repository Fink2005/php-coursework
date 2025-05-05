<?php
        include_once 'mvc/views/components/icons/Search.php';
        ?>
<input id="searchBar" data-search-panel-item="true" placeholder="Search" type="primary" autocomplete="off"
    class="h-full text-white flex-1 !placeholder-text-tertiary text-text-tertiary hover:text-text-primary min-w-0 bg-transparent typo-body caret-text-link focus:outline-none"
    value="">

<script>
$(document).ready(function() {
    $('#searchBar').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault(); // Prevent form submission if inside a form
            let query = $(this).val().trim(); // Get the search input value
            if (query) {
                // Perform the search (e.g., redirect, AJAX call, or custom logic)
                console.log('Searching for:', query);
                // Example: Redirect to a search results page
                // window.location.href = '/search?q=' + encodeURIComponent(query);
            }
        }
    });
});
</script>