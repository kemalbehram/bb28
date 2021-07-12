<?php
namespace App\Packages\Utils;

use App\Models\Option;
use function App\Models\option;

class SMS
{
    public static function check($prefix, $mobile, $ver_code)
    {
        $un_check_code = option('sms_un_check_code');
        if ($ver_code == $un_check_code) {
            return true;
        }
        $key = $prefix . ':' . $mobile;

        $temp = cache()->get($key);
        if (!$temp || $ver_code != $temp['ver_code']) {
            return false;
        }
        cache()->forget($key);

        return true;
    }

    public static function send($prefix, $mobile, $code = null)
    {
        if ($code == null) {
            $code = mt_rand(100000, 999999);
        }

        $key  = $prefix . ':' . $mobile;
        $data = ['ver_code' => $code];
        cache()->put($key, $data, 60000);

        // $params = ['code' => $code];
        // $sms = self::AliSMS($mobile, $params);

        $sms = self::smsBao($mobile, $code);

        return ['success' => $sms];
    }

    private static function AliSMS($phone, $temp_param = null)
    {
        $temp_code = env('ALI_SMS_TEMP_CODE');
        $app_key = env('ALI_SMS_ACCESS_KEY_ID');
        $app_secret = env('ALI_SMS_ACCESS_KEY_SECRET');
        $sign_name = env('ALI_SMS_SIGN_NAME');

        \AlibabaCloud\Client\AlibabaCloud::accessKeyClient($app_key, $app_secret)
            ->regionId('cn-hangzhou')
            ->asGlobalClient();

        try {
            $temp_param = json_encode($temp_param, JSON_UNESCAPED_UNICODE);
            \AlibabaCloud\Client\AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $phone,
                        'SignName' => $sign_name,
                        'TemplateCode' => $temp_code,
                        'TemplateParam' => $temp_param,
                    ],
                ])
                ->request();
            return true;
        } catch (\AlibabaCloud\Client\Exception\ClientException $error) {
            return false;
        } catch (\AlibabaCloud\Client\Exception\ServerException $error) {
            return false;
        }

        return false;
    }

    private static function smsBao($mobile, $code)
    {
        $url = 'https://api.smsbao.com/sms';
        $content = '【' . env('SMS_BAO_SIGN_NAME') . '】您的验证码为' . $code . '，在10分钟内有效';
        $params = [
            'u' => 'gd8888',
            'p' => md5('aa101088'),
            'm' => $mobile,
            'c' => $content,
        ];

        $client = new \GuzzleHttp\Client(['timeout' => 10.0]);
        $response = $client->get($url, ['query' => $params]);
        $result = json_decode($response->getBody(), true);

        return $result === 0 ? true : false;
    }
}
