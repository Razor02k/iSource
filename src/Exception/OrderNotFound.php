<?php

namespace App\Exception;

use Doctrine\ORM\EntityNotFoundException;

/**
 * Исключение, когда заказ не найден
 *
 * @package App\Exception
 */
class OrderNotFound extends EntityNotFoundException {
	/**
	 * Конструктор
	 *
	 * @param int $id Идетификатор заказа
	 */
	public function __construct(int $id) {
		parent::__construct("Заказ $id не найден", 404);
	}
}
