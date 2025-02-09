<?php
// Ensure session is started


// Initialize error and success messages
$message = "";

// Database connection
$db = new mysqli("localhost", "root", "", "nyxify-registration");

if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

// Query to get the highest existing ID
$result = $db->query("SELECT MAX(p_id) AS max_id FROM persons");
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

// Close the database connection
$db->close();
?>

<div style="color: #e0fbe7; padding: 30px; padding-bottom: 80px; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: #28817b; padding: 20px; border-radius: 8px; width: 250px;">
        <form method="POST" action="">
            <div style="margin-bottom: 5px;">
                <!-- Display success or error message -->
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="message" style="margin: 5px 0 5px 0; padding: 5px; color: white; <?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'background-color: red;' : 'background-color: #89f19d;'; ?>">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); ?> <!-- Clear the session message after displaying it -->
                <?php endif; ?>

                <label for="id" style="display: block; margin-bottom: 5px;">ID Number:</label>
                <!-- Display the next available ID -->
                <input type="number" id="id" name="id" value="<?php echo $next_id; ?>" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required readonly>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="fname" style="display: block; margin-bottom: 5px;">First Name:</label>
                <input type="text" id="fname" name="fname" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="lname" style="display: block; margin-bottom: 5px;">Last Name:</label>
                <input type="text" id="lname" name="lname" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="course" style="display: block; margin-bottom: 5px;">Course:</label>
                <input type="text" id="course" name="course" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="username" style="display: block; margin-bottom: 5px;">Username:</label>
                <input type="text" id="username" name="username" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="passwords" style="display: block; margin-bottom: 5px;">Password:</label>
                <input type="text" id="passwords" name="passwords" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div>
                <input type="submit" value="Submit" style="width: 95%; padding: 5px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer;">
            </div>
        </form>
    </div>
</div>
