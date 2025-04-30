<div class="flex flex-col items-center justify-center w-full h-[calc(100vh)] ">
    <!-- Email Icon -->
    <div class="mb-6 border-2 border-white rounded-full p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </div>

    <!-- Heading -->
    <h1 class="text-3xl font-bold mb-6">Verify your email</h1>

    <!-- Verification Message -->
    <p class="text-gray-400 mb-2 text-center">A verification code has been sent to:</p>
    <p id="email" class="mb-8"></p>

    <!-- Verification Code Input -->
    <div class="flex gap-2 mb-6" id="code-inputs">
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            autofocus>
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" maxlength="1"
            class="w-12 h-14 bg-gray-800 rounded-md text-center text-xl font-bold border border-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Notification -->
    <div id="notification" class="hidden mb-4 py-2 px-4 bg-green-500 text-white rounded-md">
        Code resent successfully!
    </div>

    <!-- Resend Code -->
    <p class="mb-8 text-gray-400" id="resendEmail">
        Didn't get a verification code?
        <a href="#" id="resend-link" class="text-blue-400 hover:underline">Resend code</a>
    </p>

    <!-- Verify Button -->
    <button id="verify-btn"
        class="bg-white text-black font-bold py-3 px-4 rounded-md w-[70%] mb-8 active:bg-blue-700 hover:bg-gray-200 transition">
        Verify
    </button>

    <!-- Success Message -->
    <div id="success-message" class="hidden text-center mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-green-500 mb-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <h2 class="text-2xl font-bold mb-2">Verification Successful!</h2>
        <p class="text-gray-400">You will be redirected shortly...</p>
    </div>
</div>


<script>
$(document).ready(function() {
    // Cache jQuery selectors
    const $inputs = $('#code-inputs input');
    const $verifyBtn = $('#verify-btn');
    const $resendLink = $('#resend-link');
    const $notification = $('#notification');
    const $successMessage = $('#success-message');
    const $mainContent = $('main');

    $('#email').text(localStorage.getItem('email', email))
    console.log(localStorage.getItem('email', email))

    // Focus the first input on load
    $inputs.first().focus();

    // Handle input
    $inputs.on('input', function() {
        // Only allow numbers
        const $this = $(this);
        $this.val($this.val().replace(/[^0-9]/g, ''));

        // If input has a value and there's a next input, focus it
        if ($this.val() && $this.index() < $inputs.length - 1) {
            $inputs.eq($this.index() + 1).focus();
        }

        // Check if all inputs are filled
        checkInputs();
    });

    // Check if all inputs are filled
    function checkInputs() {
        const allFilled = $inputs.toArray().every(input => $(input).val());
        if (allFilled) {
            $verifyBtn.removeClass('bg-white').addClass('bg-blue-500').prop('disabled', false);
        } else {
            $verifyBtn.removeClass('bg-blue-500').addClass('bg-white').prop('disabled', true);
        }
    }

    // Handle resend link click
    $resendLink.on('click', function(e) {
        e.preventDefault();

        // Show notification with jQuery animation
        $notification.removeClass('hidden').css('opacity', 0).animate({
            opacity: 1
        }, 300);



        // Clear inputs and focus the first one
        $inputs.val('');
        $inputs.first().focus();
        $.ajax({
            url: '/course-work/Auth/resendEmail', // Corrected URL
            type: 'POST',
            data: {
                email: localStorage.getItem('email', email)
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirect to sign-in after success
                    // Hide notification after 3 seconds
                    setTimeout(function() {
                        $notification.animate({
                            opacity: 0
                        }, 300, function() {
                            $(this).addClass('hidden');
                        });
                    }, 3000);
                } else {
                    showToast('error', 'error')
                }
            },

        });


    });

    // Handle verify button click
    $verifyBtn.on('click', function() {
        const code = $inputs.map(function() {
            return $(this).val();
        }).get().join('');


        if (code.length === 6) {
            $.ajax({
                url: '/course-work/Auth/confirm', // Corrected URL
                type: 'POST',
                data: {
                    token: code
                }, // Changed 'token' to match your backend
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success')

                        // Redirect to sign-in after success
                        localStorage.removeItem('email')
                        setTimeout(() => {
                            window.location.href =
                                '/course-work/Auth/signIn';
                        }, 2000);
                    } else {
                        showToast(response.message, 'error')
                    }
                },

            });
        } else {
            showToast('Please enter a 6-digit code.', 'error')

        }
    });

    // Focus the first empty input on load
    $inputs.each(function() {
        if (!$(this).val()) {
            $(this).focus();
            return false; // Break the loop
        }
    });
});
</script>