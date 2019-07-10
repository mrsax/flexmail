<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 * timestamps for creation, deletion etc... of database objects
 * @author Mathieu Dierckx mathieu.dierckx@skynet.be
 *
 * Trait TimestampableTrait
 */
trait TimestampableTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * Triggered on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Triggered on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
}

    /**
     * Triggered on update
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        $this->deleted = new \DateTime("now");
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @throws \Exception
     */
    public function setCreated(\DateTime $created = null): void
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @param \DateTime $updated
     * @throws \Exception
     */
    public function setUpdated(\DateTime $updated) :void
    {
        $this->updated = new \DateTime("now");
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param \DateTime $deleted
     */
    public function setDeleted(\DateTime $deleted): void
    {
        $this->deleted = $deleted;
    }

}






