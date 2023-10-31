<!DOCTYPE html>
<html>
<head>
    <title>Role Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            background-color: #007BFF;
            color: white;
            padding: 10px;
        }

        a {
            text-decoration: none;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .create-form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Role Management</h1>
    <a href="dashboard.php">Go to Dashboard</a>

    <!-- Create Role Form -->
    <form class="create-form" method="post" action="role_management.php">
        <h2>Create or Edit Role</h2>
        <input type="hidden" name="action" value="create">
        <label for="role_name">Role Name:</label>
        <input type="text" name="role_name" required>
        <label for="permissions">Permissions (comma-separated):</label>
        <input type="text" name="permissions" required>
        <button type="submit">Create/Edit Role</button>
    </form>

    <!-- Display and manage roles here -->
    <table>
        <tr>
            <th>Role</th>
            <th>Permissions</th>
            <th>Action</th>
        </tr>
        <?php
        session_start();
        
        if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
            header("Location: dashboard.php"); // Redirect to the login page if not an admin
            exit();
        }

        $roles = []; // Initialize $roles as an empty array

        if (file_exists("roles.json")) {
            $roles = json_decode(file_get_contents("roles.json"), true);
            if ($roles === null) {
                die("Error decoding roles.json: " . json_last_error_msg());
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
            $action = $_POST["action"];
            $roleName = $_POST["role_name"];
            $permissions = isset($_POST["permissions"]) ? explode(',', $_POST["permissions"]) : [];

            if ($action === "create") {
                $newRole = [
                    "name" => $roleName,
                    "permissions" => $permissions
                ];
                $roles[] = $newRole;
            } elseif ($action === "edit") {
                // Check if we are editing a role
                $editRoleName = $_POST["editRole"]; // New hidden field
                foreach ($roles as $key => $role) {
                    if ($role["name"] === $editRoleName) { // Compare with the editRoleName
                        $roles[$key]["name"] = $roleName;
                        $roles[$key]["permissions"] = $permissions;
                        break;
                    }
                }
            } elseif ($action === "delete") {
                foreach ($roles as $key => $role) {
                    if ($role["name"] === $roleName) {
                        array_splice($roles, $key, 1);
                        break;
                    }
                }
            }
            file_put_contents("roles.json", json_encode($roles));
        }     
        
foreach ($roles as $role) {
    echo '<tr>';
    echo '<td>' . $role["name"] . '</td>';
    echo '<td>' . implode(", ", $role["permissions"]) . '</td>';
    echo '<td class="action-buttons">';
    // Add your edit and delete forms here
    echo '<form method="post" action="role_management.php">';
    echo '<input type="hidden" name="action" value="edit">';
    echo '<input type="hidden" name="editRole" value="' . $role["name"] . '">';
    echo '<input type="hidden" name="role_name" value="' . $role["name"] . '">';
    // Add an input field for permissions and set its value
    echo '<label for="edit_permissions">Edit Permissions:</label>';
    echo '<input type="text" name="permissions" value="' . implode(", ", $role["permissions"]) . '" required>';
    echo '<button type="submit">Edit</button>';
    echo '</form>';
    echo '<form method="post" action="role_management.php">';
    echo '<input type="hidden" name="action" value="delete">';
    echo '<input type="hidden" name="role_name" value="' . $role["name"] . '">';
    echo '<button type="submit">Delete</button>';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
}




        ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>
