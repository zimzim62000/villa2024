<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ArticleType extends AbstractType
{
    public function __construct(private TokenStorageInterface $tokenStorage) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre pour votre article.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'entity.article.title',
                ],
            ])
            ->add('date', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'data-format' => 'dd-mm-yyyy HH:ii'
                ],
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur de l\'article',
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('videoLink', TextType::class, [
                'label' => 'Vidéo',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'entity.article.videoLink',
                ],
                'required' => false
            ])
            ->add('imageFile', VichFileType::class, [
                'label' => 'Image mise en avant',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false
            ])
            ->add('content', HiddenType::class, [
                'attr' => [
                    'id' => 'content',
                ]
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activer l\'article',
                'attr' => [
                    'class' => 'form-check-input switch-input'
                ],
                'required' => false,
                'data' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'btn btn-custom',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $entity = $event->getData();

        $date = new \DateTime('now');
        $timezone = new \DateTimeZone('Europe/Paris');
        $date->setTimezone($timezone);

        $entity->setDate($date);
        $entity->setAuthor($this->tokenStorage->getToken()->getUser());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}
