<?php
// Connect to the database
$db = new mysqli("localhost", "root", "", "nyxify-registration");

// Check if the connection was successful
if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

// Handle search query
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    $searchTerm = $_POST['search_term'];
    $searchQuery = "SELECT DISTINCT p_id, p_fname, p_lname, course, username, passwords FROM persons 
                     WHERE p_id LIKE ? OR p_fname LIKE ? OR p_lname LIKE ? OR username LIKE ?";

    $stmt = $db->prepare($searchQuery);
    $searchTermWithWildcards = "%$searchTerm%";
    $stmt->bind_param("ssss", $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
    $stmt->close();
}

// Handle record update
$updateMessage = ""; // Variable to store the update message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_record'])) {
    $id = $_POST['id'];
    $p_fname = $_POST['p_fname'];
    $p_lname = $_POST['p_lname'];
    $course = $_POST['course'];
    $username = $_POST['username'];
    $passwords = $_POST['passwords'];

    // Validate that all inputs are not empty
    if (!empty($id) && !empty($p_fname) && !empty($p_lname) && !empty($course) && !empty($username) && !empty($passwords)) {
        // Prepare the SQL UPDATE query
        $sql = "UPDATE `persons` 
                SET `p_fname` = ?, `p_lname` = ?, `course` = ?, `username` = ?, `passwords` = ? 
                WHERE `p_id` = ?";

        // Prepare the statement
        if ($stmt = $db->prepare($sql)) {
            // Bind parameters to the query
            $stmt->bind_param("sssssi", $p_fname, $p_lname, $course, $username, $passwords, $id);

            // Execute the query
            if ($stmt->execute()) {
                $updateMessage = "<div style='margin-top: 10px; padding: 10px; color: white; background-color: #89f19d;'>Record updated successfully!</div>";
            } else {
                $updateMessage = "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>Error executing query: " . $stmt->error . "</div>";
            }

            // Close the statement
            $stmt->close();
        } else {
            $updateMessage = "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>Error preparing statement: " . $db->error . "</div>";
        }
    } else {
        $updateMessage = "<div style='margin-top: 10px; padding: 10px; color: white; background-color: red;'>All fields are required.</div>";
    }
}
?>

<!-- HTML Form -->
<div style="color: #e0fbe7; padding: 30px; padding-bottom: 80px; display: flex; justify-content: center; align-items: center; background-color: #040905;">
    <div style="background-color: #28817b; padding: 20px; border-radius: 8px; width: 400px;">
        <form method="POST" action="">
            <!-- Display success or error message at the top -->
            <?php if ($updateMessage) : ?>
                <div style="margin-bottom: 20px;">
                    <?= $updateMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Search field and button on the same line, with label above input -->
            <div style="margin-bottom: 10px;">
                <label for="search_term" style="display: block; margin-bottom: 5px;">Search for a record:</label>
                <div style="display: flex; align-items: center;">
                    <input type="text" name="search_term" id="search_term" placeholder="Search by ID, name, or username"
                        style="width: 70%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
                    <input type="submit" value="Search" style="width: auto; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer; margin-left: 10px;">
                </div>
            </div>
        </form>

        <!-- Display search results -->
        <?php if (!empty($searchResults)) : ?>
            <form method="POST" action="">
            <div style="margin-top: 20px;">
                <label for="id" style="display: block; margin-bottom: 5px;">Match Record:</label>
                <select name="id" id="id" style="width: 100%; padding: 10px;margin-bottom: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required onchange="autoFill(this.value)">
                    <option value="">Select a Record to update</option>
                    <?php foreach ($searchResults as $row) : ?>
                        <option value="<?= $row['p_id']; ?>" data-fname="<?= $row['p_fname']; ?>" data-lname="<?= $row['p_lname']; ?>" data-course="<?= $row['course']; ?>" data-username="<?= $row['username']; ?>" data-password="<?= $row['passwords']; ?>">
                            <?= $row['p_id']; ?> - <?= $row['p_fname']; ?> <?= $row['p_lname']; ?> (<?= $row['course']; ?>, <?= $row['username']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Autofilled form fields for editing -->
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <div style="flex: 1;">
                    <label for="p_fname" style="display: block; margin-bottom: 5px;">First Name:</label>
                    <input type="text" name="p_fname" id="p_fname" style="width: 80%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
                </div>

                <div style="flex: 1;">
                    <label for="p_lname" style="display: block; margin-bottom: 5px;">Last Name:</label>
                    <input type="text" name="p_lname" id="p_lname" style="width: 80%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
                </div>

                <div style="flex: 1;">
                    <label for="course" style="display: block; margin-bottom: 5px;">Course:</label>
                    <input type="text" name="course" id="course" style="width: 80%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
                </div>
            </div>

            <div style="margin-bottom: 10px;">
                <label for="username" style="display: block; margin-bottom: 5px;">Username:</label>
                <input type="text" name="username" id="username" style="width: 95%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div style="margin-bottom: 10px;">
                <label for="passwords" style="display: block; margin-bottom: 5px;">Password:</label>
                <input type="text" name="passwords" id="passwords" style="width: 95%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
            </div>

            <div>
                <input type="submit" name="update_record" value="Update Record" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer;">
            </div>
            </form>
        <?php else : ?>
            <!-- Display message when no search results are found -->
            <?php if (isset($_POST['search_term']) && empty($searchResults)) : ?>
                <div style="margin-top: 20px; color: white; background-color: orange; padding: 10px;">
                    No records found matching your search.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php
// Close the database connection
$db->close();
?>

<!-- JavaScript to auto-fill the form fields -->
<script>
    function autoFill(id) {
        const select = document.getElementById('id');
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById('p_fname').value = selectedOption.getAttribute('data-fname');
        document.getElementById('p_lname').value = selectedOption.getAttribute('data-lname');
        document.getElementById('course').value = selectedOption.getAttribute('data-course');
        document.getElementById('username').value = selectedOption.getAttribute('data-username');
        document.getElementById('passwords').value = selectedOption.getAttribute('data-password');
    }
</script>
