<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Singer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SingerController extends Controller {

    /**
     * 添加歌手
     * @Route("/add_singer")
     * @Method("POST")
     */
    public function addSinger (Request $request) {
        $name = $request->get('singer_name');
        $img = $request->get('head_img');
        if (!isset($name) || !isset($img) || $img == '' || $name == '') {
            return new JsonResponse(['code' => '40030', 'msg' => '内容不完整']);
        }
        $create_time = time();
        $em = $this->getDoctrine()->getManager();
        $singer = new Singer(null, $name, $img, $create_time);
        $singer->setCreateTime($create_time);
        $singer->setHeadImg($img);
        $singer->setName($name);
        $em->persist($singer);
        $em->flush();
        return new JsonResponse(['code' => '200', 'msg' => 'success']);
    }
    /**
     * 获取歌手列表
     * @Route("/get_singerlist")
     * @Method("POST")
     */
    public function  getSingerList (Request $request) {
        $searchStr = $request->get('searchStr');
        $pageNum = $request->get('pageNum');
        $pageSize = $request->get('pageSize');
        if (!isset($searchStr) || $searchStr == '') {
            // 查询所有
            $res = $this->getDoctrine()->getRepository('AppBundle:Singer')->getAllSingers($pageNum, $pageSize);
            $all = $this->getDoctrine()->getRepository('AppBundle:Singer')->findAll();
            $counts = count($all);
            return new JsonResponse(['code' => 200, 'counts' => $counts, 'list' => $res]);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Singer')->searchSingers($searchStr, $pageNum, $pageSize);
        $counts = count($res);
        return new JsonResponse(['code' => '200','counts' => $counts, 'list' => $res]);
    }
    /**
     * 删除
     * @Route("/del_singer_byids")
     * @Method("POST")
     */
    public function delSingerAction(Request $request)
    {
        $delIds = $request->get('ids');
        if (!is_array($delIds) || count($delIds) <= 0) {
            return new JsonResponse(['code' => '40030', 'msg' => '请选择要删除的项']);
        }

        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($delIds); $i++) {
            $entity = $this->getDoctrine()->getRepository('AppBundle:Singer')->findOneBy(['id' => $delIds[$i]]);
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
     * 查询内容
     * @Route("/get_singerById")
     * @Method("POST")
     */
    public function getSingerContent (Request $request)
    {
        $id = $request->get('id');
        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Singer')->getSingerById($id);

        return new JsonResponse(['code' => '200', 'list' => $res]);
    }
    /**
     * 修改内容根据id
     * @Route("/edit_singerByid")
     * @Method("POST")
     */
    public function editSingerByid (Request $request) {
        $id = $request->get('id');
        $name = $request->get('name');
        $img = $request->get('img');

        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'msg' => 'id错误']);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $soup = $em->getRepository('AppBundle:Singer')->find($id);
        $soup->setName($name);
        $soup->setCreateTime(time());
        $soup->setHeadImg($img);
        $em->flush();
        return new JsonResponse(['code' => '200']);
    }
    /**
     * 获取所有的歌手
     * @Route("/get_allSinger")
     * @Method("GET")
     */
    public function getAllSinger () {
        $res = $this->getDoctrine()->getRepository('AppBundle:Singer')->getSingers();
        return new JsonResponse(['code' => '200', 'list' => $res]);
    }
}