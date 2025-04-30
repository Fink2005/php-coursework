<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/4.1.0/cdn.min.js"
        integrity="sha512-bz58Sg3BAWMEMPTH0B8+pK/+5Qfqq6b2Ho2G4ld12x4fiUVqpY8jSbN/73qpBQYFLU4QnKVL5knUm4uqcJGLVw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <main>
        <?php  isset($data["Page"]) ? include_once "./mvc/views/pages/" . $data["Page"] . ".php" : '' ?>
    </main>
    <div class="text-2xl text-blue-400" id="flash-message"
        data-message="<?= isset($data['Message']) ? htmlspecialchars($data['Message']) : '' ?>">
    </div>





    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="/course-work/public/scripts/ShowToast.js"></script>
    <script src="/course-work/public/scripts/Validation.js"></script>
    <script src="/course-work/public/scripts/FormattedDate.js"></script>
    <script src="/course-work/public/scripts/UploadImage.js"></script>

</body>


</html>


<script>
$(document).ready(function() {
    // $.get('/course-work/mvc/core/GetMessage.php', function(response) {
    //     if (response) {
    //         const data = JSON.parse(response);
    //         if (data.message) {
    //             console.log(data)
    //             Toastify({
    //                 text: data.message,
    //                 className: "bg-blue-500 rounded-xl",
    //                 duration: 3000,
    //                 gravity: "top", // `top` or `bottom`
    //                 position: "center",

    //             }).showToast();
    //         }
    //     }
    // });

});
</script>