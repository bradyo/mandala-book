<?php
namespace Mandala\BookModule;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookRepository extends EntityRepository
{
    public function getPaginator(BookCriteria $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $builder = $this->createQueryBuilder('b');
        if ($criteria->status !== null) {
            $builder->andWhere('b.status = :status');
            $builder->setParameter(':status', $criteria->status);
        }
        if ($criteria->author !== null) {
            $builder->andWhere('b.author = :author');
            $builder->setParameter(':author', $criteria->author);
        }

        foreach ($orderBy as $sort => $order) {
            $builder->addOrderBy('b.' . $sort, $order);
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
}