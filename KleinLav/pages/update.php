<?php
// Connect to the database
$db = new mysqli("localhost", "root", "", "nyxify-registration");

// Check if the connection was successful
if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

// Fetch options for the dropdowns
$fields = ['p_id', 'p_fname', 'p_lname', 'course'];
$selectQuery = "SELECT DISTINCT p_id, p_fname, p_lname, course FROM persons";
$results = $db->query($selectQuery);
?>

<!-- HTML form -->
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected field and id from form submission
    $field = $_POST['field'];
    $id = $_POST['id'];
    $p_fname = $_POST['p_fname'];
    $p_lname = $_POST['p_lname'];
    $course = $_POST['course'];

    // Validate that all inputs are not empty
    if (!empty($id) && !empty($p_fname) && !empty($p_lname) && !empty($course)) {
        // Prepare the SQL UPDATE query based on selected field
        $sql = "UPDATE `persons` 
                SET `p_fname` = ?, `p_lname` = ?, `course` = ? 
                WHERE `$field` = ?";

        // Prepare the statement
        if ($stmt = $db->prepare($sql)) {
            // Bind parameters to the query
            $stmt->bind_param("sssi", $p_fname, $p_lname, $course, $id);

            // Execute the query
            if ($stmt->execute()) {
                echo "Record updated successfully!";
            } else {
                echo "Error executing query: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $db->error;
        }
    } else {
        echo "All fields are required.";
    }
}

// Close the database connection
$db->close();
?>
