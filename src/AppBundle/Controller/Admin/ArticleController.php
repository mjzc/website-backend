<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Articles;
use AppBundle\Entity\article_class;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Utils\APIResponseGenerator;
use AppBundle\Utils\APIResponseCode;

class ArticleController extends Controller
{

    /**
     * 文章查询
     * @Route("/web_getArticleList")
     * @Method("POST")
     */
    public function searchArticleAction (Request $request)
    {
        $searchStr = $request->get('searchStr');
        $pageNum = $request->get('pageNum');
        $pageSize = $request->get('pageSize');
        if (!isset($searchStr) || $searchStr == '') {
            // 查询所有文章
            $res = $this->getDoctrine()->getRepository('AppBundle:Articles')->getAllArticles($pageNum, $pageSize);
            $all = $this->getDoctrine()->getRepository('AppBundle:Articles')->findAll();
            $counts = count($all);
            return new JsonResponse(['code' => 200, 'counts' => $counts, 'list' => $res]);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Articles')->searchArticlesByTitleClass($searchStr, $pageNum, $pageSize);
        $counts = count($res);
        return new JsonResponse(['code' => '200','counts' => $counts, 'list' => $res]);
    }

    /**
     * 删除文章
     * @Route("/web_delArticles")
     * @Method("POST")
     */
    public function delArticlesAction(Request $request)
    {
        $delIds = $request->get('ids');
        if (!is_array($delIds) || count($delIds) <= 0) {
            return new JsonResponse(['code' => '40030', 'msg' => '请选择要删除的项']);
        }

        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($delIds); $i++) {
            $entity = $this->getDoctrine()->getRepository('AppBundle:Articles')->findOneBy(['id' => $delIds[$i]]);
            if ($entity != null) {
                $em->remove($entity);
            }
        }
        $em->flush();
        return new JsonResponse([
            'code' => '200',
            'msg' => 'del success'
        ]);

    }

    /**
     * 添加文章
     * @Route("/web_addArticle")
     * @Method("POST")
     */
    public function addArticleAction(Request $request)
    {
        $title = $request->get('title');
        $content = $request->get('content');
        $classId = $request->get('classId');
        $introductionText = $request->get('introductionText');
        if ($title == '' || $content == '' || !isset($classId) || !isset($introductionText)) {
            return new JsonResponse(['code' => '40030', 'msg' => '内容不完整']);
        }

        $em = $this->getDoctrine()->getManager();
        $belongClass = $classId;
        $time = time();
        $article = new Articles(null, $title, $content, $time, $classId, $introductionText);
        $article->setTitle($title);
        $article->setContentHtml($content);
        $article->setCreateTime($time);
        $article->setBelongClass($belongClass);
        $article->setIntroductionText($introductionText);
        $em->persist($article);
        $em->flush();
        return new JsonResponse(['code' => '200', 'msg' => 'success']);
    }

    /**
     * 查询所有分类
     * @Route("/web_getAllClass")
     * @Method("GET")
     */
    public function getArticlesAllClass ()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:article_class');
        $res = $repository->findAll();
        return new JsonResponse(['code' => '200', 'list' => $res]);
    }

    /**
     * 查询文章内容
     * @Route("/web_getArticleById")
     * @Method("POST")
     */
    public function getArticleContent (Request $request)
    {
        $id = $request->get('id');
        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'msg' => '不存在文章']);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Articles')->find($id);
        $counts = count($res);

        return new JsonResponse(['code' => '200', 'result' => $res]);
    }
    /**
     * 修改文章
     * @Route("/web_editArticleById")
     * @Method("POST")
    */
    public function editArticle (Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $content = $request->get('contentHtml');
        $classId = $request->get('classId');
        $introductionText = $request->get('introductionText');
        if (!isset($id)|| $id == '') {
            return new JsonResponse(['code' => '40030', 'msg' => '不存在此id']);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Articles')->editArticleById($id, $title, $content, $classId, $introductionText);
        if ($res == 0) {
            return new JsonResponse(['code' => '400', 'msg' => '删除失败']);
        }
        return new JsonResponse(['code' => '200', 'msg' => '删除成功']);
    }
    /**
     * 根据classId查询所有的文章
     * @Route("/web_getArticles")
     * @Method("POST")
     */
    public function getArticles(Request $request)
    {
        $classId = $request->get('classId');
        if (!isset($classId)) {
            return new JsonResponse(['code' => '40030', 'msg' => '不存在此id']);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $data = $em->getRepository('AppBundle:article_class')->find($classId);
        $className = $data->getClassName();
        $res = $this->getDoctrine()->getRepository('AppBundle:Articles')->getArticlesByClassId($classId);
        return new JsonResponse(['code' => 200, 'className' => $className, 'list' => $res]);
    }
}