<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\ProductNotFound;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProductService {
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	/**
	 * ProductService constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * Создать материал
	 *
	 * @param string $code Код материала
	 * @param string $name Наименование материала
	 * @param string $price Цена материала
	 *
	 * @return Product
	 */
	public function createProduct(string $code, string $name, string $price) : Product {
		$product = (new Product())
			->setCode($code)
			->setName($name)
			->setPrice($price);
		$this->entityManager->persist($product);
		$this->entityManager->flush();

		return $product;
	}

	/**
	 * Обновить материал
	 *
	 * @param int $id Идентификатор материала
	 * @param string|null $code Код материала
	 * @param string|null $name Наименование материала
	 * @param string|null $price Цена материала
	 *
	 * @return Product
	 * @throws ProductNotFound
	 */
	public function updateProduct(int $id, ?string $code, ?string $name, ?string $price) : Product {
		$product = $this->entityManager->getRepository(Product::class)->find($id);
		if (!$product) {
			throw new ProductNotFound($id);
		}
		if ($name) {
			$product->setName($name);
		}
		if ($code) {
			$product->setCode($code);
		}
		if ($price) {
			$product->setPrice($price);
		}
		$product->setUpdatedAt(new DateTime());
		$this->entityManager->flush();

		return $product;
	}

	/**
	 * Удалить материал
	 *
	 * @param int $id Идентификатор материала
	 *
	 * @return Product
	 * @throws ProductNotFound
	 */
	public function deleteProduct(int $id) : Product {
		$product = $this->entityManager->getRepository(Product::class)->find($id);
		if (!$product) {
			throw new ProductNotFound($id);
		}
		$this->entityManager->remove($product);
		$this->entityManager->flush();

		return $product;
	}
}
