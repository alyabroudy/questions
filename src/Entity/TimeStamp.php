<?php
/**
 * Created by PhpStorm.
 * User: mohammad.alyabroudy
 * Date: 8/5/2020
 * Time: 10:12 AM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


trait TimeStamp
{
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimeStamps()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now','Europe/Berlin'));
        }
        $this->setUpdatedAt(new \DateTime('now'));
    }

}