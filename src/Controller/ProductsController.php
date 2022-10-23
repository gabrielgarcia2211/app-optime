<?php

namespace App\Controller;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
     * @Route("/products", name="products", methods={"GET"})
     */
    public function index()
    {

        $categorys = $this->categoryRepository->allCategories();
        return $this->render('productos/index.html.twig', [
            'categorys' => $categorys,
        ]);
    }

    /**
     * @Route("/products/list", name="list_products", methods={"GET"})
     */
    public function listProducts()
    {

        $products = $this->productRepository->allProducts();

        # mapeamos la informacion
        $jsonData = array();
        $idx = 0;
        foreach ($products as $p) {
            $temp = array(
                'id' => $p->getId(),
                'code' => $p->getCode(),
                'name' => $p->getName(),
                'description' => $p->getDescription(),
                'brand' => $p->getBrand(),
                'category' => $p->getCategory()->getName(),
                'price' => $p->getPrice(),
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
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

            #buscamos el resultado de producto
            $product = $this->productRepository->findBy(
                ['id' => $id],
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

    /**
     * @Route("/products/report-excel/", name="report_excel_product", methods={"GET"})
     */
    public function reporteExcel()
    {
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Codigo');
        $sheet->setCellValue('C1', 'Nombre');
        $sheet->setCellValue('D1', 'Descripcion');
        $sheet->setCellValue('E1', 'Marca');
        $sheet->setCellValue('F1', 'Categoria');
        $sheet->setCellValue('G1', 'Precio');
        $sheet->setCellValue('H1', 'Fecha Creacion');


        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();


        $sheet = $spreadsheet->getActiveSheet();

        foreach ($products as $key => $product) {
            $key = $key + 2;
            $sheet->setCellValue('A' . $key, $product->getId());
            $sheet->setCellValue('B' . $key, $product->getCode());
            $sheet->setCellValue('C' . $key, $product->getName());
            $sheet->setCellValue('D' . $key, $product->getDescription());
            $sheet->setCellValue('E' . $key, $product->getBrand());
            $sheet->setCellValue('F' . $key, $product->getCategory()->getName());
            $sheet->setCellValue('G' . $key, $product->getPrice());
            $sheet->setCellValue('H' . $key, $product->getCreatedAt());
        }
        
        $spreadsheet->getActiveSheet()
        ->getStyle('A1:H1')
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('13A811');


        $spreadsheet->getActiveSheet()
        ->getStyle('A1:H1')
        ->getFont()
        ->getColor()
        ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->setTitle("Solicitudes Electrónicas");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'report_products.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
