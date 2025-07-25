<?php
// src/Form/FilteredAstreignableType.php
namespace App\Form;

use App\Entity\Astreignable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class FilteredAstreignableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $theme = $options['theme'];

        $builder->add('binome', EntityType::class, [
            'class' => Astreignable::class,
            'multiple' => true,
            'expanded' => false,
            'choice_label' => fn (Astreignable $a) => $a->getPrenom() . ' ' . $a->getNom(),
            'query_builder' => function (EntityRepository $er) use ($theme) {
                return $er->createQueryBuilder('a')
                          ->where('a.direction = :theme')
                          ->setParameter('theme', $theme);
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('theme');
    }
}
