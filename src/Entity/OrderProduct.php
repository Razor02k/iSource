<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTriggeredEntity;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Табличная часть заказа
 *
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="order_products")
 * @ORM\HasLifecycleCallbacks
 */
class OrderProduct implements JsonSerializable {
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
	 * @ORM\ManyToOne(targetEntity="Order", inversedBy="products")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private Order $order;

	/**
	 * Материал
	 *
	 * @var Product|null
	 *
	 * @ORM\ManyToOne(targetEntity="Product")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private ?Product $product;

	/**
	 * Код материала
	 *
	 * @var string
	 *
	 * @ORM\Column(type="string", length=50, nullable=false)
	 */
	private string $code;

	/**
	 * Наименование материала
	 *
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private string $name;

	/**
	 * Количество материала
	 *
	 * @var string
	 *
	 * @ORM\Column(type="decimal", precision=15, scale=3, nullable=false)
	 */
	private string $quantity;

	/**
	 * Дата создания записи
	 *
	 * @var DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
	 */
	protected DateTime $createdAt;

	/**
	 * Связь с подобранными материалами
	 *
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="OrderProductMatch", mappedBy="orderProduct", cascade={"remove"})
	 */
	private Collection $matchedProducts;

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
	 * @return OrderProduct
	 */
	public function setOrder(Order $order) : OrderProduct {
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
	 * @return OrderProduct
	 */
	public function setProduct(Product $product) : OrderProduct {
		$this->product = $product;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCode() : string {
		return $this->code;
	}

	/**
	 * @param string $code
	 *
	 * @return OrderProduct
	 */
	public function setCode(string $code) : OrderProduct {
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return OrderProduct
	 */
	public function setName(string $name) : OrderProduct {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getQuantity() : int {
		return $this->quantity;
	}

	/**
	 * @param string $quantity
	 *
	 * @return OrderProduct
	 */
	public function setQuantity(string $quantity) : OrderProduct {
		$this->quantity = $quantity;

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
	 * @return OrderProduct
	 */
	public function setCreatedAt(DateTime $createdAt) : OrderProduct {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getMatchedProducts() : Collection {
		return $this->matchedProducts;
	}

	/**
	 * @param Collection $matchedProducts
	 *
	 * @return OrderProduct
	 */
	public function setMatchedProducts(Collection $matchedProducts) : OrderProduct {
		$this->matchedProducts = $matchedProducts;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			"code" => $this->getCode(),
			"name" => $this->getName(),
			"quantity" => $this->getQuantity(),
			"matches" => $this->getMatchedProducts()->toArray(),
		];
	}
}
