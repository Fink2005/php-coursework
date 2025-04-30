<?php
class Middleware {
    public static function handle($allowedRoles = ['admin', 'user']) {
        // Ensure session is started
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Check if user is authenticated
        if (empty($_SESSION['user'])) {
            echo "<script>
            sessionStorage.setItem('toastMessage', JSON.stringify({
                message: 'You need to log in to access this page.',
                type: 'warning'
            }));
            window.location.href = '/course-work/Auth/signIn';
            </script>";
            exit;
        }
        
        // Map permission_id to role
        $roleMap = [
            1 => 'user',
            2 => 'admin'
        ];

    
         

        $userPermissionId = isset($_SESSION['user']['permission_id']) ? $_SESSION['user']['permission_id'] : null;
        $userRole = isset($roleMap[$userPermissionId]) ? $roleMap[$userPermissionId] : null;

        // Check if user has required role
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
        echo "<script>
    sessionStorage.setItem('toastMessage', JSON.stringify({
        message: 'You do not have permission to access this page.',
        type: 'warning'
    }));
    window.location.href = '/course-work/Home'
    </script>";



}

// User is authenticated and authorized
return true;
}
}