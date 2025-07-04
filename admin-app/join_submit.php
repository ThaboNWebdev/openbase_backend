<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $bio = $_POST['bio'] ?? '';

  $entry = "Name: $name\nEmail: $email\nBio: $bio\n---\n";
  file_put_contents("join_requests.txt", $entry, FILE_APPEND);

  echo "<h2>Thanks for joining, $name!</h2><p>We received your info successfully.</p>";
}
?>
