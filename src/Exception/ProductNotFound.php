<?php

namespace App\Exception;

use Doctrine\ORM\EntityNotFoundException;

/**
 * Исключение, когда материал не найден
 *
 * @package App\Exception
 */
class ProductNotFound extends EntityNotFoundException {
	/**
	 * Конструктор
	 *
	 * @param int $id Идетификатор материала
	 */
	public function __construct(int $id) {
		parent::__construct("Материал $id не найден", 404);
	}
}
