<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author CHEKINI Hakima
 */
class IngredientController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/ingredients.json", name="ingredient_index", methods={"GET"})
     */
    public function indexAction(IngredientRepository $ingredientRepository, Request $request)
    {

        if($q = $request->get('q'))
        {
            // Get all the ingredients that has the sub string $q
            $tags = $ingredientRepository->search($q);
        }else{
            $tags = $ingredientRepository->findAll();
        }
        // import only properties that are in public groupe ==> see tag.php, in our case, only names
        return $this->json($tags, 200, [], ['groups' => ['public']]);
    }
}
