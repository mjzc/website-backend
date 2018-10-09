<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Song;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MusicController extends Controller {

    /**
     * 获取歌曲列表
     * @Route("/get_songlist")
     * @Method("POST")
     */
    public function  getSingerList (Request $request) {
        $searchStr = $request->get('searchStr');
        $pageNum = $request->get('pageNum');
        $pageSize = $request->get('pageSize');
        if (!isset($searchStr) || $searchStr == '') {
            // 查询所有
            $res = $this->getDoctrine()->getRepository('AppBundle:Song')->getAllSong($pageNum, $pageSize);
            $all = $this->getDoctrine()->getRepository('AppBundle:Song')->findAll();
            $counts = count($all);
            return new JsonResponse(['code' => 200, 'counts' => $counts, 'list' => $res]);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Song')->searchSong($searchStr, $pageNum, $pageSize);
        $counts = count($res);
        return new JsonResponse(['code' => '200','counts' => $counts, 'list' => $res]);
    }
    /**
     * 删除通过id
     * @Route("/del_songByIds")
     * @Method("POST")
     */
    public function delSoups (Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || count($ids) == 0) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Song')->delSongByIds($ids);
        return new JsonResponse(['code' => '200', 'counts' => $res]);
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
            $upload_path = '/home/webmaster/git_projects/web/Resources/music/';
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
        } else {
            return new JsonResponse(['code' => '200', 'msg' => '已存在', 'url' => $upload_path . '/'.$_FILES['file']['name']]);
        }
        // 判断文件是否保存
        if($isMove) {
            return new JsonResponse(['code' => '200', 'msg' => '成功', 'url' => $upload_path . '/'.$_FILES['file']['name']]);
        } else{
            return new JsonResponse(['code' => '400', 'msg' => '请重新上传']);
        }
    }
    /**
     * 添加音乐
     * @Route("/sumbit_music_content")
     * @Method("POST")
     */
    public function sumbitMusicContent (Request $request) {
        $name = $request->get('name');
        $lyric = $request->get('lyric');
        $singerId = $request->get('singer_id');
        $musicUrl = $request->get('music_url');
        $imgUrl = $request->get('img_url');
        if($name == '' || $musicUrl == '') {
            return new JsonResponse(['code' => '401', 'msg' => '提交失败，内容不完整']);
        } else if ($singerId == '') {
            return new JsonResponse(['code' => '401', 'msg' => '提交的歌手不正确']);
        }
        $create_time = time();
        $em = $this->getDoctrine()->getManager();
        $music = new Song(null, $name, $lyric, $singerId, $musicUrl, $imgUrl, $create_time);
        $music->setSongName($name);
        $music->setLyric($lyric);
        $music->setSingerId($singerId);
        $music->setSongImg($imgUrl);
        $music->setSongUrl($musicUrl);
        $music->setCreateTime($create_time);
        $em->persist($music);
        $em->flush();
        return new JsonResponse(['code' => '200', 'msg' => 'success']);
    }
    /**
     * 根据ID查询数据
     * @Route("/web_getSongDetail")
     * @Method("POST")
     */
    public function getSongDetailById (Request $request)
    {
        $id = $request->get('id');
        if (!isset($id)) {
            return new JsonResponse(['code' => '40030', 'mag' => 'id错误']);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Song')->getSong($id);
        return new JsonResponse($res);
    }
    /**
     * 修改内容根据id
     * @Route("/edit_song_detail")
     * @Method("POST")
     */
    public function editSongByid (Request $request) {
        $id = $request->get('id');
        $name = $request->get('name');
        $lyric = $request->get('lyric');
        $singerId = $request->get('singer_id');
        $musicUrl = $request->get('music_url');
        $imgUrl = $request->get('img_url');
        if($name == '' || $musicUrl == '') {
            return new JsonResponse(['code' => '401', 'msg' => '提交失败，内容不完整']);
        } else if ($singerId == '') {
            return new JsonResponse(['code' => '401', 'msg' => '提交的歌手不正确']);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $music = $em->getRepository('AppBundle:Song')->find($id);
        $music->setSongName($name);
        $music->setLyric($lyric);
        $music->setSingerId($singerId);
        $music->setSongImg($imgUrl);
        $music->setSongUrl($musicUrl);
        $music->setCreateTime(time());
        $em->flush();
        return new JsonResponse(['code' => '200']);
    }
}