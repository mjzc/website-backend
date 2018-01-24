<?php

namespace AppBundle\Repository;


class ArticlesRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 获取所有文章
     * @param $pageSize
     * @param $pageNum
     * @return $list
     */
    public function getAllArticles($pageNum = 1, $pageSize = 10)
    {
        $sql = 'SELECT c.id as classId, c.className, a.id, a.title, a.createTime 
        FROM AppBundle:Articles a, AppBundle:article_class c
        WHERE a.belongClass = c.id
        ORDER BY a.createTime DESC';
        $query = $this->getEntityManager()->createQuery($sql);
        $list = $query
            ->setFirstResult((intval($pageNum) - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->getArrayResult();
        return $list;
    }

    /**
     * 根据分类name／标题查询文章
     * @param $searchStr
     * @param $pageSize
     * @param $pageNum
     * @return $list
     */
    public function searchArticlesByTitleClass($searchStr, $pageNum, $pageSize)
    {
        $sql = 'SELECT a.id, a.title, a.createTime, a.belongClass, c.className, c.id 
        FROM AppBundle:Articles a, AppBundle:article_class c 
        WHERE c.id=a.belongClass 
        AND (c.className LIKE :searchStr OR a.title LIKE :searchStr)
        ORDER BY a.createTime DESC';
        $query = $this->getEntityManager()->createQuery($sql);
        $list = $query
            ->setParameter(':searchStr','%'.$searchStr.'%')
            ->setFirstResult((intval($pageNum) - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->getArrayResult();
        return $list;
    }
    /**
     * 根据id更新文章
     * @param $id
     * @return $count
     */
    public function editArticleById ($id, $title, $content, $classId, $introductionText) {
       $sql = 'UPDATE AppBundle:Articles a 
        SET a.title = :title, a.contentHtml = :contentHtml, a.createTime = :createTime, a.belongClass = :belongClass, a.introductionText = :introductionText
        WHERE a.id = :id';
       $createTime = time();
       $query = $this->getEntityManager()->createQuery($sql);
       $res = $query
           ->setParameter(':title', $title)
           ->setParameter(':contentHtml', $content)
           ->setParameter(':belongClass', $classId)
           ->setParameter(':createTime', $createTime)
           ->setParameter(':introductionText', $introductionText)
           ->setParameter(':id', $id)
           ->getArrayResult();
       return $res;
    }
    /**
     * 根据classId查询所有的文章
     * @param $classId
     * @return $res
     */
    public function getArticlesByClassId ($classId)
    {
        $sql = 'SELECT a.id, a.title, a.contentHtml,c.className, a.createTime, a.belongClass, a.introductionText
          FROM AppBundle:Articles a, AppBundle:article_class c
          WHERE a.belongClass = c.id
          AND a.belongClass = :classId
          ORDER BY a.createTime';
        $query = $this->getEntityManager()->createQuery($sql);
        $res = $query
            ->setParameter(':classId', $classId)
            ->getArrayResult();
        return $res;
    }
}


