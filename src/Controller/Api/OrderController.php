<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Exception\OrderNotFound;
use App\Service\OrderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Работа с сущностью Заказ
 *
 * @Route("/api/order")
 */
class OrderController extends AbstractApiController {
	/**
	 * Создание заказа по заявке
	 *
	 * @Route(methods={"POST"})
	 * @throws \Exception
	 */
	public function createProduct(Request $request, EntityManagerInterface $em) : Response {
		$orderId = $request->request->get("order_id");
		if (!$orderId) {
			throw new ParameterNotFoundException("order_id");
		}
		$orderDate = $request->request->get("order_date");
		if (!$orderDate) {
			throw new ParameterNotFoundException("order_date");
		}
		$deliveryDate = $request->request->get("delivery_date");
		if (!$deliveryDate) {
			throw new ParameterNotFoundException("delivery_date");
		}
		$productCode = $request->request->get("product_code");
		if (!$productCode) {
			throw new ParameterNotFoundException("product_code");
		}
		$productName = $request->request->get("product_name");
		if (!$productName) {
			throw new ParameterNotFoundException("product_name");
		}
		$quantity = $request->request->get("quantity");
		if (!$quantity) {
			throw new ParameterNotFoundException("quantity");
		}
		$orderService = new OrderService($em);
		$order = $orderService->createOrder($orderId, new DateTime($orderDate), new DateTime($deliveryDate));
		$orderService->addProduct($order, $productCode, $productName, $quantity);
		return $this->json([
			"id" => $order->getId(),
		]);
	}

	/**
	 * Получение информации о заказе
	 *
	 * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function getOrder(EntityManagerInterface $em, int $id) : Response {
		$order = $em->getRepository(Order::class)
			->createQueryBuilder("o")
			->where("o.id = :id")
			->setParameter("id", $id)
			->addSelect("op", "opm")
			->join("o.products", "op")
			->leftJoin("op.matchedProducts", "opm")
			->join("opm.product", "p")
			->andWhere("p.deletedAt IS NULL")
			->getQuery()
			->getResult();

		if (!$order) {
			throw new OrderNotFound($id);
		}
		return $this->json($order);
	}

	/**
	 * Обновление информации о заказе
	 *
	 * @Route("/{id}", methods={"PUT"}, requirements={"id"="\d+"})
	 * @throws \Exception
	 */
	public function updateOrder(Request $request, EntityManagerInterface $em, int $id) : Response {
		$orderId = $request->request->get("order_id");
		$orderDate = $request->request->get("order_date");
		if ($orderDate) {
			$orderDate = new DateTime($orderDate);
		}
		$deliveryDate = $request->request->get("delivery_date");
		if ($deliveryDate) {
			$deliveryDate = new DateTime($deliveryDate);
		}
		(new OrderService($em))->updateOrder($id, $orderId, $orderDate, $deliveryDate);
		return $this->success();
	}

	/**
	 * Удаление заказа
	 *
	 * @Route("/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
	 * @throws \Exception
	 */
	public function deleteOrder(EntityManagerInterface $em, int $id) : Response {
		(new OrderService($em))->deleteOrder($id);
		return $this->success();
	}

	/**
	 * Получение списка заказов
	 *
	 * @Route("/list", methods={"GET"})
	 * @throws \Exception
	 */
	public function getList(Request $request, EntityManagerInterface $em) : Response {
		$page = $request->query->getInt("page", 1);
		$pageData = $em->getRepository(Order::class)->getPage($page);
		return $this->json($pageData);
	}
}
