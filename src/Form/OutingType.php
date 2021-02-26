<?php

namespace App\Form;

use App\Entity\Outing;
use App\Form\ApplicationType;
use App\Entity\OutingType as EntityOutingType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OutingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('outingType', EntityType::class, $this->getConfiguration('Type de sortie', 'Veuillez choisir le type de votre sortie', [
                'class' => EntityOutingType::class,
                'choice_label' => 'title',
                'multiple' => false,
            ]))
            ->add('startDate', DateTimeType::class, $this->getConfiguration('Date de début', '', [
                'widget' => 'single_text'
            ]))
            ->add('endDate', DateTimeType::class, $this->getConfiguration('Date de fin', '', [
                'widget' => 'single_text'
            ]))
            ->add('distance', NumberType::class, $this->getConfiguration('Distance parcourue', 'Quelle distance avez-vous parcourue ?',[
                'attr' => [
                    'min' => '0.1',
                    'max' => '1000'
                ],
                'required' => true
            ]))
            ->add('comment', TextType::class, $this->getConfiguration('Commentaire', 'Quelles ont été vos impressions sur votre sortie ?', [
                'required' => false
            ]))
            ->add('submit', SubmitType::class, $this->getConfiguration('Envoyer',''))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
