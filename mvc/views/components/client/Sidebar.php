<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>

<aside id="default-sidebar"
    class="fixed top-16  left-0 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">


    <div class="h-full px-3 py-4 overflow-y-auto bg-[#121316] border-r">
        <a href="/course-work/Home/CreatePosts" class="bg-white rounded-lg inline-flex  cursor-pointer  flex-row
        items-center  transition
        duration-200 ease-in-out  justify-center font-bold h-8 px-3 rounded-10 mb-4 !flex whitespace-nowrap"><i
                class="fa-solid fa-plus mr-2"></i>New post</a>
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/course-work/Home"
                    class="inline-flex px-2 items-center space-x-2 hover:bg-gray-700 py-1 rounded-lg w-full">

                    <img class="size-8 rounded-lg"
                        src="<?php
            echo $_SESSION['user']['avatar'] ? $_SESSION['user']['avatar'] : 'https://ui-avatars.com/api/?name=' . $_SESSION['user']['username'] ?>"
                        alt="">

                    <span class="text-md font-regular text-white">My feed</span>
                </a>


            </li>
            <li>
                <a href="/course-work/Home/UserInfo"
                    class="inline-flex px-2 items-center space-x-2 hover:bg-gray-700 py-1 rounded-lg w-full text-white">
                    <i class="fa-solid fa-user "></i> <span> User profile</span></a>
            </li>
            <li>
                <a href="/course-work/Auth/logOut"
                    class="inline-flex px-2 items-center space-x-2 hover:bg-gray-700 py-1 rounded-lg w-full text-white">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Sign
                        out</span></a>
            </li>
        </ul>
    </div>
</aside>