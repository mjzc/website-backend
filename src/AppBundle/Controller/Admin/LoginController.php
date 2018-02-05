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
        $imgSrc = 'http://aoyi.zeroyc.me/blog-admin/'.$this->getParameter('upload_path').'/'.$res->getBlogHeadImg();
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
        $type = ['image/png','image/jpge','image/jpg'];
        $size = 2 * 1024 *1024;
        $imgType = $_FILES['file']['type'];
        $imgSize = $_FILES['file']['size'];
        $isMove = false;
        $upload_path = $this->getParameter('upload_path');
        if (!in_array($imgType,$type)) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
        if ($imgSize > $size) {
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
        // 判断是否是通过HTTP POST上传的
        if(!is_uploaded_file($_FILES['file']['tmp_name'])){
            // 如果不是通过HTTP POST上传的
            return ;
        }
        // 判断存图片的文件夹是否存在
        if(!file_exists($upload_path)) {
            mkdir ($upload_path,0777,true);
        }
        // 判断图片是否存在
        if (!file_exists($upload_path.$_FILES['file']['name'])) {
            $isMove = move_uploaded_file($_FILES["file"]["tmp_name"],$upload_path.'/'.$_FILES['file']['name']);
        }
        // 判断文件是否保存
        if($isMove) {
            return $this->generateResponseData(APIResponseCode::CODE_SUCCESS);
        }else{
            return $this->generateResponseData(APIResponseCode::CODE_AUTH_INFO_INVALID);
        }
    }
    /**
     * 获取地址
     * @Route("/web_getCityAPIg")
     * @Method("GET")
     */
    public function getAddress ()
    {

//        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
//            var_dump(1);
//            $ip = getenv('HTTP_CLIENT_IP');
//        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
//            var_dump(2);
//            $ip = getenv('HTTP_X_FORWARDED_FOR');
//        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
//            var_dump(3);
//            $ip = getenv('REMOTE_ADDR');
//        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
//            var_dump(4);
//            $ip = $_SERVER['REMOTE_ADDR'];
//        }
//        $cip =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';

        $ak = 'SQ5dEOZP2tTj4apHNGQ4IsgoG2Y7qv54';
        $url = 'http://api.map.baidu.com/location/ip?ak=' .$ak. '&coor=bd09ll';
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        return new JsonResponse($json_data);

    }
}
