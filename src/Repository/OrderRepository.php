<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository {
	/**
	 * Получить страницу списка заказов
	 *
	 * @param int $page Номер старницы
	 * @param int $onPageCount Количество элементов на странице
	 *
	 * @return array
	 */
	public function getPage(int $page = 1, int $onPageCount = 10) : array {
		return $this->createQueryBuilder("o")
			->orderBy("o.id")
			->setFirstResult(($page - 1) * $onPageCount)
			->setMaxResults($onPageCount)
			->getQuery()
			->getResult();
	}
}
