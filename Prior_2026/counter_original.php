<?php
$file = 'counter.txt';

// Initialize the counter file if it doesn't exist
if(!file_exists($file)){
    file_put_contents($file, 0);
}

// Read the current count
$count = file_get_contents($file);

// Increment the count
$count++;

// Save the new count back to the file
file_put_contents($file, $count);

// Display the count
echo "This page has been viewed " . $count . " times.";
?>
