<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository {

    public function findActive($results = 10, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Contact c ORDER BY c.sortNumber DESC');

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }
} ?>
