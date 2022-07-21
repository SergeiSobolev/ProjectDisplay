<?php

namespace App\Form;

namespace App\Form;

use App\Form\Objects\SearchObject;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categoriesNameChoices = $options['choices'];

        $builder->add('name', TextType::class, ['required' => false]);
        $builder->add('category', ChoiceType::class,[
            'choices' => $categoriesNameChoices,
            'multiple' => true,
        ]);
        $builder->add('maxPrice', IntegerType::class, ['required' => false]);
        $builder->add('minPrice', IntegerType::class, ['required' => false]);
        $builder->add('search', SubmitType::class, ['label' => 'Поиск']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchObject::class,
            'choices' => null,
        ]);
    }
}