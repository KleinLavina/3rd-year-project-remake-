<?php
session_start(); // Start the session

// Check if the logout request is made
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Log out the user and destroy the session
    session_unset();
    session_destroy();
    // Redirect to home page after logging out
    header("Location: index.php"); // Adjust the location to where you want to redirect
    exit();
}
?>

<style>
    nav {
        display: flex;
        justify-content: center;
        background:  #031708;
        padding: 5px 15px; /* Reduced padding */
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2), 0 2px 4px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
        position: sticky;
        top: 0;
        z-index: 1000; /* Ensures it stays on top */
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 15px; /* Reduced gap between links */
    }

    nav li {
        margin: 0;
        position: relative; /* Needed for the underline effect */
        border-right: 1px solid #666; /* Add vertical line */
        padding-right: 15px; /* Reduced space before the line */
    }

    nav li:last-child {
        border-right: none; /* Remove the line from the last item */
    }

    nav a {
        color: white;
        padding: 10px 20px; /* Reduced padding for smaller links */
        text-decoration: none;
        position: relative;
        display: inline-block;
        border-radius: 5px; /* Slightly round the edges of the links */
        transition: color 0.3s ease, transform 0.3s ease, background-color 0.3s ease; /* Smooth transition */
        font-size: 14px; /* Reduced font size for a more compact look */
    }

    nav a:hover:not(.active) { /* Apply hover only when not active */
        color: rgb(22, 195, 201); /* Updated hover color */
        background-color: rgba(255, 255, 255, 0.1); /* Add a hover background effect */
        transform: scale(1.21); /* Slight zoom on hover */
        font-style: italic;
    }

    /* Sliding underline effect */
    nav a::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: rgb(22, 195, 201); /* Updated underline color */
        transition: width 0.3s ease, left 0.3s ease;
    }

    nav a:hover:not(.active)::after {
        width: 100%;
        left: 0;
    }

    /* Active link indicator */
    nav a.active {
        color: rgb(22, 195, 201); /* Set active color */
        border-bottom: 2px solid rgb(22, 195, 201); /* Updated active link indicator */
    }
</style>

<nav>
    <ul>
        <li><a href="?page=home" id="home">Home</a></li>
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <!-- Admin-specific menu items -->
            <li><a href="?page=add" id="add">Add</a></li>
            <li><a href="?page=edit" id="edit">Edit</a></li>
            <li><a href="?page=delete" id="delete">Delete</a></li>
        <?php endif; ?>
        <li><a href="?page=list" id="list">List</a></li>

        <!-- Conditionally show Login/Logout based on session status -->
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'true'): ?>
            <!-- If logged in, show Log Out -->
            <li><a href="?logout=true" id="logout">Log Out</a></li>
        <?php else: ?>
            <!-- If not logged in, show Log In -->
            <li><a href="?page=login" id="login">Log In</a></li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    // Get all navigation links
    const navLinks = document.querySelectorAll('nav a');

    // Function to set the active class on the clicked link
    function setActiveLink() {
        // Remove active class from all links
        navLinks.forEach(link => link.classList.remove('active'));

        // Add active class to the clicked link
        this.classList.add('active');
    }

    // Add click event listener to each link
    navLinks.forEach(link => link.addEventListener('click', setActiveLink));

    // Optional: Check the current page from the URL and set active class accordingly
    const currentPage = window.location.search;
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
</script>
