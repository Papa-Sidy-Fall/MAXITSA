<!DOCTYPE html>
<html>
<head>
    <title>Mon Compte</title>
</head>
<body>
    <h1>Mon Compte Principal</h1>
    <p>Numéro de téléphone: <?= $compte->getNumTel() ?></p>
    <p>Solde: <?= number_format($compte->getSolde(), 2) ?> €</p>
    <p>Type de compte: <?= ucfirst($compte->getTypeDeCompte()) ?></p>
    <a href="/logout">Déconnexion</a>
</body>
</html>