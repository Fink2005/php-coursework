    <div class="w-full flex items-center justify-center flex-col min-h-screen">

        <!-- Form Header -->
        <div class="flex items-center justify-center mb-8">

            <h1 class="text-3xl font-bold text-pink-500">Join daily.dev</h1>
        </div>

        <!-- Form -->
        <form class="space-y-4">
            <!-- Email Field -->
            <div class="relative">
                <div class="border border-white rounded-xl p-4 flex items-center space-x-2">
                    <i class="fa-regular fa-envelope text-xl text-[#9CA3AF]"></i>
                    <div class="flex-1">
                        <div class="text-xs text-gray-400 mb-1">Email</div>
                        <input id="email" type="email" class="w-full bg-transparent focus:outline-none" />
                    </div>

                </div>
            </div>

            <!-- Name Field -->
            <div class="relative">
                <div class="border border-white rounded-xl p-4 flex items-center space-x-2">
                    <i class="fa-regular fa-user text-xl text-[#9CA3AF]"></i>
                    <div class="flex-1">
                        <div class="text-xs text-gray-400 mb-1">Name</div>
                        <input id="name" type="text" class="w-full bg-transparent focus:outline-none" />
                    </div>

                </div>
            </div>

            <!-- Password Field -->
            <div class="relative">
                <div class="border border-white rounded-xl p-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="text-xs text-gray-400 mb-1">Create a password</div>
                        <input id="password" type="password" class="w-full bg-transparent focus:outline-none" />
                    </div>
                </div>
            </div>

            <!-- Username Field -->


            <!-- Experience Level Field -->
            <div class="relative">
                <div class="border border-white rounded-xl p-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <div class="flex-1">
                        <div class="text-xs text-gray-400 mb-1">Experience level</div>
                        <select class="w-full bg-transparent focus:outline-none appearance-none">
                            <option>Select your experience level</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>]
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Email Usage Notice -->
            <div class="text-gray-400 text-sm py-4 border-t border-gray-800">
                Your email will be used to send you product and community updates
            </div>



            <button type="button" id="signUp" class="bg-white w-full h-10 rounded-xl text-black">Create account</button>

        </form>
    </div>


    <script>
$(document).ready(function() {
    $("#signUp").click(function() {
        let email = $("#email").val();
        let password = $("#password").val();
        let name = $("#name").val();
        

        $.ajax({
            url: "/course-work/Auth/signUp",
            type: "POST",
            data: {
                email: email,
                password: password,
                username: name,
            },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response)
                if (response.success) {
                    Toastify({
                        text: "Sign up successfully",
                        className: "bg-blue-500 rounded-lg",
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "center",
                    }).showToast();

                    setTimeout(() => {
                        window.location.href = "/course-work/Auth/signIn";
                    }, 1000);
                  
                } else {
                    Toastify({
                        text: response.message,
                        className: "rounded-lg",
                        duration: 3000,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "red"
                        }

                    }).showToast();

                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

})
    </script>