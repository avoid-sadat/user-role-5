<?php
session_start();

$user = $_SESSION["user"] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $users = json_decode(file_get_contents("users.json"), true);

    foreach ($users as $userData) {
        if ($userData["email"] === $email && $userData["password"] === $password) {
            $_SESSION["user"] = $userData;
            $user = $userData;

            // Redirect based on the user's role
            if ($userData["role"] === "admin") {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($userData["role"] === "manager") {
                header("Location: manager_dashboard.php");
                exit();
            } else {
                header("Location: user_dashboard.php");
                exit();
            }
        }
    }
    echo "Login failed. Check your credentials.";
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #888888;
        }

        h1 {
            color: #007BFF;
        }

        p {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            display: block;
            margin: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <?php if ($user): ?>
            <h1>Welcome, <?php echo $user["username"]; ?></h1>
            <?php if ($user["role"] === "admin"): ?>
                <p>You have admin privileges. Choose an option:</p>
                <a href="role_management.php">Role Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php elseif ($user["role"] === "manager"): ?>
                <p>You have manager privileges. Choose an option:</p>
                <a href="role_management.php">Role Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <p>You have user privileges. Choose an option:</p>
                <a href="role_management.php">Role Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        <?php else:
            header("Location: login.php"); // Redirect to the login page if the user is not logged in
            exit();
        endif; ?>
    </div>
</body>
</html>
