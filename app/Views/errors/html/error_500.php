<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { color: #d32f2f; margin: 0 0 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo isset($heading) ? htmlspecialchars($heading) : 'Error'; ?></h1>
        <p><?php echo isset($message) ? htmlspecialchars($message) : 'An error occurred.'; ?></p>
    </div>
</body>
</html>
