<?php
// Check the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// If the request is for / or /api/, serve the custom webpage
if ($requestUri === '/' || $requestUri === '/api/' || $requestUri === '/api') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quotes REST API</title>
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
        <h1>My Quotes Palace</h1><br>
        <p>Author: Devon Letendre</p><br>
        <i>"What comes around is all around"</i>
    </body>
    </html>';
    exit;
}

// If it's not the root or /api/, continue with normal processing (likely the API logic)


// Debugging Information (Optional - remove in production)
echo '<pre>';
print_r(getenv('SITE_URL'));
echo '<br>';
print_r($_SERVER);
echo '</pre>';

phpinfo();
