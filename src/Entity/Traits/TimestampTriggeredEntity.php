<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Трейт, добавляющий методы обновления полей created_at и updated_at
 *
 * @package App\Entity\Traits
 * @ORM\HasLifecycleCallbacks
 */
trait TimestampTriggeredEntity {
	use TimestampableEntity;
	/**
	 * Gets triggered only on insert
	 *
	 * @ORM\PrePersist
	 */
	public function onPrePersist() {
		$this->createdAt = new DateTime();
	}

	/**
	 * Gets triggered every time on update
	 *
	 * @ORM\PreUpdate
	 */
	public function onPreUpdate() {
		$this->updatedAt = new DateTime();
	}
}
