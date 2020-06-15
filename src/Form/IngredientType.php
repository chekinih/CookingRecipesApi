<?php
namespace  App\Form;

use Doctrine\Common\Persistence\ObjectManager;
use App\Form\DataTransformer\IngredientTransformer;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ingredient;

/**
 * 
 * @author CHEKINI Hakima
 * This class builds the form type of an ingredient
 */
class IngredientType extends  AbstractType
{
    /**
    * @var ObjectManager
    */
    private $manager;
    /**
     * 
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->addModelTransformer(new CollectionToArrayTransformer(), true)
                ->addModelTransformer(new IngredientTransformer($this->manager), true);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
      // Give a class for the ingredient field
      $resolver->setDefault('attr', [
        'class'=> 'tag-input'
      ]);
      // The review field is not required by default
      $resolver->setDefault('required', true); 
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
      return TextType::class;
    }
}
