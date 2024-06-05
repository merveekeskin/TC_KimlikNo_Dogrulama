<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T.C. Kimlik Doğrulama</title>
</head>
<body>
    <h1>T.C. Kimlik Doğrulama</h1>
    <form action="dogrula.php" method="post">
        <label for="tc_kimlik_no">T.C. Kimlik No:</label>
        <input type="text" id="tc_kimlik_no" name="tc_kimlik_no" required> <! required: Alanın zorunlu olduğunu belirtir, boş bırakılamaz. -->
        <button type="submit">Doğrula</button>
    </form>
</body>
</html>