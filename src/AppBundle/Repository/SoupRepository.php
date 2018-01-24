<?php

namespace AppBundle\Repository;


class SoupRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 获取全部数据
     * @param $pageSize
     * @param $pageNum
     * @return array
     */
    public function getAllSoup ($pageNum = 1, $pageSize = 10)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb->select('p')
            ->setFirstResult(intval($pageNum-1) * $pageSize)
            ->setMaxResults($pageSize)
            ->orderBy('p.createTime','DESC')
            ->getQuery();
        $res = $query->getArrayResult();
        return $res;
    }

    /**
     * 删除数据
     * @param ids
     * @return array
     */
    public function delSoupByIds ($ids)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->delete()
            ->where('p.id in (:ids)')
            ->setParameter(':ids',$ids)
            ->getQuery();
        $res = $query->getResult();
        return $res;
    }

    /**
     * 查询数据
     * @param $searchStr
     * @param $pageNum
     * @param $pageSize
     * @return array
     */
    public function searchSoupByTitle ($searchStr, $pageNum = 1, $pageSize = 10)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->select('p')
            ->where('p.title LIKE :searchStr2')
            ->setParameter(':searchStr2', '%'.$searchStr.'%')
            ->orderBy('p.createTime', 'DESC')
            ->getQuery();
      $allList = $query->getArrayResult();

      $query->setFirstResult(intval($pageNum - 1) * $pageSize)
            ->setMaxResults($pageSize);
      $list = $query->getArrayResult();
      $counts = count($allList);
      $res = array("list" => $list, "counts" => $counts);
      return $res;
    }
}
