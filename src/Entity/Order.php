<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTriggeredEntity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Заказ
 *
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="order_unique",
 *            columns={"order_id", "order_date"}
 *        )
 *    }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Order implements JsonSerializable {
	use TimestampTriggeredEntity;

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
	 * Идентификатор заявки клиента
	 *
	 * @var int
	 *
	 * @ORM\Column(name="order_id", type="integer", nullable=false)
	 */
	private int $orderId;

	/**
	 * Дата заказа
	 *
	 * @var DateTime
	 *
	 * @ORM\Column(name="order_date", type="date", nullable=false)
	 */
	private DateTime $orderDate;

	/**
	 * Дата поставки
	 *
	 * @var DateTime
	 *
	 * @ORM\Column(name="delivery_date", type="datetime", nullable=false)
	 */
	private DateTime $deliveryDate;

	/**
	 * Дата создания записи
	 *
	 * @var DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", nullable=false, columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
	 */
	protected $createdAt;

	/**
	 * Дата обновления записи
	 *
	 * @var DateTime|null
	 *
	 * @ORM\Column(name="updated_at", type="datetime", columnDefinition="TIMESTAMP")
	 */
	protected $updatedAt;

	/**
	 * Связь с материалами
	 *
	 * @var Collection|Product[]
	 *
	 * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"remove"})
	 */
	private Collection $products;

	/**
	 * Конструктор
	 */
	public function __construct() {
		$this->products = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getOrderId() : int {
		return $this->orderId;
	}

	/**
	 * @param int $orderId
	 *
	 * @return Order
	 */
	public function setOrderId(int $orderId) : Order {
		$this->orderId = $orderId;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getOrderDate() : DateTime {
		return $this->orderDate;
	}

	/**
	 * @param DateTime $orderDate
	 *
	 * @return Order
	 */
	public function setOrderDate(DateTime $orderDate) : Order {
		$this->orderDate = $orderDate;

		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getDeliveryDate() : DateTime {
		return $this->deliveryDate;
	}

	/**
	 * @param DateTime $deliveryDate
	 *
	 * @return Order
	 */
	public function setDeliveryDate(DateTime $deliveryDate) : Order {
		$this->deliveryDate = $deliveryDate;

		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getProducts() : Collection {
		return $this->products;
	}

	/**
	 * @param Collection $products
	 *
	 * @return Order
	 */
	public function setProducts(Collection $products) : Order {
		$this->products = $products;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			"id" => $this->getId(),
			"order_id" => $this->getOrderId(),
			"order_date" => $this->getOrderDate(),
			"delivery_date" => $this->getDeliveryDate(),
			"products" => $this->getProducts()->toArray(),
		];
	}
}
