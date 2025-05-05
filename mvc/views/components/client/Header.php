<header class="z-50 fixed top-0 w-full h-16 border-b px-3 bg-[#0E1217] flex justify-between items-center">
    <a href="/course-work/Home">
        <img height="60px" width="120px" src="/course-work/public/assets/logo.png" alt="Logo">
    </a>
    <div
        class=" bg-[#1D1F26]  rounded-2xl w-[400px] relative h-12 items-center rounded-12 !bg-background-subtle !px-3 laptop:border laptop:py-1 laptop:backdrop-blur-[3.75rem] mx-2 laptop:mx-auto laptop:rounded-16 flex px-4 overflow-hidden bg-surface-float border border-transparent cursor-text OSaZMMeM+axN9uO6jYIrXA==">
        <?php 
       include_once 'mvc/views/components/client/Search.php';
       ?>
        <!-- <div class="flex h-full items-center bg-background-subtle"></div> -->
        <div class="z-1 hidden items-center gap-3 laptop:flex">
            <div class="flex"><kbd
                    class="flex min-w-5 justify-center rounded-8 border border-border-subtlest-tertiary bg-background-subtle px-2 py-0.5 font-sans text-text-tertiary typo-footnote">⌘</kbd><span
                    class="mx-1 py-0.5 text-text-tertiary typo-footnote">+</span><kbd
                    class="flex min-w-5 justify-center rounded-8 border border-border-subtlest-tertiary bg-background-subtle px-2 py-0.5 font-sans text-text-tertiary typo-footnote">K</kbd>
            </div>
        </div>
    </div>

    <?php 
include_once './mvc/views/components/client/DropDownUser.php';
    
    ?>



</header>