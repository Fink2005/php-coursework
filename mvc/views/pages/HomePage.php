<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-[#121316]">
    <?php
    include_once './mvc/views/components/client/Header.php';
    include_once './mvc/views/components/client/Sidebar.php';
    ?>
    <main class="p-4 sm:ml-64 mt-20">
        <?php include_once "./mvc/views/" . $data['view'] . ".php"; ?>
    </main>
    <div id="up" class="hidden fixed bottom-5 right-5 p-3 rounded-full cursor-pointer">
        <i class="fa-solid fa-circle-chevron-up text-4xl text-white"></i>
    </div>

    <script>
    $(document).ready(function() {
        // Show/hide #up button based on scroll position
        $(window).on('scroll', function() {
            const viewportTop = $(window).scrollTop();

            // Show #up button if scrolled down more than 30% of viewport height
            if (viewportTop > 300) {
                $('#up').removeClass('hidden');
            } else {
                $('#up').addClass('hidden');
            }
        });

        // Scroll to top when #up is clicked
        $('#up').on('click', function() {
            $('html, body').animate({
                scrollTop: 0 // Scroll to the top of the page
            }, 500); // 500ms animation duration
        });
    });
    </script>
</body>

</html>