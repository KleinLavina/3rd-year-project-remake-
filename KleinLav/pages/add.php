<?php
// Initialize error and success messages
$message = "";

require "file/database.php";
$db = new Database();

// Query to get the highest existing ID
$result = $db->getData("SELECT MAX(p_id) AS max_id FROM persons");
$row = $result->fetch_assoc();
$next_id = $row['max_id'] + 1; // Increment the highest ID to get the next available ID

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $idno = $next_id; // Use the generated ID
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $course = $_POST['course'];
    $username = $_POST['username'];
    $passwords = $_POST['passwords'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($passwords !== $confirm_password) {
        $_SESSION['message'] = "Error: Passwords do not match!";
    } else {
        // Insert new record into the database
        $sql1 = $db->prepare("INSERT INTO persons (p_id, p_fname, p_lname, course ,username, passwords) VALUES (?, ?, ?, ?, ?, ?)");
        $sql1->bind_param("ssssss", $idno, $fname, $lname, $course, $username, $passwords);

        if ($sql1->execute()) {
            // Success message
            $_SESSION['message'] = "New record created successfully!";
        } else {
            // Error in execution
            $_SESSION['message'] = "Error: Failed to execute the query. " . $sql1->error;
        }
        $sql1->close();
    }
}

// Close the database connection
$db->close();
?>

<div style="color: #e0fbe7; padding: 30px; padding-bottom: 80px; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: #28817b; padding: 20px; border-radius: 8px; width: 300px;">
            <form method="POST" action="">
            <div style="text-align: center; color: #e0fbe7; font-size:30px; margin:0 0 20px 0;"><i>Add User</i></div>
                <div style="margin-bottom: 5px;">
                    <!-- Display success or error message -->
                    <?php if (isset($_SESSION['message'])) : ?>
                        <div class="message" style="margin: 5px 0 5px 0;display:flex; justify-content: center;  padding: 5px; color: white; border-radius: 30px; <?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'background-color: red;' : 'background-color: #89f19d;'; ?>">
                            <?php echo $_SESSION['message']; ?>
                        </div>
                        <?php unset($_SESSION['message']); ?> <!-- Clear the session message after displaying it -->
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-around; margin-bottom: 5px;">
                        <div style=" flex: 1; margin-right: 5px;">
                            <label for="id" style="display: block; margin-bottom: 5px;">ID Number:</label>
                            <input type="number" id="id" name="id" value="<?php echo $next_id; ?>" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required readonly placeholder="Enter ID Number">
                        </div>
                        <div style="flex: 1; margin-left: 5px;">
                            <label for="course" style="display: block; margin-bottom: 5px;">Course:</label>
                            <input type="text" id="course" name="course" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Enter Course">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; margin-right: 5px;">
                            <label for="fname" style="display: block; margin-bottom: 5px;">First Name:</label>
                            <input type="text" id="fname" name="fname" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Enter First Name">
                        </div>
                        <div style="flex: 1; margin-left: 5px;">
                            <label for="lname" style="display: block; margin-bottom: 5px;">Last Name:</label>
                            <input type="text" id="lname" name="lname" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Enter Last Name">
                        </div>
                    </div>

                    <div style="margin-bottom: 5px;">
                        <label for="username" style="display: block; margin-bottom: 5px;">Username:</label>
                        <input type="text" id="username" name="username" style="width: 70%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Enter Username">
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; margin-right: 5px;">
                            <label for="passwords" style="display: block; margin-bottom: 5px;">Password:</label>
                            <input type="password" id="passwords" name="passwords" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Enter Password">
                        </div>
                        <div style="flex: 1; margin-left: 5px;">
                            <label for="confirm_password" style="display: block; margin-bottom: 5px;">Confirm Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" style="width: 80%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required placeholder="Confirm Password">
                        </div>
                    </div>

                    <div>
                        <input type="submit" value="Submit" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer;">
                    </div>
                </div>
            </form>
    </div>
</div>
