<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Singer;
use AppBundle\Entity\Song;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MusicController extends Controller {

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
    /**
     * 上传音乐
     * @Route("/upload_music")
     * @Method("POST")
    */
    public function uploadMusic () {
        $type = ['audio/mp3'];
        $size = 10 * 1024 *1024;
        $imgType = $_FILES['file']['type'];
        $imgSize = $_FILES['file']['size'];
        $isMove = false;
        if ($_SERVER['SERVER_NAME'] == $this->getParameter('database_host')) {
            $upload_path = 'Resources/music/';
        } else {
            $upload_path = '/home/webmaster/git_projects/aoyi-blog-admin-api/web/Resources/music/';
        }
        if (!in_array($imgType,$type)) {
            return new JsonResponse(['code' => '401', 'msg' => '上传的图片类型错误']);
        }
        if ($imgSize > $size) {
            return new JsonResponse(['code' => '402', 'msg' => '上传的图片过大']);
        }
        // 判断是否是通过HTTP POST上传的
        if(!is_uploaded_file($_FILES['file']['tmp_name'])){
            // 如果不是通过HTTP POST上传的
            return new JsonResponse(['code' => '403', 'msg' => '不是通过post上传的']);
        }
        // 判断存图片的文件夹是否存在
        if(!file_exists($upload_path)) {
            mkdir ($upload_path,0777,true);
        }
        // 判断图片是否存在
        if (!file_exists($upload_path.$_FILES['file']['name'])) {
            $isMove = move_uploaded_file($_FILES['file']['tmp_name'],$upload_path . $_FILES['file']['name']);
        }
        // 判断文件是否保存
        if($isMove) {
            return new JsonResponse(['code' => '200', 'msg' => '成功', 'url' => $upload_path . '/'.$_FILES['file']['name']]);
        }else{
            return new JsonResponse(['code' => '400', 'msg' => '请重新上传']);
        }
    }
    /**
     * 上传音乐
     * @Route("/sumbit_music_content")
     * @Method("POST")
     */
    public function sumbitMusicContent (Request $request) {
        $name = $request->get('name');
        $lyric = $request->get('lyric');
        $singerId = $request->get('singer_id');
        $musicUrl = $request->get('music_url');
        $imgUrl = $request->get('img_url');
        if(!isset($name) || $name == '' || !isset($singerId) || $singerId == '' || isset($musicUrl) || $musicUrl == '') {
            return new JsonResponse(['code' => '401', 'msg' => '提交失败，内容不完整']);
        }
        $create_time = time();
        $em = $this->getDoctrine()->getManager();
        $music = new Song(null, $name, $lyric, $singerId, $musicUrl, $imgUrl, $create_time);
        $music->setLyric($lyric);
        $em->persist($music);
        $em->flush();
        return new JsonResponse(['code' => '200', 'msg' => 'success']);

    }
}