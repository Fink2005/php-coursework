<?php
session_start();

if (isset($_SESSION['flashMessage'])) {
    echo json_encode([
        'message' => $_SESSION['flashMessage']
    ]);
    unset($_SESSION['flashMessage']); 
} else {
    echo json_encode(['message' => null]);
}