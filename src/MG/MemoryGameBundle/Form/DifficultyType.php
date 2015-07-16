<?php
namespace MG\MemoryGameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use MG\MemoryGameBundle\Entity\Difficulty;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DifficultyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', 'entity', array(
    			'class' => 'MGMemoryGameBundle:Difficulty',
    			'property' => 'label', 
        		))
        ;
    }

	public function getName()
	{
		return 'difficulty';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'MG\MemoryGameBundle\Entity\Difficulty',
				'validation_groups' => false,
		));
	}
}