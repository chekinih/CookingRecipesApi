<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @author CHEKINI Hakima
 * This class is usefull when the apiPlatform listens to all the DataPersister to see if there is someone who
 * can handle and transform data before persisting them
 */
class RecipePersister implements DataPersisterInterface{

    protected $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    /**
     * This dataPersister intervenes is the data is an instance of the class Recipe
     */
    public function supports($data):bool{
        return $data instanceof Recipe; 
    }

    /**
     * When the support function returns true, this function is executed
     */
    public function persist($data){
        /**@var array $ingredients */
        $ingredients = $data->getIngredients();
        foreach($ingredients as $ingredient)
        {   
            $code = $this->removeAccentsAndUpperCase($ingredient->getNom());
            $ingredient->setCode($code);   
        }
        $this->em->persist($data);
        $this->em->flush();

    }

    public function remove($data){
        $this->em->remove($data);
        $this->em->flush();
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
}