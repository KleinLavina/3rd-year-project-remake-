<div style="color:#020906;  padding: 30px; padding-bottom:80px; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: #8feac8; padding: 20px; border-radius: 8px; width: 250px;">
        <form method="POST" action="pages/file/check_login.php">
            <div style="margin-bottom: 5px;">
                <label for="username" style="display: block; margin-bottom: 5px;font-weight: bold;">USERNAME:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" style="width: 95%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #a9cfd3; color: white;" required>
            </div>

            <div style="margin-bottom: 5px;">
                <label for="passwords" style="display: block; margin-bottom: 5px;font-weight: bold;">PASSWORD:</label>
                <div style="position: relative;">
                    <input type="password" id="passwords" name="passwords" placeholder="Enter your password" style="width: 95%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color: #a9cfd3; color: white;" required>
                    <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 10px; top: 10px; background: none; border: none; color: white; cursor: pointer;">
                        <i id="toggle-icon" class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display: flex; align-items: center; margin:10px;">
                <label style="font-weight: bold; color:#020906; margin-right: 10px;">CAPTCHA:</label>
                <img id="imgcap" onclick="reloadCaptcha();return false;" src="inc/captcha.php" alt="CAPTCHA" style="border-radius: 10px; height: 30px; width: 70px; cursor: pointer;">
                <input type="text" id="captcha" name="captcha" placeholder="Enter CAPTCHA" style="width: 30%; padding: 10px; margin-left: 10px; border-radius: 10px; border: 1px solid #555; background-color: #a9cfd3; color: white;" required>
            </div>

            <?php
            // Display error messages if there is any
            if (isset($_SESSION['error'])) {
                echo '<div style="color: red; text-align: center; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <div>
                <input type="submit" value="Submit" style="width: 95%; padding: 10px; border-radius: 4px; border: 1px solid #555; background-color:#45b1bc color: white; cursor: pointer;">
            </div>
        </form>
    </div>
</div>

<script>
// Reload CAPTCHA image
function reloadCaptcha() {
    var d = new Date();
    document.getElementById("imgcap").src = document.getElementById("imgcap").src.split('?')[0] + '?' + d.getMilliseconds();
}

// Toggle password visibility
function togglePasswordVisibility() {
    var passwordField = document.getElementById("passwords");
    var toggleIcon = document.getElementById("toggle-icon");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.className = "fa fa-eye-slash"; // Change icon to "eye-slash"
    } else {
        passwordField.type = "password";
        toggleIcon.className = "fa fa-eye"; // Change icon to "eye"
    }
}
</script>
