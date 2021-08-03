<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Трейт поддержки поля created_at у сущностей
 *
 * @package App\Entity\Traits
 * @ORM\HasLifecycleCallbacks
 */
trait CreatedAtTriggeredEntity {
	/**
	 * @var DateTime
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	protected DateTime $createdAt;

	/**
	 * Sets createdAt.
	 *
	 * @return $this
	 */
	public function setCreatedAt(DateTime $createdAt) {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Returns createdAt.
	 *
	 * @return DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * Gets triggered only on insert
	 *
	 * @ORM\PrePersist
	 */
	public function onPrePersist() {
		$this->createdAt = new DateTime();
	}
}
