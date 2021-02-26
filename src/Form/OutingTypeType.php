<?php

namespace App\Form;

use App\Entity\OutingType;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingTypeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Nom du type de sortie', "Quel nom donner ?"))
            ->add('submit', SubmitType::class, $this->getConfiguration('Envoyer', ''))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OutingType::class,
        ]);
    }
}
