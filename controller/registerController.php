<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../model/authenticationClass.php');

    $auth = Authentication::getInstance();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $userStatus = $_POST['userStatus'];

        if ($password !== $confirm_password) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            $_SESSION['show_auth'] = 'register';
            header('Location: /');
            exit;
        }
        
        $flag = $auth->verificationRegister($username,$email);
        
        if ($flag){ // If user or email already exists
            // Determine specific error
            if (strtolower($flag['username']) === strtolower($username)) {
                $_SESSION['auth_error'] = 'Username already taken. Please choose another.';
            } else if (strtolower($flag['email']) === strtolower($email)) {
                $_SESSION['auth_error'] = 'Email already registered. Please use a different email or login.';
            } else {
                $_SESSION['auth_error'] = 'Username or email already in use.'; // Generic fallback
            }
            $_SESSION['show_auth'] = 'register'; // To reopen the popup on the register tab
            // Preserve form values to repopulate (optional, but good UX)
            $_SESSION['form_values'] = ['name' => $name, 'username' => $username, 'email' => $email, 'userStatus' => $userStatus];
            header('Location: /'); // Redirect back to homepage (where popup is)
            exit;
        } else {
            // Proceed with registration
            $auth->register($name,$username,$email,$password,$userStatus);
            // Clear any previous form values or errors after successful registration
            unset($_SESSION['form_values']);
            unset($_SESSION['auth_error']);
            unset($_SESSION['show_auth']);
            // Consider logging the user in automatically here by setting session variables if not already done by $auth->register()
            $_SESSION['success_message'] = 'Registration successful! You can now log in.'; // Optional success message
            header('Location: /'); // Redirect to homepage, JS might open login popup
            exit;
        }
    }
    else {
        header('Location: /');
        exit;
    }
?>
