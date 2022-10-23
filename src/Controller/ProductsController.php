<?php

namespace App\Controller;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/products/create", name="create_product", methods={"POST"})
     */
    public function new(Request $request)
    {

        try {

            $code = $request->request->get('codigo');
            $name = $request->request->get('nombre');
            $description = $request->request->get('descripcion');
            $brand = $request->request->get('marca');
            $preci = $request->request->get('precio');
            $category = $request->request->get('categoria');

            if (empty($category)) {
                $this->addFlash('validate-category', 'Id vacio');
                return $this->redirectToRoute('products');
            }

            $response = $this->productRepository->saveProduct($code, $name, $description, $brand, $preci, $category);

            if ($response["status"]) {
                $this->addFlash('success-category', 'Producto creado!');
                return $this->redirectToRoute('products');
            } else {
                $this->addFlash('validate-product', $response["response"]);
                return $this->redirectToRoute('products');
            }
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductsController/new!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/products/edit/{id}", name="edit_product", methods={"GET"})
     */
    public function edit($id): JsonResponse
    {

        try {

            #buscamos el resultado
            $product = $this->productRepository->findBy(
                ['id' => 7],
            );

            # mapeamos la informacion
            $jsonData = array();
            $idx = 0;
            foreach ($product as $p) {
                $temp = array(
                    'id' => $p->getId(),
                    'code' => $p->getCode(),
                    'name' => $p->getName(),
                    'description' => $p->getDescription(),
                    'brand' => $p->getBrand(),
                    'category' => $p->getCategory(),
                    'price' => $p->getPrice(),
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
            /* return $this->render('productos/index.html.twig', [
                'edit_product' => $product,
            ]); */
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductsController/edit!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/products/edit", name="edit_product_new", methods={"POST"})
     */
    public function editProduct(Request $request)
    {

        try {


            $id = $request->request->get('id_edit');
            $code = $request->request->get('codigo_edit');
            $name = $request->request->get('nombre_edit');
            $description = $request->request->get('descripcion_edit');
            $brand = $request->request->get('marca_edit');
            $preci = $request->request->get('precio_edit');
            $category_id = $request->request->get('categoria_edit');

            if (empty($category_id)) {
                $this->addFlash('validate-category', 'Id vacio');
                return $this->redirectToRoute('products');
            }

            $customer = $this->productRepository->findOneBy(['id' => $id]);
            $category = (object) $this->categoryRepository->findBy(
                ['id' => $category_id],
            )[0];

            $customer->setCode($code)
                ->setName($name)
                ->setDescription($description)
                ->setBrand($brand)
                ->setCategory($category)
                ->setPrice($preci);

            $response = $this->productRepository->updateProduct($customer);
            if ($response["status"]) {
                $this->addFlash('success-category', 'Producto actualizado!');
                return $this->redirectToRoute('products');
            } else {
                $this->addFlash('validate-product', $response["response"]);
                return $this->redirectToRoute('products');
            }
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductsController/editProduct!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/products/delete/{id}", name="delete_product", methods={"POST"})
     */
    public function delete(Product $id)
    {
        try {
            $productDelete = $this->getDoctrine()->getManager();
            $productDelete->remove($id);
            $productDelete->flush();
            $this->addFlash('success-product', 'Producto elimnado!');
            return $this->redirectToRoute('products');
        } catch (\Exception $e) {
            $this->logger->critical('Ah ocurrido un error - ProductController/delete!', [
                'cause' => $e->getMessage(),
            ]);
        }
    }
}
