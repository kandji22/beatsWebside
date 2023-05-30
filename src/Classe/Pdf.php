<?php
namespace App\Classe;
use App\Entity\Albums;
use App\Entity\Contrat;
use App\Entity\Contratwithalbum;
use FPDF;


class Pdf extends FPDF{
    //constant à changer fichier pdf exemplaire
    const ACHETEUR_ADRRES_NOM = '[nom_address]';
    const VENDEUR_ADDRESS_NOM = '[vendeur_address]';
   /* public function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Exemple de PDF modifié', 0, 1, 'C');
        $this->Ln(10);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    public function getPDFContrat(Albums $album,Contrat $contrat,$entityManager) {
        $this->AliasNbPages();
        $this->AddPage();
        // Ouvrir le PDF existant
        $existingPdf = $_SERVER['DOCUMENT_ROOT'].'uploads/'.$contrat->getFileContrat();
        $lines = file($existingPdf);

        // Parcourir chaque ligne du PDF existant
        foreach ($lines as $line) {
            // Remplacer [nom] par une valeur spécifique
            $modifiedLine = str_replace(self::ACHETEUR_ADRRES_NOM, 'John Doe', $line);
            $modifiedLine = str_replace(self::VENDEUR_ADDRESS_NOM, 'WEB BEAT', $line);
            // Ajouter la ligne modifiée au PDF en cours de création
            $this->MultiCell(0, 10, utf8_decode($modifiedLine));
        }
        $contratWithAlbum = new Contratwithalbum();
        $contratWithAlbum->setAlbum($album);
        $contratWithAlbum->setFile('new_contrat_test');
        //$entityManager->persist($contratWithAlbum);
        //$entityManager->flush();
        // Générer le PDF modifié
        $this->Output($_SERVER['DOCUMENT_ROOT'].'uploads/contrats/new_contrat_test.pdf', 'F');


    }

    */


    public function getPDFContrat(Albums $album, Contrat $contrat, $entityManager)
    {
        $this->AddPage();

        $this->SetFont('Arial', '', 12); // Exemple : police Arial, style normal, taille 12

        // Ouvrir le PDF existant
        $existingPdf = $_SERVER['DOCUMENT_ROOT'] . 'uploads/' . $contrat->getFileContrat();
        $lines = file($existingPdf);

        // Parcourir chaque ligne du PDF existant
        foreach ($lines as $line) {
            // Remplacer [nom] par une valeur spécifique
            $modifiedLine = str_replace(self::ACHETEUR_ADRRES_NOM, 'John Doe', $line);
            $modifiedLine = str_replace(self::VENDEUR_ADDRESS_NOM, 'WEB BEAT', $modifiedLine);
            // Ajouter la ligne modifiée au PDF en cours de création
            $this->MultiCell(0, 10, utf8_decode($modifiedLine));
        }

        $contratWithAlbum = new Contratwithalbum();
        $contratWithAlbum->setAlbum($album);
        $contratWithAlbum->setFile('new_contrat_test');
        $entityManager->persist($contratWithAlbum);
        $entityManager->flush();

        // Générer le PDF modifié
        $this->Output($_SERVER['DOCUMENT_ROOT'] . 'uploads/contrats/new_contrat_test.pdf', 'F');
    }


}