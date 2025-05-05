<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    dark: {
                        100: '#1e1e1e',
                        200: '#1a1a1a',
                        300: '#2a2a2a',
                        900: '#121212',
                    }
                }
            }
        }
    }
    </script>
    <!-- jQuery CDN -->
</head>

<body class="bg-black text-white p-4">
    <div class="max-w-3xl mx-auto rounded-xl overflow-hidden border border-gray-700 bg-dark-900">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-700">
            <h1 class="text-3xl font-bold text-white">Profile</h1>
            <button id="saveBtn"
                class="bg-white text-black font-semibold px-6 py-2 rounded-full hover:bg-gray-200 transition-colors duration-200">
                <span id="saveText">Save</span>
                <span class="hidden" id="loading">
                        <?php include 'mvc/views/components/Spinner.php'; ?>
                    </span>
            </button>
        </div>

        <!-- Profile Picture Section -->
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold mb-2 text-white">Profile Picture</h2>
            <p class="text-gray-400 mb-6">Upload a picture to make your profile stand out and let people recognize your
                comments and contributions easily!</p>

            <!-- Cover Image Upload -->
            <div id="coverImageUpload"
                class="bg-dark-200 flex-1 h-32 rounded-3xl w-[50%] pe-10 flex items-center justify-end hover:bg-dark-300 transition-colors duration-200 relative">
                <div class="flex gap-4">
                    <!-- Profile Picture Upload -->
                    <div id="profilePicUpload"
                        class="bg-dark-200 w-32 h-32 absolute top-0 left-0 border-4 border-black z-50 rounded-3xl flex items-center justify-center">
                        <img id="avatar" src="" class="w-full h-full object-cover rounded-3xl hidden">
                        <div id="cancelAvatar"
                            class="hidden size-8 bg-black rounded-full flex items-center justify-center absolute -top-3 -right-3 cursor-pointer">
                            <i class="fa-solid fa-xmark text-white"></i>
                        </div>
                        <input type="file" id="profilePicInput" class="hidden" accept="image/*">
                    </div>
                    <div>
                        <label id="clickAvatar" class="cursor-pointer w-[50%] group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="mt-2 text-gray-400">Upload cover image</p>
                            <!-- <input type="file" id="coverImageInput" class="hidden" accept="image/*"> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-white">Account Information</h2>

            <!-- Email Field -->
            <div class="mb-4">
                <div class="bg-dark-100 flex items-center p-3 rounded-lg">
                    <span class="text-gray-400 text-xl mr-3"><i class="fa-solid fa-envelope"></i></span>
                    <div class="flex flex-col flex-1">
                        <label class="text-sm text-gray-400">Email</label>
                        <input type="email" id="emailInput"
                            class="bg-transparent border-none outline-none text-white text-lg" value="">
                    </div>
                </div>
            </div>
            <!-- Name Field -->
            <div class="mb-4">
                <div class="bg-dark-100 flex items-center p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <div class="flex flex-col flex-1">
                        <label class="text-sm text-gray-400">Name</label>
                        <input type="text" id="nameInput"
                            class="bg-transparent border-none outline-none text-white text-lg" value="">
                    </div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <div class="bg-dark-100 flex items-center p-3 rounded-lg">
                    <span class="text-gray-400 text-xl mr-3"><i class="fas fa-lock"></i></span>
                    <div class="flex flex-col flex-1">
                        <label class="text-sm text-gray-400">Password</label>
                        <input type="password" id="passwordInput"
                            class="bg-transparent border-none outline-none text-white text-lg" value="">
                    </div>
                </div>
            </div>
            <!-- New Password Field -->
            <div class="mb-4">
                <div class="bg-dark-100 flex items-center p-3 rounded-lg">
                    <span class="text-gray-400 text-xl mr-3"><i class="fas fa-lock"></i></span>
                    <div class="flex flex-col flex-1">
                        <label class="text-sm text-gray-400">New password</label>
                        <input type="password" id="newPasswordInput"
                            class="bg-transparent border-none outline-none text-white text-lg" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Safely retrieve PHP session data
        let isImage = false

        let email = null
        let username = null
       
        let avatar = null
           
        let userId = null

            $.ajax({
                url: `/course-work/Home/UserInfo`,
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log(response.data)
                    if (response.success) {

                        email = response.data.email
                        username = response.data.username
                        avatar = response.data.avatar
                        userId = response.data.id

                        $('#emailInput').val(email);
                        $('#nameInput').val(username);
                        if (avatar) {
                            $('#cancelAvatar').removeClass('hidden');
                            $('#avatar').attr('src', avatar).removeClass('hidden');
                        } else {
                            $('#avatar').attr('src', 'https://ui-avatars.com/api/?name=' + (username || 'User')).removeClass(
                                'hidden');
                        }
                    
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Error updating user: ' + error, 'error');
                }
            });


        $('#clickAvatar').on('click', function() {
            $('#profilePicInput').click();
        });
        // Set initial values

    

        // Cancel avatar
        $('#cancelAvatar').on('click', function() {
            isImage = true
            $('#avatar').attr('src', 'https://ui-avatars.com/api/?name=' + (username || 'User'))
                .removeClass('hidden');
            $('#cancelAvatar').addClass('hidden');
            $('#profilePicInput').val(''); // Clear file input
        });

        // Handle profile picture upload
   
        $('#profilePicInput').change(async function(e) {
            isImage = true
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatar').attr('src', e.target.result).removeClass('hidden');
                    $('#cancelAvatar').removeClass('hidden');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

      

        // Save button functionality
        $('#saveBtn').click(async function() {
            $('#loading').removeClass('hidden');
            $('#saveText').addClass('hidden');
            const username = $('#nameInput').val();
            const email = $('#emailInput').val();
            const password = $('#passwordInput').val();
            const newPassword = $('#newPasswordInput').val();



            // Validate new password length
            if (newPassword.length > 0 && newPassword.length < 6) {
                showToast("New password must be at least 6 characters", "error");
                return;
            }

            // Perform validation
            if ((password || newPassword) && (password.length < 6 ||
                    newPassword.length < 6)) {
                showToast('Passwords must be at least 6 characters', 'error')
                return;
            }
            const formData = new FormData();


            
                if (isImage && $('#avatar').attr('src') ) {
            let formDataImage = new FormData();
            formDataImage.append('file', $('#profilePicInput')[0].files[0]);
               const isSuccess = await handleUploadImage(formDataImage)
                if (isSuccess) {
                    console.log({isSuccess})
                formData.append('avatar', isSuccess);
                } 
                }


            // Use FormData to handle file uploads
            formData.append('email', email);
            formData.append('username', username);
            formData.append('password', password);
            formData.append('newPassword', newPassword);

           
            // Send update request
            $.ajax({
                url: `/course-work/Admin/UpdateUser/${userId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.success) {

                        $('#avatarDropdown').attr('src', response.user_data.avatar)
                        $('#avatarSidebar').attr('src', response.user_data.avatar)
      
                        showToast(response.message, 'success');
                        $('#loading').addClass('hidden');
                        $('#saveText').removeClass('hidden');
                        // Reload page to refresh session data
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Error updating user: ' + error, 'error');
                }
            });
        });
    });
    </script>
</body>

</html>