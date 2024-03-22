<?php
try {
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "guvi";
    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
    if (!$conn) {
        die("Database Error!");
    }

    $jsonData = file_get_contents('php://input');
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if ($email && $password) {
        // Prepare the SQL statement with placeholders
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the user data
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($user) {
            // Verify the password using password_verify
            if (password_verify($password, $user["password"])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'email' => $email]);
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'msg' => 'Password not valid']);
            }
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'msg' => 'User not found']);
        }
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'msg' => 'Email and/or password not provided']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}
?>
