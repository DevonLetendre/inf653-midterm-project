<?php
// Serve custom webpage when accessing the root or /api/
if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/api/') {
    // Output the HTML directly
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome</title>
        <style>
            body {
                background-color: grey;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: white;
                font-size: 24px;
                font-family: Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <h1>Your Name</h1>
    </body>
    </html>';
    exit;
}

// Debugging Information (Optional - remove in production)
echo '<pre>';
print_r(getenv('SITE_URL'));
echo '<br>';
print_r($_SERVER);
echo '</pre>';

phpinfo();
