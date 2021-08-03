<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTriggeredEntity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JsonSerializable;

/**
 * Материал
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 * @ORM\HasLifecycleCallbacks
 */
class Product implements JsonSerializable {
	use TimestampTriggeredEntity;
	use SoftDeleteableEntity;

	/**
	 * Идентификатор записи
	 *
	 * @var int|null
	 *
	 * @ORM\Id()
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private ?int $id;

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
	 * Рекомендованная цена реализации
	 *
	 * @var string
	 *
	 * @ORM\Column(type="decimal", precision=15, scale=2, nullable=false)
	 */
	private string $price;

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
	 * Дата удаления записи
	 *
	 * @var DateTime|null
	 *
	 * @ORM\Column(name="deleted_at", type="datetime", columnDefinition="TIMESTAMP")
	 */
	protected $deletedAt;

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
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
	 * @return Product
	 */
	public function setCode(string $code) : Product {
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
	 * @return Product
	 */
	public function setName(string $name) : Product {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPrice() : string {
		return $this->price;
	}

	/**
	 * @param string $price
	 *
	 * @return Product
	 */
	public function setPrice(string $price) : Product {
		$this->price = str_replace(",", ".", $price);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			"id" => $this->getId(),
			"name" => $this->getName(),
			"price" => $this->getPrice(),
		];
	}
}
