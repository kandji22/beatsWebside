<?php
namespace App\Controller;
use App\Entity\Albums;
use App\Entity\OrderDetail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class StripeController extends AbstractController
{
    private $entityManager;
    private $card;
    public function __construct(EntityManagerInterface $manager,Cart $card) {
            $this->entityManager = $manager;
            $this->card = $card;

    }
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(Cart $cart,Request $request,Security $security,$reference)
    {

        $priceTotal = 0;
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $user = $security->getUser();
        $userId = $user->getId();
        $emailUser = $user->getUsername();
        $fullName = $user->getLastAndFirstName();
        $idsAlbums = explode(',',$reference);
        $numberAlbum = 0;
        $tabOrderDetail = [];
        foreach ($idsAlbums as $id) {
            $numberAlbum++;
        }
        //création detail achat et recalcul p
        foreach ($idsAlbums as $id) {
           $album =  $this->entityManager->getReference(Albums::class,$id);
            $orderDetail = new OrderDetail();
            $orderDetail->setStatus(false);
            $orderDetail->setFullnameuser($fullName);
            $orderDetail->setAlbum($album->getId());
            $orderDetail->setPrice($album->getPrice());
            array_push($tabOrderDetail,$orderDetail);
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $album->getPrice().'00',
                    'product_data' => [
                        'name' => $album->getTitle(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$album->getImage()],
                    ],
                ],
                'quantity' => 1,
            ];

        }
        Stripe::setApiKey('sk_test_51LECEPEUVim8IwAqQkjRfg7fmf3MFDeWK0m6hrY3ukUy1hu9rYIYTe0pA9SyIyusU62OUvMDlU6KhEgXMlIaWsTN00F8dx2gkL');
        $checkout_session = Session::create([
            'customer_email' => $emailUser,
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);
        foreach ($tabOrderDetail as $orderDetail) {
            $orderDetail->setStripesession($checkout_session->id);
            $this->entityManager->persist($orderDetail);
            $this->entityManager->flush();
        }


        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;

    }

    /**
     * @Route("/commande/merci/{checkout}", name="stripe_success_session")
     */
    public function successStripe($checkout) {
        //app_account
        // Add a flash message
        //http://127.0.0.1:8000/commande/merci/dkdfk

        $tabDetail = $this->entityManager->getRepository(OrderDetail::class)->findOneByStripeSessionId($checkout);

        $this->addFlash('success', 'Paiement effectué avec succés');
        //on set les albums à vendu
        foreach ($tabDetail as $detail) {
            $albumId = $detail->getAlbum();
            $album = $this->entityManager->getRepository(Albums::class)->find($albumId);
            if($album != null) {
                $album->setStatus(true);
                $this->entityManager->persist($album);
                $this->entityManager->flush();
            }
            //set status orderdetail
            $detail->setStatus(true);
            $detail->setPrice($album->getPrice());
            $this->entityManager->persist($detail);
            $this->entityManager->flush();
            $this->card->delete($album->getId());
        }
        // Redirect to another route
        return $this->redirectToRoute('app_account');
    }

    /**
     * @Route("/commande/erreur/{checkout}", name="stripe_error_session")
     */
    public function cancelStripe($checkout) {
        // Add a flash message
        $this->addFlash('error', 'Erreur lors du paiement.');

        // Redirect to another route
        return $this->redirectToRoute('app_account');
    }
}