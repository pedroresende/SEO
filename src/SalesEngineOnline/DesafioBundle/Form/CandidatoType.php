<?php

namespace SalesEngineOnline\DesafioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CandidatoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('telefone')
            ->add(
                    'nascimento', 
                    'birthday', 
                    array(
                        'years' => range(date('Y') - 110, date('Y'))
                    )
                )
            ->add('localidade', 'choice')
            ->add(
                    'fotografia', 
                    'file', 
                    array(
                        'required' => true
                        )
                    )
            ->add(
                    'curriculum', 
                    'file', 
                    array(
                        'required' => true
                        )
                )
            ->add('email')
            ->add('emailAmigo')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SalesEngineOnline\DesafioBundle\Entity\Candidato'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'salesengineonline_desafiobundle_candidato';
    }
}
