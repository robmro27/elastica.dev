<?php

namespace AppBundle\Form\Type;

use AppBundle\Model\CodeSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * 
 * @author rmroz
 */
class CodeSearchType extends AbstractType {
   
    /**
     *
     * @var integer 
     */
    protected $perPage = 20;
    
    /**
     *
     * @var array 
     */
    protected $perPageChoices = array(2,5,10,20);
    
    
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $perPageChoices = array();
        foreach($this->perPageChoices as $choice){
            $perPageChoices[$choice] = $choice;
        }
        
        $builder
            ->setMethod('GET')
            ->add('code', null, ['required' => false,'attr' => ['autocomplete' => 'off']])
            ->add('search', SubmitType::class);
    }
    
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefalults([
            'csrf_protection' => false,
            'data_class' => AppBundle\Model\CodeSearch::class,
        ]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'code_search_type';
    }
}
