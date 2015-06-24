<?php
namespace MG\MemoryGameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use MG\MemoryGameBundle\Entity\HumanPlayer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', ['label'=>'Votre pseudo :'])
        		->add('password', 'repeated', array(
        		'type' => 'password',
        		'invalid_message' => 'Les mots de passe doivent correspondre.',
        		'options' => array('required' => true)))
                ->add('birthdate', 'date', array(
                        'widget' => 'choice',
                                        'format' => 'dd-MM-yyyy',
                						'empty_value' => '',
                						'years' => range(date('Y'), date('Y') - 100),
                						'pattern' => "{{ day }}-{ month }}-{{ year }}",
                						'invalid_message' => 'Cette date n\'est pas valide.',
                                        'label' => 'Votre date de naissance :'
                                        ))
                ->add('save', 'submit', ['label'=>'S\'enregistrer'])
        ;
    }

	public function getName()
	{
		return 'registration';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'MG\MemoryGameBundle\Entity\HumanPlayer',
		));
	}
}