<?php

    if (isset($_GET['created']) && $_GET['created'] == 1) {
        echo '<script>alert("User created successfully!");</script>';
    }

    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        echo '<script>alert("User deleted successfully!");</script>';
    }

    if (isset($_GET['delete_error'])) {
        echo '<script>alert("Failed to delete user!");</script>';
    }

    if (isset($_GET['updated']) && $_GET['updated'] == 1) {
        echo '<script>alert("User updated successfully!");</script>';
    }

    if (isset($_GET['update_error'])) {
        echo '<script>alert("Failed to Update Username or Password");</script>';
    }

    if (isset($_GET['error'])) {
        switch($_GET['error']) {
            case 'emailtaken':
                echo '<script>alert("Email already exists! Please use a different email.");</script>';
                break;
            case 'usernametaken':
                echo '<script>alert("Username already exists!");</script>';
                break;
            case 'invalidrole':
                echo '<script>alert("Invalid user role selected.");</script>';
                break;
        }
    }
?>
