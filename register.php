<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Store user data in users.json (simplified; should be enhanced for security)
    $user = [
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "role" => "user" // Default role
    ];

    $users = json_decode(file_get_contents("users.json"), true);
    $users[] = $user;
    file_put_contents("users.json", json_encode($users));
    echo "Registration successful. <a href='login.php'>Login</a>";
}
?>


<html>
<head>
    <title>Registration</title>
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
            width: 300px;
            margin: 0 auto;
            margin-top: 100px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #888888;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration</h2>
        <form method="post" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
