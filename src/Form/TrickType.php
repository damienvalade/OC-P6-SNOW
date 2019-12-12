<?php

namespace App\Form;

use App\Form\ImageType;
use App\Form\VideoType;

use App\Entity\Tricks;
use App\Entity\TypeTricks;
use App\Form\ApplicationType;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = [];

        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('type_tricks', EntityType::class, [
                'class' => TypeTricks::class,
                'choice_label' => 'name_tricks'
            ])
            ->add('file', FileType::class, array('required' => false))

            ->add('pictures', CollectionType::class, array(
                'entry_type'          => ImageType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
            ))
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
