<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderProductMatch;
use App\Entity\Product;
use App\Exception\OrderNotFound;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class OrderService {
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * Конструктор
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * Создать заказ
	 *
	 * @param int|null $orderId Идентификатор заявки
	 * @param DateTime $orderDate Дата заявки
	 * @param DateTime $deliveryDate Дата поставки
	 *
	 * @return Order
	 */
	public function createOrder(int $orderId, DateTime $orderDate, DateTime $deliveryDate) : Order {
		$repository = $this->entityManager->getRepository(Order::class);
		$order = $repository->findOneBy([
			"orderId" => $orderId,
			"orderDate" => $orderDate,
		]);
		if (!$order) {
			$order = (new Order())
				->setOrderId($orderId)
				->setOrderDate($orderDate)
				->setDeliveryDate($deliveryDate);
			$this->entityManager->persist($order);
			$this->entityManager->flush();
		}
		return $order;
	}

	/**
	 * Обновить заказ
	 *
	 * @param int $id Идентификатор заказа
	 * @param int|null $orderId Идентификатор заявки
	 * @param DateTime|null $orderDate Дата заявки
	 * @param DateTime|null $deliveryDate Дата поставки
	 *
	 * @return Product
	 * @throws EntityNotFoundException
	 */
	public function updateOrder(int $id, ?int $orderId, ?DateTime $orderDate, ?DateTime $deliveryDate) : Product {
		$order = $this->entityManager->getRepository(Order::class)->find($id);
		if (!$order) {
			throw new OrderNotFound($id);
		}
		if ($orderId) {
			$order->setOrderId($orderId);
		}
		if ($orderDate) {
			$order->setOrderDate($orderDate);
		}
		if ($deliveryDate) {
			$order->setDeliveryDate($deliveryDate);
		}
		$order->setUpdatedAt(new DateTime());
		$this->entityManager->flush();

		return $order;
	}

	/**
	 * Удалить заказ
	 *
	 * @param int $id Идентификатор заказа
	 *
	 * @return Order
	 * @throws OrderNotFound
	 */
	public function deleteOrder(int $id) : Order {
		$order = $this->entityManager
			->getRepository(Order::class)
			->find($id);
		if (!$order) {
			throw new OrderNotFound($id);
		}
		$this->entityManager->remove($order);
		$this->entityManager->flush();

		return $order;
	}

	/**
	 * Добавить материал к заказу
	 *
	 * @param Order $order Заказ
	 * @param string $code Код материала
	 * @param string $name Наименование материала
	 * @param string $quantity Количество
	 *
	 * @return $this
	 */
	public function addProduct(Order $order, string $code, string $name, string $quantity) : self {
		$products = $this->entityManager
			->getRepository(Product::class)
			->getSimilarityProducts($name);

		$orderProduct = (new OrderProduct())
			->setOrder($order)
			->setCode($code)
			->setName($name)
			->setQuantity($quantity);

		if ($products->count() === 1) {
			$orderProduct->setProduct($products->first());
		}

		$this->entityManager->persist($orderProduct);
		$this->entityManager->flush();

		if ($products->count() > 1) {
			$sort = 1;
			foreach ($products as $product) {
				$orderProductMatches = (new OrderProductMatch())
					->setOrder($order)
					->setProduct($product)
					->setOrderProduct($orderProduct)
					->setSort($sort++);
				$this->entityManager->persist($orderProductMatches);
			}
			$this->entityManager->flush();
		}

		return $this;
	}
}
