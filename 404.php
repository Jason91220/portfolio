<?php
// Envoyer l'en-tête HTTP 404 Not Found
header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        h1 {
            font-size: 36px;
            color: #3498db;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #3498db;
            margin: 0;
            line-height: 1;
            opacity: 0.2;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .search-box {
            margin: 20px 0;
        }
        .search-box input {
            padding: 10px;
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-box button {
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .suggestions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .suggestions h3 {
            color: #555;
            font-size: 18px;
        }
        .suggestions ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .suggestions li {
            margin: 5px;
        }
        .suggestions a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #f1f1f1;
            color: #333;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .suggestions a:hover {
            background-color: #e1e1e1;
        }
    </style>
</head>
<body>
    <div class="error-code">404</div>
    <div class="container">
        <h1>Page introuvable</h1>
        <p>Oups ! La page que vous recherchez semble avoir disparu.</p>
        <p>L'URL que vous avez saisie n'existe pas sur ce site.</p>
        
        <div class="search-box">
            <form action="/" method="get">
                <input type="text" name="search" placeholder="Rechercher sur le site...">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        
        <a href="/" class="btn">Retour à l'accueil</a>
        
        <div class="suggestions">
            <h3>Vous pourriez être intéressé par :</h3>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/about.php">À propos</a></li>
                <li><a href="/projects.php">Projets</a></li>
                <li><a href="/skills.php">Compétences</a></li>
                <li><a href="/contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
