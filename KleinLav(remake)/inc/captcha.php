<?php
session_start();

// Function to generate a random string of 4 characters
function generateCaptchaText($length = 4) {
    $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789';
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
$image = imagecreate(200, 80);  // Adjusted size for larger text
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 128, 0); // Darker green text

// Set the font size (higher number = larger text)
$font_size = 40;

// Path to a .ttf font file (ensure you have the font file in the correct path)
$font_path = 'font/myfont.ttf';

// Calculate text box width and height to center the text
$text_box = imagettfbbox($font_size, 0, $font_path, $captcha_text);
$text_width = $text_box[2] - $text_box[0];
$text_height = $text_box[1] - $text_box[7];

// Set the text position (centered horizontally and vertically)
$x = (imagesx($image) - $text_width) / 2;
$y = (imagesy($image) + $text_height) / 2;

// Add the text to the image
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font_path, $captcha_text);

// Output image to browser
header('Content-type: image/png');
imagepng($image);
imagedestroy($image); // Clean up image
?>
