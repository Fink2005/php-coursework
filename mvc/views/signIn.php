<div class="container mx-auto px-4 py-8 max-w-md">
    <!-- Logo -->
    <div class="mb-16">
        <div class="flex items-center">
            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 0L0 20L20 40L40 20L20 0ZM20 11.5L28.5 20L20 28.5L11.5 20L20 11.5Z" fill="white" />
            </svg>
            <span class="ml-2 text-xl font-semibold">daily.dev</span>
        </div>
    </div>

    <!-- Login Form -->
    <div class="flex flex-col items-center">
        <h1 class="text-2xl font-bold mb-8">Log in</h1>

        <!-- Social Login Buttons -->
        <div class="w-full space-y-4 mb-6">
            <button
                class="w-full bg-white text-[#1877F2] rounded-full py-3 px-4 flex items-center justify-center font-medium">
                <i class="fab fa-facebook-f text-[#1877F2] mr-3"></i>
                Facebook
            </button>
            <button
                class="w-full bg-white text-gray-800 rounded-full py-3 px-4 flex items-center justify-center font-medium">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                Google
            </button>
            <button
                class="w-full bg-white text-gray-800 rounded-full py-3 px-4 flex items-center justify-center font-medium">
                <i class="fab fa-github text-black mr-3"></i>
                GitHub
            </button>
            <button
                class="w-full bg-white text-gray-800 rounded-full py-3 px-4 flex items-center justify-center font-medium">
                <i class="fab fa-apple text-black mr-3"></i>
                Apple
            </button>
        </div>

        <!-- Divider -->
        <div class="w-full flex items-center justify-center mb-6">
            <span class="text-gray-400">or</span>
        </div>

        <!-- Email/Password Form -->
        <div class="w-full space-y-4 mb-6">
            <div class="bg-[#1E1E1E] rounded-lg flex items-center px-4 py-3">
                <i class="far fa-envelope text-gray-400 mr-3"></i>
                <input id="email" type="email" placeholder="Email"
                    class="bg-transparent w-full focus:outline-none text-white">
            </div>
            <div class="bg-[#1E1E1E] rounded-lg flex items-center px-4 py-3 border border-gray-700">
                <i class="fas fa-lock text-gray-400 mr-3"></i>
                <input id="password" type="password" placeholder="Password"
                    class="bg-transparent w-full focus:outline-none text-white">
                <button class="text-gray-400">
                    <i class="far fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Login Actions -->
        <div class="w-full flex justify-between items-center mb-8">
            <a href="#" class="text-gray-400 hover:text-white text-sm">Forgot password?</a>
            <button id="logIn" class="bg-white text-black font-medium py-2 px-8 rounded-full">Log in</button>
        </div>

        <!-- Divider -->
        <div class="w-full border-t border-gray-700 mb-8"></div>

        <!-- Sign Up Link -->
        <div class="text-center mb-8">
            <span class="text-gray-300">Not a member yet? </span>
            <a href="/course-work/Auth/signUp" class="text-white font-medium">Sign up</a>
        </div>

        <!-- Footer -->
        <div class="text-xs text-gray-500 flex flex-wrap justify-center gap-4">
            <span>© 2025 Daily Dev Ltd.</span>
            <a href="#" class="hover:text-gray-300">Guidelines</a>
            <a href="#" class="hover:text-gray-300">Explore</a>
            <a href="#" class="hover:text-gray-300">Tags</a>
            <a href="#" class="hover:text-gray-300">Sources</a>
            <a href="#" class="hover:text-gray-300">Squads</a>
            <a href="#" class="hover:text-gray-300">Leaderboard</a>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#logIn').click(function() {

        let email = $('#email').val();
        let password = $('#password').val();

        $.ajax({
            url: '/course-work/Auth/signIn',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success) {
                    Toastify({
                        text:`Login successful, hello ${response.user}`,
                        className: "bg-blue-500 rounded-lg",
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "center",

                    }).showToast();
                    setTimeout(() => {
                        window.location.href = "/course-work/Home";
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
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

    });
});
</script>