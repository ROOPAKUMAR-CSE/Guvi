<?php
$jsonData = file_get_contents('php://input');
$json = $_POST;
$fullname = $json["fullname"] ?? '';
$email = $json["email"] ?? '';
$password = $json["password"] ?? '';
$passwordRepeat = $json["repeat_password"] ?? '';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$errors = array();

if (empty($fullname) || empty($email) || empty($password) || empty($passwordRepeat)) {
    array_push($errors, "All fields are required");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Email is not valid");
}

if (strlen($password) < 8) {
    array_push($errors, "Password must be at least 8 characters");
}

if ($password != $passwordRepeat) {
    array_push($errors, "Passwords do not match");
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
} else {
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "guvi";
    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
    if (!$conn) {
        die("Database connection error");
    }

    $sqlCheckEmail = "SELECT * FROM users WHERE email=?";
    $stmtCheckEmail = mysqli_prepare($conn, $sqlCheckEmail);
    mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
    mysqli_stmt_execute($stmtCheckEmail);
    $resultCheckEmail = mysqli_stmt_get_result($stmtCheckEmail);
    $rowcountCheckEmail = mysqli_num_rows($resultCheckEmail);

    if ($rowcountCheckEmail > 0) {
        array_push($errors, "Email already exists");
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $sqlInsertUser = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmtInsertUser = mysqli_prepare($conn, $sqlInsertUser);

        if (mysqli_stmt_prepare($stmtInsertUser, $sqlInsertUser)) {
            mysqli_stmt_bind_param($stmtInsertUser, "sss", $fullname, $email, $passwordHash);
            mysqli_stmt_execute($stmtInsertUser);

            // MongoDB insertion (assuming MongoDB connection is already established)
            $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert(['fullname' => $fullname, '_id' => $email, 'address' => '', 'phno' => '', 'gender' => '', 'dob' => '']);
            $manager->executeBulkWrite('mydb.user', $bulk);

            $data = "Registration successful";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data);
        } else {
            die("Something went wrong with user insertion");
        }
    }

    mysqli_close($conn); // Close the database connection
}
?>
