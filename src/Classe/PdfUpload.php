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

    public function getPDFContrat(Albums $album, Contrat $contrat, $entityManager,$user)
    {

        $data = [
            'Name_buyer' => $user->getFirstname().' '.$user->getLastname(),
            'name_seller' => 'Webeat',
            'title_album' => $album->getTitle(),
            'price_album' => $album->getPrice().' €',
        ];

        $response = $this->genererate($data,$contrat,$album);
        return $response;
    }


    public function genererate($data,$contrat,$album)
    {
        try {
            $filename = 'pdf_' . $album->getId() . '.pdf';
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
    
    function convertirChiffreEnLettre($a)
    {
        $convert = explode('.',$a);
        if (isset($convert[1]) && $convert[1]!=''){
            return int2str($convert[0]).'Dinars'.' et '.int2str($convert[1]).'Centimes' ;
        }
        if ($a<0) return 'moins '.int2str(-$a);
        if ($a<17){
            switch ($a){
                case 0: return 'zero';
                case 1: return 'un';
                case 2: return 'deux';
                case 3: return 'trois';
                case 4: return 'quatre';
                case 5: return 'cinq';
                case 6: return 'six';
                case 7: return 'sept';
                case 8: return 'huit';
                case 9: return 'neuf';
                case 10: return 'dix';
                case 11: return 'onze';
                case 12: return 'douze';
                case 13: return 'treize';
                case 14: return 'quatorze';
                case 15: return 'quinze';
                case 16: return 'seize';
            }
        } else if ($a<20){
            return 'dix-'.int2str($a-10);
        } else if ($a<100){
            if ($a%10==0){
                switch ($a){
                    case 20: return 'vingt';
                    case 30: return 'trente';
                    case 40: return 'quarante';
                    case 50: return 'cinquante';
                    case 60: return 'soixante';
                    case 70: return 'soixante-dix';
                    case 80: return 'quatre-vingt';
                    case 90: return 'quatre-vingt-dix';
                }
            } elseif (substr($a, -1)==1){
                if( ((int)($a/10)*10)<70 ){
                    return int2str((int)($a/10)*10).'-et-un';
                } elseif ($a==71) {
                    return 'soixante-et-onze';
                } elseif ($a==81) {
                    return 'quatre-vingt-un';
                } elseif ($a==91) {
                    return 'quatre-vingt-onze';
                }
            } elseif ($a<70){
                return int2str($a-$a%10).'-'.int2str($a%10);
            } elseif ($a<80){
                return int2str(60).'-'.int2str($a%20);
            } else{
                return int2str(80).'-'.int2str($a%20);
            }
        } else if ($a==100){
            return 'cent';
        } else if ($a<200){
            return int2str(100).' '.int2str($a%100);
        } else if ($a<1000){
            return int2str((int)($a/100)).' '.int2str(100).' '.int2str($a%100);
        } else if ($a==1000){
            return 'mille';
        } else if ($a<2000){
            return int2str(1000).' '.int2str($a%1000).' ';
        } else if ($a<1000000){
            return int2str((int)($a/1000)).' '.int2str(1000).' '.int2str($a%1000);
        }
        else if ($a==1000000){
            return 'millions';
        }
        else if ($a<2000000){
            return int2str(1000000).' '.int2str($a%1000000).' ';
        }
        else if ($a<1000000000){
            return int2str((int)($a/1000000)).' '.int2str(1000000).' '.int2str($a%1000000);
        }
    }

    

}