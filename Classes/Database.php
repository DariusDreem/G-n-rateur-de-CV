<?php
require_once 'CV.php';
require_once 'Database.php';

// Vérifiez si REQUEST_METHOD est défini
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que le formulaire spécifique est soumis
    if (isset($_POST['CVData']) && $_POST['CVData'] === 'mySpecialForm') {
        // Récupère les données du formulaire
        $names = isset($_POST['names']) ? htmlspecialchars($_POST['names']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
        $experience = isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : '';
        $education = isset($_POST['education']) ? htmlspecialchars($_POST['education']) : '';
        $hobbies = isset($_POST['hobbies']) ? htmlspecialchars($_POST['hobbies']) : '';

        // Crée une instance de la classe CV
        $cv = new CV($names, $email, $phone, $experience, $education, $hobbies);

        // Enregistre le CV dans la base de données
        $database = new Database();
        $database->saveCV($cv);
    }
}

class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'userscvinfo';

    // Déclarez les attributs ici
    private $user_id;
    private $names;
    private $email;
    private $phone;
    private $experience;
    private $education;
    private $hobbies;

    /**
     * Établit une connexion à la base de données.
     *
     * @return mysqli|null Retourne l'objet de connexion ou null en cas d'erreur.
     */
    private function connect()
    {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    /**
     * Enregistre le CV dans la base de données.
     *
     * @param CV $cv Instance de la classe CV.
     */
    public function saveCV($cv)
    {
        $conn = $this->connect();

        // Vérifier si l'e-mail existe déjà
        $existingCV = $this->getCVByEmail($cv->getEmail());

        if ($existingCV) {
            // Mettre à jour le CV existant au lieu d'insérer une nouvelle entrée
            $this->updateCV($cv);
        } else {
            // L'e-mail n'existe pas, procéder à l'insertion
            $this->insertCV($cv);
        }

        $conn->close();
    }

    // ... (le reste du code reste inchangé)

    /**
     * Méthode pour obtenir un CV par e-mail.
     *
     * @param string $email Adresse e-mail du CV à récupérer.
     * @return CV|null Retourne une instance de CV ou null si non trouvé.
     */
    public function getCVByEmail($email)
    {
        try {
            $conn = $this->connect();

            $stmt = $conn->prepare("SELECT * FROM cv_data WHERE email = ?");
            if (!$stmt) {
                throw new Exception("Erreur de préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                throw new Exception("Erreur d'exécution de la requête : " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $cv = new CV($row['names'], $row['email'], $row['phone'], $row['experience'], $row['education'], $row['hobbies']);
                return $cv;
            } else {
                return null;
            }
        } catch (Exception $e) {
            // Gérez l'exception (par exemple, en enregistrant les erreurs dans un fichier de journal)
            // Vous pouvez également imprimer le message d'erreur sur la page pour le débogage pendant le développement
            echo "Erreur : " . $e->getMessage();
            return null;
        } finally {
            // Assurez-vous de fermer le statement et la connexion, même en cas d'exception
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conn)) {
                $conn->close();
            }
        }
    }



    // Méthode pour obtenir un CV par e-mail
    private function prepareAndBind($stmt, $types, ...$params) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }

    private function insertCV($cv) {
        $conn = $this->connect();

        $stmt = $conn->prepare("INSERT INTO cv_data (user_id, names, email, phone, experience, education, hobbies) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $this->user_id = 1;
        $this->names = $cv->getNames();
        $this->email = $cv->getEmail();
        $this->phone = $cv->getPhone();
        $this->experience = $cv->getExperience();
        $this->education = $cv->getEducation();
        $this->hobbies = $cv->getHobbies();

        $this->prepareAndBind($stmt, "issssss", $this->user_id, $this->names, $this->email, $this->phone, $this->experience, $this->education, $this->hobbies);
    }

    private function updateCV($cv) {
        $conn = $this->connect();

        $stmt = $conn->prepare("UPDATE cv_data SET names=?, phone=?, experience=?, education=?, hobbies=? WHERE email=?");
        $this->names = $cv->getNames();
        $this->phone = $cv->getPhone();
        $this->experience = $cv->getExperience();
        $this->education = $cv->getEducation();
        $this->hobbies = $cv->getHobbies();
        $this->email = $cv->getEmail();

        $this->prepareAndBind($stmt, "ssssss", $this->names, $this->phone, $this->experience, $this->education, $this->hobbies, $this->email);
    }
    public function getAllCVs() {
        $conn = $this->connect();

        $sql = "SELECT * FROM cv_data";
        $result = $conn->query($sql);

        $cvData = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cvData[] = $row;
            }
        }

        $conn->close();

        return $cvData;
    }

    public function getCV($cvID) {
        $conn = $this->connect();

        $stmt = $conn->prepare("SELECT * FROM cv_data WHERE cv_id = ?");
        $stmt->bind_param("i", $cvID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cv = new CV($row['names'], $row['email'], $row['phone'], $row['experience'], $row['education'], $row['hobbies']);
            return $cv;
        } else {
            echo "CV non trouvé";
        }

        $stmt->close();
        $conn->close();
    }

}
?>
