<?php

namespace App\Trait;

trait EmPurgeTrait
{
    public function purge(): void
    {
        $table = $this->getClassMetadata()->getTableName();
        $emptyRsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery("DELETE FROM $table", $emptyRsm);
        $query->execute();
    }
}