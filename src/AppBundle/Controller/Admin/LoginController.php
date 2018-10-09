<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Utils\APIResponseGenerator;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Author;
use AppBundle\Utils\APIResponseCode;


class LoginController extends APIResponseGenerator
{
    /**
     * 登陆接口
     * @Route("/web_login")
     * @Method("POST")
     */
    public function adminLoginAction(Request $request){

        $accountNum = $request->get('account');
        $password = $request->get('password');

        if ($accountNum == '' || $password == '') {
            return $this->generateResponseData(APIResponseCode::CODE_NOT_FOUND);
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Author')->getAuthorInfo($accountNum);

        // 用户不存在
        if (!isset($res[0]['account'])) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);

        }
        // 用户密码不正确
        if ($res[0]['password'] != $password) {
            return $this->generateResponseData(APIResponseCode::CODE_NOT_FOUND);
        }

        //成功
        $signer = new Sha256();

        $token = (new Builder())
            ->setIssuedAt(time())
            ->setNotBefore(time() + 60)
            ->setExpiration(time() + $this->getParameter('jwt_ttl'))
            ->set('username', $accountNum)
            ->sign($signer, $this->getParameter('secret'))
            ->getToken();

        return $this->generateResponseData(APIResponseCode::CODE_SUCCESS, [
            'id' => $res[0]['id'],
            'username' => $res[0]['account'],
            'token' => (string)$token
            ]);
    }

    /**
     * 获取用户的设置信息
     * @Route("/web_getAuthorInfo")
     * @Method("POST")
     */
    public function getAuthorInfoById (Request $request)
    {
        $id = $request->get('id');
        if (!isset($id)) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
        $res = $this->getDoctrine()->getRepository('AppBundle:Author')->find($id);

        if (is_null($res)) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
        if ($_SERVER['SERVER_NAME'] == $this->getParameter('database_host')) {
            $imgSrc = 'http://127.0.0.1:8000/'.$this->getParameter('upload_path').'/'.$res->getBlogHeadImg();
        } else {
            $imgSrc = 'https://www.mjiacc.cn/'.$this->getParameter('upload_path').'/'.$res->getBlogHeadImg();
        }
        $res->setBlogHeadImg($imgSrc);
        return new JsonResponse($res);
    }

    /**
     * 修改用户的设置信息
     * @Route("/web_editAuthorInfo")
     * @Method("POST")
     */
    public function editAuthorInfoById (Request $request)
    {
        $id = $request->get('id');
        $blogName = $request->get('blogName');
        $blogIntro = $request->get('blogIntro');
        $imgurl = $request->get('blogHeadImg');
        if (!isset($id)) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $author = $em->getRepository('AppBundle:Author')->find($id);
        if (!$author) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
        $author->setBlogName($blogName);
        $author->setBlogIntro($blogIntro);
        $author->setBlogHeadImg($imgurl);
        $em->flush();
        return $this->generateResponseData(APIResponseCode::CODE_SUCCESS);
    }

    /**
     * 上传博客头像图片
     * @Route("/web_uploadBlogHeadImg")
     * @Method("POST")
     */
    public function uploadImg ()
    {
        $type = ['image/png','image/jpeg','image/jpg'];
        $size = 2 * 1024 *1024;
        $imgType = $_FILES['file']['type'];
        $imgSize = $_FILES['file']['size'];
        $isMove = false;
        if ($_SERVER['SERVER_NAME'] == $this->getParameter('database_host')) {
            $upload_path = 'Resources/blogHeadImg/';
        } else {
            $upload_path = '/home/webmaster/git_projects/web/Resources/blogHeadImg/';
        }
        if (!in_array($imgType,$type)) {
            return new JsonResponse(['code' => '400', 'msg' => '上传的图片类型错误']);
        }
        if ($imgSize > $size) {
            return new JsonResponse(['code' => '400', 'msg' => '上传的图片过大']);
        }
        // 判断是否是通过HTTP POST上传的
        if(!is_uploaded_file($_FILES['file']['tmp_name'])){
            // 如果不是通过HTTP POST上传的
            return new JsonResponse(['code' => '400', 'msg' => '不是通过post上传的']);
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
            return $this->generateResponseData(APIResponseCode::CODE_SUCCESS);
        }else{
            return new JsonResponse(['code' => '400', 'msg' => '请重新上传']);
        }
    }
}
