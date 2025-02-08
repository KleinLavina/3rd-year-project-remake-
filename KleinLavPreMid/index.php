<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Simple Website</title>
	<link rel="stylesheet" href="css/style.css"> 
	<link rel="stylesheet" href="css/loginstyle.css">
</head>
<body>
<header>
<?php 
	include "element/header.php";
?>
</header>

<nav>
<?php 
	include "element/nav.php";
?>
</nav>
<div>
<?php 
	if (isset($_GET['page'])) {
		$pg = $_GET['page'];
			if ($pg == "home") {
				include "pages/home.php";
				}
				elseif ($pg == "add") {
				include "pages/add.php";
				}
				elseif ($pg == "edit") {
				include "pages/edit.php";
				}
				elseif ($pg == "about") {
				include "pages/about.php";
				}
				elseif ($pg == "delete") {
				include "pages/delete_remake.php";
				}
				elseif ($pg == "login") {
				include "pages/login.php";
				}
				elseif ($pg == "list") {
				include "pages/list_remake.php";
				}
		else {
				include "pages/home.php";
					}
		} else { 
			include "pages/home.php";
		}	
			?>
</div>	
</body>

<?php 
	include "element/footer.php";
?>
</html>
