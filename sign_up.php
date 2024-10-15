<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $secretKey = "6Lf4NV8qAAAAAIBzOd-yb-qQCYSiM5XH08vBGa7a";
    $captchaResponse = $_POST['g-recaptcha-response'];

    if (!$captchaResponse) {
        echo "<div class='error-message'>Please complete the reCAPTCHA.</div>";
        exit;
    }

    // Verify the CAPTCHA
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captchaResponse;
    $response = file_get_contents($verifyUrl);
    $responseKeys = json_decode($response, true);
    

    if (!$responseKeys['success']) {
        echo "<div class='error-message'>reCAPTCHA verification failed. Please try again.</div>";
        exit;
    }

    
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Database connection 
    $conn = new mysqli('localhost', 'root', 'admin', 'micro');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
