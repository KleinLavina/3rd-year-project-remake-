<?php
session_start();

// Function to generate a random string of 4 characters
function generateCaptchaText($length = 4) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha_text = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha_text .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captcha_text;
}

// Generate CAPTCHA text
$captcha_text = generateCaptchaText(4); // 4 characters long CAPTCHA
$_SESSION['captcha_code'] = $captcha_text; // Store in session

// Create the image
$image = imagecreate(100, 40);  // Adjusted size for 4 characters
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 128, 0); // Darker green text
imagestring($image, 20, 20, 20, $captcha_text, $text_color); // Larger text size and adjusted positioning

// Output image to browser
header('Content-type: image/png');
imagepng($image);
imagedestroy($image); // Clean up image
?>
