<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "UsersCVInfo";

/**
 * Établit une connexion à la base de données.
 *
 * @param string $servername Nom du serveur.
 * @param string $username Nom d'utilisateur.
 * @param string $password Mot de passe.
 *
 * @return mysqli|null Retourne l'objet de connexion ou null en cas d'erreur.
 */
function connectToDatabase($servername, $username, $password)
{
    $conn = new mysqli($servername, $username, $password);

    // Vérifie la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

/**
 * Crée la base de données s'il n'existe pas déjà.
 *
 * @param mysqli $conn Objet de connexion à la base de données.
 * @param string $dbname Nom de la base de données.
 */
function createDatabase($conn, $dbname)
{
    // Crée la base de données
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }
}

/**
 * Sélectionne la base de données spécifiée.
 *
 * @param mysqli $conn Objet de connexion à la base de données.
 * @param string $dbname Nom de la base de données à sélectionner.
 */
function selectDatabase($conn, $dbname)
{
    // Sélectionne la base de données
    $conn->select_db($dbname);
}

/**
 * Crée la table pour les informations du CV s'il n'existe pas déjà.
 *
 * @param mysqli $conn Objet de connexion à la base de données.
 */
function createCVTable($conn)
{
    // Crée la table pour les informations du CV
    $sql = "CREATE TABLE IF NOT EXISTS cv_data (
        cv_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        names VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE,
        phone VARCHAR(20) NOT NULL,
        experience TEXT NOT NULL,
        education TEXT NOT NULL,
        hobbies TEXT NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'cv_data' created successfully";
    } else {
        echo "Error creating table 'cv_data': " . $conn->error;
    }
}

// Établit la connexion à la base de données
$conn = connectToDatabase($servername, $username, $password);

// Crée la base de données
createDatabase($conn, $dbname);

// Sélectionne la base de données
selectDatabase($conn, $dbname);

// Crée la table pour les informations du CV
createCVTable($conn);

// Ferme la connexion à la base de données
$conn->close();
?>
