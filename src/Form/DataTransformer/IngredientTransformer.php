<?php

namespace App\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use App\Entity\Ingredient;
/**
 *
 * @author CHEKINI Hakima
 * This class contains two principal functions: transform and reverseTransform
 *
 */
class IngredientTransformer implements DataTransformerInterface
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
     * removeAccentsAndUpperCase
     * Removes all the accents of the string and transform the string into lowercase string
     *
     * @param String $str
     * @return string
     */
    function removeAccentsAndUpperCase($str)
    {
        $strWithoutAccents =  iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        return strtolower($strWithoutAccents);

    }
    
    /** transform
     * This function converts the ingredient array to a string separated with a comma
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\DataTransformerInterface::transform()
     */
    public function transform($value): string
    {

        // Convert the ingredient array to a string separated with a comma
        return implode(',', $value);
    }
    /**
     * reverseTransform
     * This function convert the string containing ingredients entred by the user into an array of ingredients  and persist them
     * in the base if the ingredient doesn't already exist
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     */
    public function reverseTransform($string): array
    {
        // array_filter : Take off empty ingredients
        //array_unique: Remove all duplicates ingredients
        /*** @var array $enteredTagNames */
        $enteredTagNames = array_unique(array_filter(array_map('trim', explode(',', $string))));

        // Get an associatif array of entered ingredients with key = ingredient s code  by calling removeAccentsAndUpperCase 
        // function and value = ingredients s name
        /** * @var array $enteredTags */
        $enteredTags = [];
        foreach($enteredTagNames as $enteredTagName)
        {
            $enteredTags[$this->removeAccentsAndUpperCase($enteredTagName)] = $enteredTagName;
        }
        // Get already existed ingredients that are entered
        $tags = $this->manager->getRepository('App:Ingredient')->findBy(['nom'=>$enteredTagNames]);
    
        // Get an associatif array of existed ingredients
        $existedTags = [];
        foreach($tags as $tag)
        {
            $existedTags[$tag->getCode()] = $tag->getNom();
        }
        //remove the new ingredients entered by the user that already exists with the same code
        /*** @var array $newTags */        
        $newTags = array_diff_key($enteredTags,$existedTags);
        
        // insert only ingredients that doesn't exist
        foreach($newTags as $newTagCode => $newTagName)
        {
            $tag = new Ingredient();
            $tag->setNom($newTagName);
            $tag->setCode($newTagCode);
            $tags[] = $tag;
        }
        return $tags;   
    }
    
}