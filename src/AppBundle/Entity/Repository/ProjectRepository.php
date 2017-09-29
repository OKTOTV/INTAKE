<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository {

    public function findActive($results = 10, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p, a FROM AppBundle:Project p LEFT JOIN p.assets a ORDER BY p.created_at DESC');

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }
}
