<?php

namespace App\Trait;

trait EmFlushTrait
{
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}