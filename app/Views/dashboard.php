<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NutriPlan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome {
            text-align: center;
            margin-bottom: 30px;
        }
        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome">
            <h1> Bienvenue sur NutriPlan !</h1>
            <p>Connexion réussie !</p>
        </div>

        <div class="user-info">
            <h3>Informations utilisateur :</h3>
            <p><strong>Nom :</strong> <?= esc($user['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= esc($user['prenom']) ?></p>
            <p><strong>Email :</strong> <?= esc($user['email']) ?></p>
        </div>

        <div style="text-align: center;">
            <a href="/logout" class="logout-btn">Se déconnecter</a>
        </div>
    </div>
</body>
</html>