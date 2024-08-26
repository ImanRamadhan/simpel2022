<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background: #fff;
            padding: 2rem 4rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .error-code {
            font-size: 6rem;
            color: #ff4c4c;
            margin: 0;
        }

        .error-message {
            font-size: 1.5rem;
            color: #333;
            margin: 0.5rem 0;
        }

        .suggestion {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .home-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! Page Not Found</div>
        <div class="suggestion">It looks like the page you're looking for doesn't exist.</div>
        <p><a href="<?php echo base_url() . $link; ?>" class="home-button">Go Back to Home </a></p>
    </div>
</body>

</html>