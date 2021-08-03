<?php

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository {
	/**
	 * Получить страницу списка материалов
	 *
	 * @param int $page Номер старницы
	 * @param int $onPageCount Количество элементов на странице
	 *
	 * @return array
	 */
	public function getPage(int $page = 1, int $onPageCount = 10) : array {
		return $this->createQueryBuilder("p")
			->orderBy("p.id")
			->setFirstResult(($page - 1) * $onPageCount)
			->setMaxResults($onPageCount)
			->getQuery()
			->getResult();
	}

	/**
	 * Получить похожие материалы
	 *
	 * @param string $name Наименование материала
	 * @param int $limit Лимит выдаваемых материалов
	 *
	 * @return ArrayCollection
	 */
	public function getSimilarityProducts(string $name, int $limit = 10) : ArrayCollection {
		$parts = explode(" ", $name);
		usort($parts, fn (string $a, string $b) => mb_strlen($a) < mb_strlen($b));
		$baseBuilder = $this->createQueryBuilder("p")
			->where("p.name LIKE :name");
		$result = [];
		$excludeIds = [];
		foreach ($parts as $part) {
			$qb = (clone $baseBuilder)->setParameter("name", "%$part%");
			if ($excludeIds) {
				$qb->andWhere("p.id NOT IN (:excludeIds)")
					->setParameter("excludeIds", $excludeIds);
			}

			$products = $qb->setMaxResults($limit)
				->getQuery()
				->getResult();

			if ($products) {
				$limit -= count($products);

				$result = array_merge($result, $products);
				if (!$limit) {
					break;
				}

				foreach ($products as $product) {
					$excludeIds[] = $product->getId();
				}
			}
		}

		return new ArrayCollection($result);
	}
}
