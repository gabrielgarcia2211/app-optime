<?php

namespace App\Controller;

use App\Entity\Category;
use Psr\Log\LoggerInterface;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    private $logger;
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/category/create", name="create_category", methods={"POST"})
     */
    public function new(Request $request)
    {

        try {

            $name = $request->request->get('nombre');
            $activate = $request->request->get('activo');

            $response = $this->categoryRepository->saveCategory($name, $activate);

            if ($response["status"]) {
                $this->addFlash('success-category', 'Categoria creada!');
                return $this->redirectToRoute('products');
            } else {
                $this->addFlash('validate-category', $response["response"]);
                return $this->redirectToRoute('products');
            }
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - CategoryController/new!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }
    /**
     * @Route("category/delete/{id}", name="delete_category", methods={"POST"})
     */
    public function delete(Category $id)
    {
        try {
            $categoryDelete = $this->getDoctrine()->getManager();
            $categoryDelete->remove($id);
            $categoryDelete->flush();
            $this->addFlash('success-category', 'Categoria elimnada!');
            return $this->redirectToRoute('products');
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - CategoryController/delete!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }
}
