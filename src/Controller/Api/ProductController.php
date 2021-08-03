<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Exception\ProductNotFound;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Работа с сущностью Материал
 *
 * @Route("/api/product")
 */
class ProductController extends AbstractApiController {
	/**
	 * Создание материала
	 *
	 * @Route(methods={"POST"})
	 * @throws \Exception
	 */
	public function createProduct(Request $request, EntityManagerInterface $em) : Response {
		$code = $request->request->get("code");
		$name = $request->request->get("name");
		$price = $request->request->get("price");
		$product = (new ProductService($em))->createProduct($code, $name, $price);
		return $this->json([
			"id" => $product->getId(),
		]);
	}

	/**
	 * Получение информации о материале
	 *
	 * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function getProduct(EntityManagerInterface $em, int $id) : Response {
		$product = $em->getRepository(Product::class)->find($id);
		if (!$product) {
			throw new ProductNotFound($id);
		}
		return $this->json($product);
	}

	/**
	 * Обновление информации о материале
	 *
	 * @Route("/{id}", methods={"PUT"}, requirements={"id"="\d+"})
	 * @throws \Exception
	 */
	public function updateProduct(Request $request, EntityManagerInterface $em, int $id) : Response {
		$code = $request->request->get("code");
		$name = $request->request->get("name");
		$price = $request->request->get("price");
		(new ProductService($em))->updateProduct($id, $code, $name, $price);
		return $this->success();
	}

	/**
	 * Удаление материала
	 *
	 * @Route("/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
	 * @throws \Exception
	 */
	public function deleteProduct(EntityManagerInterface $em, int $id) : Response {
		(new ProductService($em))->deleteProduct($id);
		return $this->success();
	}

	/**
	 * Получение списка материалов
	 *
	 * @Route("/list", methods={"GET"})
	 * @throws \Exception
	 */
	public function getList(Request $request, EntityManagerInterface $em) : Response {
		$page = $request->query->getInt("page", 1);
		$pageData = $em->getRepository(Product::class)->getPage($page);
		return $this->json($pageData);
	}
}
