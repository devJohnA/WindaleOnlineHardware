<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Database</title>
</head>
<body>
    <h1>Import Database</h1>
    <form action="import.php" method="post" enctype="multipart/form-data">
        <label for="sqlFile">Select SQL File to Import:</label>
        <input type="file" name="sqlFile" id="sqlFile" accept=".sql" required>
        <button type="submit" name="import">Import</button>
    </form>
</body>
</html>
