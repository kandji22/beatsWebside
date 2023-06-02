<?php
namespace App\Classe;

    use App\Entity\Albums;
    use App\Entity\Contrat;
    use App\Entity\Contratwithalbum;

    use mikehaertl\pdftk\Pdf;
    use Symfony\Component\HttpFoundation\JsonResponse;


class PdfUpload
{
    //constant à changer fichier pdf exemplaire
    const ACHETEUR_ADRRES_NOM = '[nom_address]';
    const VENDEUR_ADDRESS_NOM = '[vendeur_address]';

    public function getPDFContrat(Albums $album, Contrat $contrat, $entityManager)
    {
        $data = [
            'name_field' => $album->getTitle(),
            'email_field' => 'kandji.k66@gmail.com',
            'phone_field' => '0783350630',
        ];

        $response = $this->genererate($data,$contrat);
        return $response;
    }


    public function genererate($data,$contrat)
    {
        try {
            $filename = 'pdf_' . rand(2000, 1200000) . '.pdf';
            $templatePath = $_SERVER['DOCUMENT_ROOT'] . 'uploads/' . $contrat->getFileContrat();
            $pdf = new Pdf($templatePath);
            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . 'uploads/contrats';

            $pdf->fillForm($data)
                ->flatten()
                ->saveAs($destinationPath . '/' . $filename);
            return $filename;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}