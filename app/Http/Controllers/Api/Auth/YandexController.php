<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class YandexController extends Controller
{
    public function callbackToYandex()
    {
        try {
            if (!empty($_GET['code'])) {
                // Отправляем код для получения токена (POST-запрос).
                $params = array(
                    'grant_type' => 'authorization_code',
                    'code' => $_GET['code'],
                    'client_id' => config('services.yandex.client_id'),
                    'client_secret' => config('services.yandex.client_secret'),
                );

                $ch = curl_init('https://oauth.yandex.ru/token');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $data = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($data, true);
                if (!empty($data['access_token'])) {
                    // Токен получили, получаем данные пользователя.
                    $ch = curl_init('https://login.yandex.ru/info');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, array('format' => 'json'));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $data['access_token']));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    $info = curl_exec($ch);
                    curl_close($ch);

                    $info = json_decode($info, true);
                    $findUser = User::where('yauth_id', $info['id'])->first();

                    if ($findUser) {

                        Auth::login($findUser);

                        return new JsonResponse(auth()->tokenById($findUser->id));

                    } else {
                        $newUser = User::create([
                            'name' => $info['login'],
                            'email' => $info['default_email'],
                            'yauth_id' => $info['id'],
                            'yauth_type' => 'yandex',
                            'password' => encrypt('admin@123')
                        ]);

                        Auth::login($newUser);

                        return new JsonResponse(auth()->tokenById($newUser->id));
                    }
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
