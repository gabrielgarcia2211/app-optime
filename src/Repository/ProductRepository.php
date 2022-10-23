<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $categoryRepository;
    private $validator;
    private $manager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $manager, ManagerRegistry $registry,   ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($registry, Product::class);
    }


    public function allProducts()
    {
        $productsList = $this->findAll();
        return $productsList;
    }

    public function saveProduct($code, $name, $description, $brand, $preci, $category)
    {
        $newProduct = new Product();
        $sendErros = [];

        $category = (object) $this->categoryRepository->findBy(
            ['id' => $category],
        )[0];


        $newProduct->setCode($code)
            ->setName($name)
            ->setDescription($description)
            ->setBrand($brand)
            ->setCategory($category)
            ->setPrice($preci)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable());

        $errors = $this->validator->validate($newProduct);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                $sendErros[$key]["key"] = $error->getPropertyPath();
                $sendErros[$key]["value"] = (!empty($error->getConstraint()->message)) ? $error->getConstraint()->message : $error->getMessage();
            }

            return ['status' => false, 'response' => $sendErros];
        }

        $this->manager->persist($newProduct);
        $this->manager->flush();

        return ['status' => true, 'response' => ""];
    }

    public function updateProduct(Product $newProduct)
    {
        $errors = $this->validator->validate($newProduct);
        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                $sendErros[$key]["key"] = $error->getPropertyPath();
                $sendErros[$key]["value"] = (!empty($error->getConstraint()->message)) ? $error->getConstraint()->message : $error->getMessage();
            }

            return ['status' => false, 'response' => $sendErros];
        }
        $this->manager->persist($newProduct);
        $this->manager->flush();

        return ['status' => true, 'response' => ""];
    }
}
