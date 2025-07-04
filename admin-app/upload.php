<?php
// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure files and title are received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $artist = trim($_POST['artist'] ?? 'Unknown Artist');
    $audio = $_FILES['audio'] ?? null;
    $cover = $_FILES['cover'] ?? null;

    if (!$title || !$audio || !$cover) {
        die("❌ Missing required data.");
    }

    // Validate MIME types
    $validAudioTypes = ['audio/mpeg'];
    $validImageTypes = ['image/jpeg', 'image/png'];

    if (!in_array($audio['type'], $validAudioTypes)) {
        die("❌ Audio must be an MP3 file.");
    }

    if (!in_array($cover['type'], $validImageTypes)) {
        die("❌ Cover must be a JPG or PNG image.");
    }

    // Generate safe, unique filenames
    $musicName = uniqid('track_') . "_" . basename($audio["name"]);
    $coverName = uniqid('cover_') . "_" . basename($cover["name"]);

    $musicPath = "music/" . $musicName;
    $coverPath = "cover/" . $coverName;

    // Attempt to move uploaded files
    $musicSaved = move_uploaded_file($audio["tmp_name"], $musicPath);
    $coverSaved = move_uploaded_file($cover["tmp_name"], $coverPath);

    if (!$musicSaved || !$coverSaved) {
        die("❌ Failed to save uploaded files.");
    }

    // Load existing songs or start a new list
    $songsFile = "songs.json";
    $songs = [];

    if (file_exists($songsFile)) {
        $json = file_get_contents($songsFile);
        $songs = json_decode($json, true) ?? [];
    }

    // Append new song
    $songs[] = [
        "title" => $title,
        "artist" => $artist,
        "file" => $musicPath,
        "cover" => $coverPath
    ];

    // Save updated songs
    file_put_contents($songsFile, json_encode($songs, JSON_PRETTY_PRINT));

    echo "✅ Upload successful! <a href='admin.html'>Back to Admin</a>";
} else {
    die("❌ Invalid request method.");
}
?>
