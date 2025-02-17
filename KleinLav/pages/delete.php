<?php
// Connect to the database
require "file/database.php";
$db = new Database();


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

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Validate that the ID is not empty
    if (!empty($id)) {
        // Prepare the SQL DELETE query based on the selected ID
        $sql = "DELETE FROM `persons` WHERE `p_id` = ?";

        // Prepare the statement
        if ($stmt = $db->prepare($sql)) {
            // Bind parameters to the query
            $stmt->bind_param("i", $id); // Bind the id as an integer

            // Execute the query
            if ($stmt->execute()) {
                echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: #89f19d;'>Record deleted successfully!</div>";
            } else {
                echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: #d9534f;'>Error executing query: " . $stmt->error . "</div>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: #d9534f;'>Error preparing statement: " . $db->error . "</div>";
        }
    } else {
        echo "<div style='margin-top: 10px; padding: 10px; color: white; background-color: #d9534f;'>Please select a record to delete.</div>";
    }
}
?>

<!-- HTML Form -->
<div style="color: #e0fbe7; padding: 30px; padding-bottom:80px; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: #28817b; padding: 20px; border-radius: 8px; width: 400px;">
        <form method="POST" action="">
            
            <!-- Search field and button on the same line, with label above input -->
            <div style="margin-bottom: 10px;">
            <div style="text-align: center; color: #e0fbe7; font-size:30px; margin:0 0 20px 0;"><i>Delete User</i></div>
                <label for="search_term" style="display: block; margin-bottom: 5px; color: #e0fbe7;">Search for a record:</label>
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
                    <label for="delete_id" style="display: block; margin-bottom: 5px; color: #e0fbe7;">Matched Record:</label>
                    <select name="delete_id" id="delete_id" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #555; background-color: #67afc1; color: white;" required>
                        <option value="">Select a Record</option>
                        <?php foreach ($searchResults as $row) : ?>
                            <option value="<?= $row['p_id']; ?>">
                                <?= $row['p_id']; ?> - <?= $row['p_fname']; ?> <?= $row['p_lname']; ?> (<?= $row['course']; ?>, <?= $row['username']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <input type="submit" value="Delete" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #89f19d; color: white; cursor: pointer;">
                </div>
            </form>
        <?php elseif (isset($_POST['search_term'])) : ?>
            <div style="margin-top: 20px; color: white; background-color: #f0ad4e; padding: 10px;">
                No records found matching your search.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Close the database connection
$db->close();
?>
