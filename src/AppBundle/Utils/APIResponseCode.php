<?php
namespace AppBundle\Utils;

class APIResponseCode
{
    const CODE_SUCCESS = 200;
    const CODE_ALREADY_EXIST = 200.01;
    const CODE_OUT_OF_RANGE = 200.02;
    const CODE_BAD_REQUEST = 400;
    const CODE_AUTH_INFO_INVALID = 400.88;
    const CODE_NEED_UNAUTHORIZED = 401;
    const CODE_NEED_FORBIDDEN = 403;
    const CODE_NOT_FOUND = 404;
    const CODE_INTERNAL_SERVER_ERROR = 500;
}
