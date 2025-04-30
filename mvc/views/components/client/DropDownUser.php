<div class="size-10 rounded-lg overflow-hidden" id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
    data-dropdown-trigger="hover">
    <img class="size-full"
        src="<?php
            echo $_SESSION['user']['avatar'] ? $_SESSION['user']['avatar'] : 'https://ui-avatars.com/api/?name=' . $_SESSION['user']['username'] ?>"
        alt="">
</div>




<!-- Dropdown menu -->
<div id="dropdownHover"
    class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
        <li>
            <a href="/course-work/Home/UserInfo"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex-inline items-center space-x-1">
                <i class="fa-solid fa-user"></i> <span> Your profile</span></a>
        </li>
        <li>
            <a href="/course-work/Auth/logOut"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex-inline items-center space-x-1">
                <i class="fa-solid fa-right-from-bracket"></i> <span>Sign
                    out</span></a>
        </li>
    </ul>
</div>