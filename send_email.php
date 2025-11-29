<?php
// send_email.php

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

// Get form data
$name = strip_tags(trim($_POST["name"]));
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
$tv_username = strip_tags(trim($_POST["tv-username"]));
$service_type = strip_tags(trim($_POST["service-type"]));
$markets = strip_tags(trim($_POST["markets"]));
$timeframes = strip_tags(trim($_POST["timeframes"]));
$description = strip_tags(trim($_POST["description"]));
$rules_ready = strip_tags(trim($_POST["rules-ready"]));

// Validation
if (empty($name) || empty($description) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields correctly."]);
    exit;
}

// Email Configuration
$recipient = "info@kodologic.com"; // REPLACE THIS WITH YOUR ACTUAL EMAIL
$subject = "New Project Request from $name";

// Email Content
$email_content = "Name: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "TradingView Username: $tv_username\n";
$email_content .= "Service Type: $service_type\n";
$email_content .= "Markets: $markets\n";
$email_content .= "Timeframes: $timeframes\n";
$email_content .= "Rules Ready: $rules_ready\n\n";
$email_content .= "Description:\n$description\n";

// Email Headers
// IMPORTANT: The 'From' address MUST be an email created on your Hostinger domain (e.g., info@kodologic.com)
// to prevent the email from being blocked as spam/spoofing.
$headers = "From: info@kodologic.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send Email
if (mail($recipient, $subject, $email_content, $headers)) {
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Oops! Something went wrong and we couldn't send your message."]);
}
?>
