<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon CV</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #008080;
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #008080;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background-color: #005757;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<?php
/**
 * Inclut l'en-tête commun et la classe de base de données.
 */
include 'Classes/Database.php';

$database = new Database();

$existingData = $database->getAllCVs();
?>

<div class="container">
    <h1>Mon CV</h1>

    <!-- Formulaire principal -->
    <form method="post">
        <input type="hidden" name="CVData" value="mySpecialForm">

        <!-- Informations personnelles -->
        <label for="names">Noms et Prénoms :</label>
        <input type="text" name="names" required> <br>

        <label for="email">Adresse Email :</label>
        <input type="email" name="email" required><br>

        <label for="phone">Numéro de téléphone :</label>
        <input type="tel" name="phone" required><br>

        <!-- Expérience professionnelle -->
        <label for="experience">Expérience Professionnelle :</label>
        <textarea name="experience" rows="4" required></textarea><br>

        <!-- Parcours académique -->
        <label for="education">Parcours Académique :</label>
        <textarea name="education" rows="4" required></textarea><br>

        <!-- Hobbies -->
        <label for="hobbies">Hobbies :</label>
        <textarea name="hobbies" rows="4" required></textarea><br>

        <!-- Bouton de soumission -->
        <button type="submit">Enregistrer le CV</button>
        <button type="button" onclick="showExistingData()">Voir données existantes</button>
    </form>

    <!-- Formulaire pour générer le PDF -->
    <form method='post' id='generatePdfForm'>
        <input type='text' name='email' id='emailInput' placeholder="Entrez l'adresse mail du profil à exporter">
        <button type='submit' name='generatePDF'>Générer CV PDF</button>
    </form>

    <?php
    // Génère le PDF si le formulaire pour générer le PDF est soumis
    if (isset($_POST['generatePDF'])) {
        $cvEmail = htmlspecialchars($_POST['email']);
        $cvInstance = $database->getCVByEmail($cvEmail);

        if ($cvInstance) {
            $cvInstance->generatePDFDomPdf(); // Appelle la méthode pour générer le PDF
        }
    }
    ?>

    <!-- Section pour afficher les données existantes -->
    <div id="existingDataSection" style="display: none;">
        <h2>Données existantes</h2>
        <table>
            <tr>
                <th>Noms</th>
                <th>Email</th>
                <th>Numéro Tel</th>
                <th>Experience pro</th>
                <th>Parcours Académique</th>
                <th>Hobbies</th>
            </tr>
            <?php
            // Affiche les données existantes dans un tableau
            foreach ($existingData as $data) {
                echo "<tr>";
                echo "<td>{$data['names']}</td>";
                echo "<td>{$data['email']}</td>";
                echo "<td>{$data['phone']}</td>";
                echo "<td>{$data['experience']}</td>";
                echo "<td>{$data['education']}</td>";
                echo "<td>{$data['hobbies']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Ajoutez ici vos liens vers des fichiers JS -->
<script>

     /** Affiche la section des données existantes. */
    function showExistingData() {
        document.getElementById('existingDataSection').style.display = 'block';
    }
</script>

</body>
</html>
