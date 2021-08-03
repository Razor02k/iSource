<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Базовый класс для методов API
 *
 * @package App\Controller\Api
 */
class AbstractApiController extends AbstractController {
	public function success(): Response {
		return $this->json([
			"success" => true,
		]);
	}
}
