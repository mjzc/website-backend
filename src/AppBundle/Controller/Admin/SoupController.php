<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Soup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SoupController  extends Controller
{

    /**
     * 添加鸡汤
     * @Route("/web_addSoup")
     * @Method("POST")
     */
    public function addSoup (Request $request)
    {
        $title = $request->get('title');
        $content = $request->get('content');
        $img = $request->get('img');

        if (!isset($title) || !isset($content)) {
            return new JsonResponse(['code' => '40030', 'mag' => '内容不完整']);
        }

        $time = time();
        $em = $this->getDoctrine()->getManager();
        $soup = new Soup(null, $title, $content, $time, $img);
        $soup->setTitle($title);
        $soup->setContent($content);
        $soup->setCreateTime($time);
        $soup->setImgLink($img);
        $em->persist($soup);
        $em->flush();
        return new JsonResponse(['code' => '200', 'mag' => 'success']);
    }

    /**
     * 获取鸡汤
     * @Route("/web_getSoupList")
     * @Method("POST")
     */
    public function getSoupList (Request $request)
    {
        $searchStr = $request->get('searchStr');
        $pageNum = $request->get('pageNum');
        $pageSize = $request->get('pageSize');
        if (!isset($searchStr) || $searchStr == '') {
            // 获取所有鸡汤
            $res = $this->getDoctrine()->getRepository('AppBundle:Soup')->getAllSoup($pageNum, $pageSize);
            $all = $this->getDoctrine()->getRepository('AppBundle:Soup')->findAll();
            $count = count($all);
            return new JsonResponse(['code' => '200', 'list' => $res, 'counts' => $count]);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Soup')->searchSoupByTitle($searchStr, $pageNum, $pageSize);
        $counts = count($res);
        return new JsonResponse(['code' => '200','counts' => $counts, 'list' => $res]);
    }

    /**
     * 删除通过id
     * @Route("/web_delSoupByIds")
     * @Method("POST")
     */
    public function delSoups (Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || count($ids) == 0) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Soup')->delSoupByIds($ids);
        return new JsonResponse(['code' => '200', 'counts' => $res]);
    }

    /**
     * 根据ID查询数据
     * @Route("/web_getSoupDetail")
     * @Method("POST")
     */
    public function getSoupDetailById (Request $request)
    {
        $id = $request->get('id');
        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Soup')->find($id);
        return new JsonResponse($res);
    }

    /**
     * 根据ID修改内容
     * @Route("/web_editSoupById")
     * @Method("POST")
     */
    public function editSoup (Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $content = $request->get('content');
        $img = $request->get('img');

        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }
        if (!isset($title) || !isset($content)) {
            return new JsonResponse(['code' => '40030', 'mag' => '请输入完整的内容']);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $soup = $em->getRepository('AppBundle:Soup')->find($id);
        $soup->setTitle($title);
        $soup->setCreateTime(time());
        $soup->setContent($content);
        $soup->setImgLink($img);
        $em->flush();
        return new JsonResponse(['code' => '200']);
    }
}