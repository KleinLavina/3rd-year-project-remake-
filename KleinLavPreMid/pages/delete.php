<?php
// Connect to the database
$db = new mysqli("localhost", "root", "", "nyxify-registration");

// Check if the connection was successful
if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

// Fetch options for the dropdowns (including passwords field)
$fields = ['p_id', 'p_fname', 'p_lname', 'course', 'username', 'passwords'];
$selectQuery = "SELECT DISTINCT p_id, p_fname, p_lname, course, username, passwords FROM persons";
$results = $db->query($selectQuery);
?>

<!-- HTML form -->
<div style="color: white; padding: 30px; padding-bottom:80px; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: #444; padding: 20px; border-radius: 8px; width: 400px;">
        <form method="POST" action="">
            <!-- Dropdown to select the field -->
            <div style="margin-bottom: 10px;">
                <label for="field" style="display: block; margin-bottom: 5px;">Select Field to Filter By:</label>
                <select name="field" id="field" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #666; color: white;" required>
                    <option value="p_id">Person ID</option>
                    <option value="p_fname">First Name</option>
                    <option value="p_lname">Last Name</option>
                    <option value="course">Course</option>
                    <option value="username">Username</option>
                    <option value="passwords">Passwords</option>
                </select>
            </div>

            <!-- Dropdown to select the ID based on the field chosen -->
            <div style="margin-bottom: 10px;">
                <label for="id" style="display: block; margin-bottom: 5px;">Select Record to Delete:</label>
                <select name="id" id="id" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #666; color: white;" required>
                    <option value="">Select a Record</option>
                    <?php while ($row = $results->fetch_assoc()) : ?>
                        <option value="<?= $row['p_id']; ?>">
                            <?= $row['p_id']; ?> - <?= $row['p_fname']; ?> <?= $row['p_lname']; ?> (<?= $row['course']; ?>, <?= $row['username']; ?>, <?= $row['passwords']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <input type="submit" value="Delete" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #d9534f; color: white; cursor: pointer;">
            </div>
        </form>

        <!-- Display success or error message -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the selected field and id from form submission
            $field = $_POST['field'];
            $id = $_POST['id'];

            // Validate that the ID is not empty
            if (!empty($id)) {
                // Prepare the SQL DELETE query based on selected field
                $sql = "DELETE FROM `persons` WHERE `$field` = ?";

                // Prepare the statement
                if ($stmt = $db->prepare($sql)) {
                    // Bind parameters to the query
                    $stmt->bind_param("i", $id); // Bind the id as an integer

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: green;'>Record deleted successfully!</div>";
                    } else {
                        echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>Error executing query: " . $stmt->error . "</div>";
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>Error preparing statement: " . $db->error . "</div>";
                }
            } else {
                echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>Please select a record to delete.</div>";
            }
        }
        ?>
    </div>
</div>

<?php
// Close the database connection
$db->close();
?>