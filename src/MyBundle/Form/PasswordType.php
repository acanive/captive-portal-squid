<?php
namespace MyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'password', array(
                'label' => "Contraseña Actual:", 'attr' => array(
                    'placeholder' => 'Escriba su contraseña actual'
                )
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas no coinciden.',
                'required' => false,
                'first_options' => array('label' => "Nueva Contraseña:", 'attr' => array(
                    'placeholder' => 'Escriba su nueva contraseña'
                )),
                'second_options' => array('label' => "Repetir Contraseña:", 'attr' => array(
                    'placeholder' => 'Repita su nueva contraseña'
                )),
            ))
        ;
    }

}