<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Callback;


class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ])
            ])

            ->add('firstname',TextType::class,[
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ])
            ])
            ->add('lastname',TextType::class,[
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ])
            ])
            ->add('addresse',TextType::class)
            ->add('ville',TextType::class)
            ->add('code_postal', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le code postal est requis']),
                    new Callback([$this, 'validatePostalCode']),
                ],
            ])
            ->add('pays', ChoiceType::class, [
                        'choices' => array_flip(Countries::getNames()),
                        'placeholder' => 'Choisissez un pays'
            ])
            ->add('picture', FileType::class, [

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
    public function validatePostalCode($codePostal, ExecutionContextInterface $context, $payload)
    {
        /** @var FormInterface $form */
        $form = $context->getRoot();

        // Récupérer la valeur du champ pays
        $pays = $form->get('pays')->getData();

        // Valider le code postal en fonction du pays
        switch ($pays) {
            case 'FR':
                // Code postal de France : 5 chiffres
                $regex = '/^[0-9]{5}$/';
                $message = 'Le code postal doit contenir 5 chiffres pour la France';
                break;
            case 'US':
                // Code postal des États-Unis : 5 chiffres ou 5 chiffres + 4 chiffres séparés par un tiret
                $regex = '/^[0-9]{5}(?:-[0-9]{4})?$/';
                $message = 'Le code postal doit contenir 5 chiffres ou 5 chiffres + 4 chiffres séparés par un tiret pour les États-Unis';
                break;
            // Ajouter d'autres cas selon les pays que vous souhaitez prendre en charge
            default:
                // Pas de validation pour les autres pays
                return;
        }

        // Valider le code postal en utilisant la regex correspondante
        if (!preg_match($regex, $codePostal)) {
            $context->buildViolation($message)
                ->addViolation();
        }
    }
}
