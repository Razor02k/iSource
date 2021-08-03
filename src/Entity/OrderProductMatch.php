<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTriggeredEntity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Подобранные соответствия материалов к заказам
 *
 * @ORM\Entity()
 * @ORM\Table(name="order_product_matches")
 * @ORM\HasLifecycleCallbacks
 */
class OrderProductMatch implements JsonSerializable {
	use CreatedAtTriggeredEntity;
	/**
	 * Идентификатор записи
	 *
	 * @var int|null
	 *
	 * @ORM\Id()
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private ?int $id = null;

	/**
	 * Заказ
	 *
	 * @var Order
	 *
	 * @ORM\ManyToOne(targetEntity="Order")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private Order $order;

	/**
	 * Материал
	 *
	 * @var Product
	 *
	 * @ORM\ManyToOne(targetEntity="Product")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private Product $product;

	/**
	 * Строка табличной части заказа
	 *
	 * @var OrderProduct
	 *
	 * @ORM\ManyToOne(targetEntity="OrderProduct", inversedBy="matchedProducts")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private OrderProduct $orderProduct;

	/**
	 * Сортировка
	 *
	 * @var int
	 *
	 * @ORM\Column(name="sort", type="integer", nullable=false)
	 */
	private int $sort;

	/**
	 * Дата создания записи
	 *
	 * @var DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
	 */
	protected DateTime $createdAt;

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @return Order
	 */
	public function getOrder() : Order {
		return $this->order;
	}

	/**
	 * @param Order $order
	 *
	 * @return OrderProductMatch
	 */
	public function setOrder(Order $order) : OrderProductMatch {
		$this->order = $order;

		return $this;
	}

	/**
	 * @return Product
	 */
	public function getProduct() : Product {
		return $this->product;
	}

	/**
	 * @param Product $product
	 *
	 * @return OrderProductMatch
	 */
	public function setProduct(Product $product) : OrderProductMatch {
		$this->product = $product;

		return $this;
	}

	/**
	 * @return OrderProduct
	 */
	public function getOrderProduct() : OrderProduct {
		return $this->orderProduct;
	}

	/**
	 * @param OrderProduct $orderProduct
	 *
	 * @return OrderProductMatch
	 */
	public function setOrderProduct(OrderProduct $orderProduct) : OrderProductMatch {
		$this->orderProduct = $orderProduct;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getSort() : int {
		return $this->sort;
	}

	/**
	 * @param int $sort
	 *
	 * @return OrderProductMatch
	 */
	public function setSort(int $sort) : OrderProductMatch {
		$this->sort = $sort;

		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getCreatedAt() : DateTime {
		return $this->createdAt;
	}

	/**
	 * @param DateTime $createdAt
	 *
	 * @return OrderProductMatch
	 */
	public function setCreatedAt(DateTime $createdAt) : OrderProductMatch {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			"id" => $this->product->getId(),
			"sort" => $this->getSort(),
		];
	}
}
