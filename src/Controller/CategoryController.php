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
    public function new(Request $request): JsonResponse
    {

        try {

            $name = $request->request->get('nombre');
            $activate = $request->request->get('activo');

            $response = $this->categoryRepository->saveCategory($name, $activate);

            if ($response["status"]) {
                return new JsonResponse(['status' => 'Categoria creada!'], Response::HTTP_CREATED);
            } else {
                return new JsonResponse(['status' => 'Ah ocurrido un error', 'response' => $response["response"]], Response::HTTP_CREATED);
            }
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductsController/new!', [
                'cause' => $e->getMessage(),
            ]);
            return new JsonResponse($e->getMessage(), Response::HTTP_CONFLICT);
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
            return new Response(
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - CategoryController/delete!', [
                'cause' => $e->getMessage(),
            ]);

            return new JsonResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }
    }
}
