<?php

require_once __DIR__ . '/../Librairies/fpdf/fpdf.php';
require_once __DIR__ . '/../Librairies/DomPdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Classe CV représentant un Curriculum Vitae.
 */
class CV {
    /** @var string Noms du candidat. */
    private $names;

    /** @var string Adresse email du candidat. */
    private $email;

    /** @var string Numéro de téléphone du candidat. */
    private $phone;

    /** @var string Expérience professionnelle du candidat. */
    private $experience;

    /** @var string Parcours académique du candidat. */
    private $education;

    /** @var string Hobbies du candidat. */
    private $hobbies;

    /**
     * Constructeur de la classe CV.
     *
     * @param string $names Noms du candidat.
     * @param string $email Adresse email du candidat.
     * @param string $phone Numéro de téléphone du candidat.
     * @param string $experience Expérience professionnelle du candidat.
     * @param string $education Parcours académique du candidat.
     * @param string $hobbies Hobbies du candidat.
     */
    public function __construct($names, $email, $phone, $experience, $education, $hobbies) {
        $this->names = $names;
        $this->email = $email;
        $this->phone = $phone;
        $this->experience = $experience;
        $this->education = $education;
        $this->hobbies = $hobbies;
    }

    /**
     * Génère le CV au format PDF en utilisant Dompdf.
     */
    public function generatePDFDomPdf() {
        // Créer une instance de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $html = "
            <html>
                <body>
                    <h1>Noms: {$this->names}</h1>
                    <p>Email: {$this->email}</p>
                    <p>Numéro Tel : {$this->phone}</p>
                    <p>Experience pro: {$this->experience}</p>
                    <p>Parcours Académique : {$this->education}</p>
                    <p>Hobbies: {$this->hobbies}</p>
                </body>
            </html>
        ";

        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->setPaper('A4', 'portrait');

        // Générer le PDF
        $dompdf->render();

        // Envoyer le PDF en tant que réponse HTTP
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="exemple.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        echo $dompdf->output();

        exit();
    }

    /**
     * Génère le CV au format PDF en utilisant FPDF.
     *
     * @return string Contenu du PDF généré.
     */
    public function generatePDFFpdf() {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, "Noms: {$this->names}");
        $pdf->Cell(40, 10, "Email: {$this->email}");
        $pdf->Cell(40, 10, "Numéro Tel: {$this->phone}");
        $pdf->Cell(40, 10, "Experience pro: {$this->experience}");
        $pdf->Cell(40, 10, "Parcours Académique: {$this->education}");
        $pdf->Cell(40, 10, "Hobbies: {$this->hobbies}");

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="cv.pdf"');

        // Déclencher le téléchargement du PDF
        ob_start();
        $pdf->Output();
        return ob_get_clean();
    }

    /**
     * Génère le CV au format PDF en utilisant TCPDF.
     */
    public function generatePDFTCPdf() {
        // Créer une instance de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Ajouter une page au PDF
        $pdf->AddPage();

        // Charger le contenu HTML pour le PDF
        $html = "
            <h1>Noms: {$this->names}</h1>
            <p>Email: {$this->email}</p>
            <p>Numéro Tel : {$this->phone}</p>
            <p>Experience pro: {$this->experience}</p>
            <p>Parcours Académique : {$this->education}</p>
            <p>Hobbies: {$this->hobbies}</p>
        ";

        // Ajouter le HTML au PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Définir le nom du fichier PDF
        $filename = 'exemple_tcpdf.pdf';

        // Envoyer le PDF en tant que réponse HTTP
        $pdf->Output($filename, 'I');

        // Assurez-vous de terminer le script après avoir envoyé le PDF
        exit();
    }

    /**
     * @return string Noms du candidat.
     */
    public function getNames() {
        return $this->names;
    }

    /**
     * @param string $names Noms du candidat.
     */
    public function setNames($names): void {
        $this->names = $names;
    }

    /**
     * @return string Adresse email du candidat.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email Adresse email du candidat.
     */
    public function setEmail($email): void {
        $this->email = $email;
    }

    /**
     * @return string Numéro de téléphone du candidat.
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param string $phone Numéro de téléphone du candidat.
     */
    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    /**
     * @return string Expérience professionnelle du candidat.
     */
    public function getExperience() {
        return $this->experience;
    }

    /**
     * @param string $experience Expérience professionnelle du candidat.
     */
    public function setExperience($experience): void {
        $this->experience = $experience;
    }

    /**
     * @return string Parcours académique du candidat.
     */
    public function getEducation() {
        return $this->education;
    }

    /**
     * @param string $education Parcours académique du candidat.
     */
    public function setEducation($education): void {
        $this->education = $education;
    }

    /**
     * @return string Hobbies du candidat.
     */
    public function getHobbies() {
        return $this->hobbies;
    }

    /**
     * @param string $hobbies Hobbies du candidat.
     */
    public function setHobbies($hobbies): void {
        $this->hobbies = $hobbies;
    }
}

?>
