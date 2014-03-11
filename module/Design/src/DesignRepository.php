<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mandala\UserModule\User;

class DesignRepository extends EntityRepository
{
    public function getPaginator(DesignSearchCriteria $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $builder = $this->createQueryBuilder('d');
        if (isset($criteria->userFavorited)) {
            $ids = $this->getFavoritedDesignIds($criteria->userFavorited);
            $builder->andWhere('d.id IN (:ids)');
            $builder->setParameter('ids', $ids);
        }
        if (isset($criteria->status)) {
            $builder->andWhere('d.status = :status');
            $builder->setParameter(':status', $criteria->status);
        }
        if (isset($criteria->author)) {
            $builder->andWhere('d.author = :author');
            $builder->setParameter(':author', $criteria->author);
        }

        foreach ($orderBy as $sort => $order) {
            $builder->addOrderBy('d.' . $sort, $order);
        }
        if ($limit) {
            $builder->setMaxResults($limit);
        }
        if ($offset) {
            $builder->setFirstResult($offset);
        }
        $query = $builder->getQuery();
        return new Paginator($query);
    }

    private function getFavoritedDesignIds(User $user) {
        $dql = '
            SELECT DISTINCT d.id
            FROM Mandala\DesignModule\Design d
            INNER JOIN Mandala\DesignModule\DesignFavorite f WITH f.design = d
            WHERE f.user = :user
        ';
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('user', $user);
        return array_map('current', $query->getScalarResult());
    }
}