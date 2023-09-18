<?php
namespace App\Classe;

    use App\Entity\Albums;
    use App\Entity\Contrat;
    use App\Entity\Contratwithalbum;

    use mikehaertl\pdftk\Pdf;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Dompdf\Dompdf;

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


    public function genererate($data,$contrat=null,$album=null)
    {
        // Créez une instance de Dompdf
        $dompdf = new Dompdf();
        $filename = 'pdf_' . $album->getId() . '.pdf';
        // Construisez le contenu HTML du contrat en utilisant les données fournies
        $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            /* CSS pour le contrat */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }
            h1 {
                font-size: 24px;
                text-align: center;
                margin-bottom: 20px;
            }
            .contract-details {
                margin-bottom: 30px;
            }
            .contract-details table {
                width: 100%;
            }
            .contract-details table td {
                padding: 5px;
            }
            .contract-details .buyer-info {
                width: 50%;
                float: left;
            }
            .contract-details .seller-info {
                width: 50%;
                float: right;
            }
            .contract-details .album-title {
                margin-top: 20px;
                font-weight: bold;
            }
            .contract-details .price {
                margin-top: 10px;
                font-weight: bold;
            }
            .contract-body {
                margin-top: 30px;
                font-size: 14px;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <h1>Contrat d\'achat d\'album</h1>
    
        <div class="contract-details">
            <table>
                <tr>
                    <td class="buyer-info">
                        <h2>Acheteur :</h2>
                        <p>'.$data["Name_buyer"].'</p>
                    </td>
                    <td class="seller-info">
                        <h2>Vendeur :</h2>
                        <p>'.$data["name_seller"].'</p>
                    </td>
                </tr>
            </table>
    
            <div class="album-title">
                <h2>Titre de l\'album :</h2>
                <p>'.$data["title_album"].'</p>
            </div>
    
            <div class="price">
                <h2>Prix de l\'album :</h2>
                <p>'.$data["price_album"].'</p>
            </div>
        </div>
        <div class="contract-body">
        <h2>Corps du contrat :</h2>
        <p>Par le présent contrat, l\'Acheteur s\'engage à acheter l\'album intitulé "{{ data.title_album }}" du Vendeur via la plateforme de vente en ligne.</p>
        <p>Les détails de l\'achat sont les suivants :</p>
        <ul>
            <li>Nom de l\'Acheteur : '.$data["Name_buyer"].'</li>
            <li>Nom du Vendeur : '.$data["name_seller"].'</li>
            <li>Prix de l\'album : '.$data["price_album"].'</li>
        </ul>
        <p>Les parties conviennent des conditions suivantes :</p>
        <ol>
            <li>L\'Acheteur confirme que les informations fournies sont exactes et accepte les modalités de paiement en ligne.</li>
            <li>Le Vendeur s\'engage à fournir l\'accès à l\'album après la confirmation du paiement.</li>
            <li>L\'Acheteur reconnaît que cet achat est non remboursable.</li>
            <li>Ce contrat est soumis aux lois en vigueur dans le pays de résidence du Vendeur.</li>
        </ol>
        <p>En cliquant sur le bouton "Confirmer l\'achat", l\'Acheteur reconnaît avoir pris connaissance et accepté les termes et conditions du présent contrat.</p>
    </div>
    </body>
    </html>
    ';

        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Définissez les options de configuration de Dompdf si nécessaire
        // $dompdf->setOptions([...]);

        // Générez le fichier PDF
        $dompdf->render();

        // Obtenez le contenu PDF généré
        $output = $dompdf->output();

        // Définissez le chemin de destination pour enregistrer le fichier PDF
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/contrats/'.$filename;


        // Enregistrez le fichier PDF sur le serveur
        file_put_contents($filePath, $output);

        // Retournez le chemin du fichier PDF généré
        return $filename;
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