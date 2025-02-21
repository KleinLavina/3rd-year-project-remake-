<?php

// Database connection
require "file/database.php";
$db = new Database();

// Initialize search variables
$search_query = "";
$search_term = "";

// Check if form is submitted via POST for search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    $search_term = $_POST['search_term']; // Get the search term from the form
    $search_query = " WHERE username LIKE ? OR p_fname LIKE ? OR p_lname LIKE ? OR p_id LIKE ?";
}

// Prepare the SQL query based on search term
$sql = "SELECT * FROM persons" . $search_query;
$stmt = $db->prepare($sql);

if (!empty($search_query)) {
    $search_term_like = "%" . $search_term . "%";
    $stmt->bind_param("ssss", $search_term_like, $search_term_like, $search_term_like, $search_term_like);
}

$stmt->execute();
$result = $stmt->get_result();

// Handle Grant Admin or Revoke Admin action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_admin'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        $user_id = $_POST['user_id'];
        $current_status = $_POST['current_status'];
        
        // Toggle Admin privileges
        if ($current_status == 1) {
            // Revoke Admin privileges
            $update_sql = "UPDATE persons SET is_admin = 0 WHERE p_id = ?";
            $action = "revoked";
        } else {
            // Grant Admin privileges
            $update_sql = "UPDATE persons SET is_admin = 1 WHERE p_id = ?";
            $action = "granted";
        }

        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("i", $user_id);
        
        if ($update_stmt->execute()) {
            echo "<div style='text-align: center; background-color: #89f19d; color: white; padding: 10px; border-radius: 4px;'>Admin privileges have been $action for User with ID $user_id.</div>";
        } else {
            echo "<div style='text-align: center; background-color: #d9534f; color: white; padding: 10px; border-radius: 4px;'>Failed to update admin privileges for User with ID $user_id.</div>";
        }
    } else {
        echo "<div style='text-align: center; background-color: #d9534f; color: white; padding: 10px; border-radius: 4px;'>You do not have permission to modify admin rights.</div>";
    }
}

// Display the search form (accessible to all users)
echo "
<div style='color: #e0fbe7; padding: 30px; padding-bottom: 80px; display: flex; justify-content: center; align-items: center;'>
    <div style='text-align: center; padding: 20px; background-color: #28817b; border-radius: 8px; width: 400px;'>
        <form method='POST' action=''>
            <label for='search_term' style='color: #e0fbe7; font-size: 30px;'><i>Search Users: </i></label><br>
            <input type='text' name='search_term' id='search_term' value='" . htmlspecialchars($search_term) . "' placeholder='Enter user ID, name or username' style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white; width:70%;' required>
            <input type='submit' value='Search' style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer;'>
        </form>
    </div>
</div>
";

// Display the list of users (accessible to all users)
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10' cellspacing='0' style='margin: auto; margin-bottom: 200px; border-collapse: collapse; width: 90%; background-color: #222; color: #fff;'>";
    echo "<tr style='background-color: #28817b; color: #e0fbe7; font-weight: bold;'>
            <th>ID</th><th>First Name</th><th>Last Name</th><th>Course</th><th>Username</th><th>Password</th><th>Admin Status</th><th>Action</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr style='background-color: #333;'>";
        echo "<td>" . htmlspecialchars($row['p_id']) . "</td>";  // ID
        echo "<td>" . htmlspecialchars($row['p_fname']) . "</td>"; // First Name
        echo "<td>" . htmlspecialchars($row['p_lname']) . "</td>"; // Last Name
        echo "<td>" . htmlspecialchars($row['course']) . "</td>";  // Course

        // Check if the user is logged in and their role
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'true') {
            // Admin can see usernames and passwords
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";  // Username
                echo "<td>" . htmlspecialchars($row['passwords']) . "</td>";  // Password
            } else {
                // Regular user can only see masked usernames and passwords
                echo "<td>**********</td>";  // Masked Username
                echo "<td>**********</td>";  // Masked Password
            }
        } else {
            // Non-logged-in users can only see masked usernames and passwords
            echo "<td>**********</td>";  // Masked Username
            echo "<td>**********</td>";  // Masked Password
        }

        // Show Admin status
        if ($row['is_admin'] == 1) {
            echo "<td style='color: lightgreen;'>Admin</td>";
        } else {
            echo "<td style='color: lightcoral;'>User</td>";
        }

        // Show toggle button for admin privileges
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            if ($row['is_admin'] == 1) {
                // Show Revoke Admin button
                echo "
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='user_id' value='" . htmlspecialchars($row['p_id']) . "'>
                        <input type='hidden' name='current_status' value='1'>
                        <input type='submit' name='toggle_admin' value='Revoke Admin' style='background-color: #d9534f; color: white; padding: 5px 10px; border-radius: 4px;'>
                    </form>
                </td>";
            } else {
                // Show Grant Admin button
                echo "
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='user_id' value='" . htmlspecialchars($row['p_id']) . "'>
                        <input type='hidden' name='current_status' value='0'>
                        <input type='submit' name='toggle_admin' value='Grant Admin' style='background-color: #89f19d; color: white; padding: 5px 10px; border-radius: 4px;'>
                    </form>
                </td>";
            }
        } else {
            echo "<td></td>"; // Empty for users who aren't admins
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div style='text-align: center; background-color: #f0ad4e; color: white; padding: 10px; border-radius: 4px;'>No users found.</div>";
}

$db->close();
?>
