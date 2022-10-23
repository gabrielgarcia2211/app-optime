<?php

namespace App\Controller;

use App\Entity\Category;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsController extends AbstractController
{

    private $productRepository;
    private $categoryRepository;
    private $logger;


    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository, LoggerInterface $logger)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
    }


    /**
     * @Route("/products", name="products")
     */
    public function index(): Response
    {
        # listar productos
        $products = $this->productRepository->allProducts();
        # listar categorias
        $categorys = $this->categoryRepository->allCategories();

        return $this->render('productos/index.html.twig', [
            'products' => $products,
            'categorys' => $categorys,
        ]);
    }

    /**
     * @Route("/products/create", name="create_product", methods={"GET"})
     */
    public function new(Request $request): JsonResponse
    {

        try {

            $data = json_decode($request->getContent(), true);

            $code = 1; //$data['code'];
            $name = "GABRIEL"; //$data['name'];
            $description = "HOLA MUNDO"; //$data['description'];
            $brand = null; //$data['brand'];
            $category = 1; //$data['category'];

            $response = $this->productRepository->saveProduct($code, $name, $description, $brand, $category);

            if ($response["status"]) {
                return new JsonResponse(['status' => 'Producto creado!'], Response::HTTP_CREATED);
            } else {
                return new JsonResponse(['status' => 'Ah ocurrido un error', 'response' => $response["response"]], Response::HTTP_CREATED);
            }
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductsController/new!', [
                'cause' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
