<!DOCTYPE html>
<html>
<head>
    <title>Créer un Compte Principal</title>
</head>
<body>
    <h1>Créer un Compte Principal</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="/create-account" enctype="multipart/form-data">
        <div>
            <label>Numéro de téléphone:</label>
            <input type="text" name="numTel">
        </div>
        <div>
            <label>Photo CNI Recto:</label>
            <input type="file" name="photoCniRecto">
        </div>
        <div>
            <label>Photo CNI Verso:</label>
            <input type="file" name="photoCniVerso">
        </div>
        <button type="submit">Créer le compte</button>
    </form>
</body>
</html>