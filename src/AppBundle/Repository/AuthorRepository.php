<?php

namespace AppBundle\Repository;


class AuthorRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * 根据用户账号获取用户信息
     * @param $account
     * @return $res
     */
    public function getAuthorInfo ($account)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->where('p.account = :account')
            ->setParameter('account',$account)
            ->getQuery();
        $res = $query->getArrayResult();
        return $res;
    }
}
