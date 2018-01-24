<?php
namespace AppBundle\Utils;

use AppBundle\Utils\APIResponseCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIResponseGenerator extends Controller
{
    private $responseList = [
        'response_200' => ['code' => APIResponseCode::CODE_SUCCESS, 'msg' => '成功'],
        'response_200.01' => ['code' => APIResponseCode::CODE_ALREADY_EXIST, 'msg' => '资源已存在'],
        'response_200.02' => ['code' => APIResponseCode::CODE_OUT_OF_RANGE, 'msg' => '超过可用次数'],
        'response_400' => ['code' => APIResponseCode::CODE_BAD_REQUEST, 'msg' => '请求缺失参数'],
        'response_400.88' => ['code' => APIResponseCode::CODE_AUTH_INFO_INVALID, 'msg' => '用户名或密码错误'],
        'response_401' => ['code' => APIResponseCode::CODE_NEED_UNAUTHORIZED, 'msg' => '需要授权'],
        'response_403' => ['code' => APIResponseCode::CODE_NEED_FORBIDDEN, 'msg' => '无权访问'],
        'response_404' => ['code' => APIResponseCode::CODE_NOT_FOUND, 'msg' => '不存在的资源'],
        'response_500' => ['code' => APIResponseCode::CODE_INTERNAL_SERVER_ERROR, 'msg' => '服务器错误']
    ];


    /**
     * @param $data
     * @param  $code
     * @return JsonResponse
     */
    public function generateResponseData ($code, $data = []) {

        $defaultData = [];

        $responseType = 'response_' . $code;
        if (isset($this->responseList[$responseType])) {
            $defaultData = $this->responseList[$responseType];
            $defaultData['code'] = $defaultData['code'] * 100;
        }
        $responseData = array_merge($defaultData, $data);

        return new JsonResponse($responseData);
    }

}