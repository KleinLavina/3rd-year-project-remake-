<?php

// Database connection
$db = new mysqli("localhost", "root", "", "nyxify-registration");

if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

// If the user is logged in and has admin privileges
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'true') {
    // Check if the user is an admin
    if ($_SESSION['role'] == 'admin') {
        // Admin can grant admin rights to others
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];

            // Check if the user exists in the database
            $check_user_sql = "SELECT p_id FROM persons WHERE p_id = ?";
            $stmt = $db->prepare($check_user_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If user exists, update their admin status
                $update_sql = "UPDATE persons SET is_admin = 1 WHERE p_id = ?";
                $stmt = $db->prepare($update_sql);
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute()) {
                    echo "<div style='text-align: center; color: green;'>User $user_id has been granted admin privileges!</div>";
                } else {
                    echo "<div style='text-align: center; color: red;'>Error granting admin privileges!</div>";
                }
            } else {
                echo "<div style='text-align: center; color: red;'>User with ID $user_id not found!</div>";
            }
        }

        // Display the form to grant admin rights
        echo "
        <div style='text-align: center; margin: 20px 0 20px 0;'>
            <form method='POST' action=''>
                <label for='user_id' style='font-weight: bold;'>Grant Admin to User (Enter User ID):</label><br>
                <input type='text' id='user_id' name='user_id' required style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #555; background-color: #666; color: white;'>
                <input type='submit' value='Grant Admin' style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #555; background-color: #007BFF; color: white; cursor: pointer;'>
            </form>
        </div>
        ";

    } else {
        // Regular users should not be able to grant admin rights
        echo "<div style='text-align: center; color: red;'>You do not have permission to grant admin rights.</div>";
    }

    // Show the list of users
    $sql = "SELECT * FROM persons";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Start the table
        echo "<table border='1' cellpadding='10' cellspacing='0' style='margin: auto;'>";
        echo "<tr style='background-color: cyan; color: black;'><th>ID</th><th>First Name</th><th>Last Name</th><th>Course</th><th>Username</th><th>Password</th><th>Admin Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['p_id']) . "</td>";  // ID
            echo "<td>" . htmlspecialchars($row['p_fname']) . "</td>"; // First Name
            echo "<td>" . htmlspecialchars($row['p_lname']) . "</td>"; // Last Name
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";  // Course

            // Show username and password if admin
            if ($_SESSION['role'] == 'admin') {
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";  // Username
                echo "<td>" . htmlspecialchars($row['passwords']) . "</td>";  // Password
            } else {
                echo "<td>**********</td>";  // Masked Username
                echo "<td>**********</td>";  // Masked Password
            }

            // Show Admin status
            if ($row['is_admin'] == 1) {
                echo "<td>Admin</td>";
            } else {
                echo "<td>User</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<div style='text-align: center; color: red;'>No users found.</div>";
    }

} else {
    echo "<div style='text-align: center; color: red;'>You must be logged in to access this page!</div>";
}

// Close the database connection
$db->close();
?>
