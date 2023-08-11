<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GithubController extends Controller
{
    public function callbackToGithub()
    {
        try {
            if (!empty($_GET['code'])) {
                // Отправляем код для получения токена (POST-запрос).
                $params = array(
                    'redirect_uri' => config('services.github.redirect'),
                    'code' => $_GET['code'],
                    'client_id' => config('services.github.client_id'),
                    'client_secret' => config('services.github.client_secret'),
                );

                $ch = curl_init('https://github.com/login/oauth/access_token');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $data = curl_exec($ch);
                curl_close($ch);
                parse_str($data, $data);

                if (!empty($data['access_token'])) {
                    $ch = curl_init('https://api.github.com/user');
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token ' . $data['access_token']));
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $info = curl_exec($ch);
                    curl_close($ch);
                    $info = json_decode($info, true);

                    $findUser = User::where('ghauth_id', $info['id'])->first();

                    if ($findUser) {

                        Auth::login($findUser);

                        return new JsonResponse(auth()->tokenById($findUser->id));

                    } else {
                        $newUser = User::create([
                            'name' => $info['login'],
                            'email' => $info['email'] ?? $info['login'],
                            'ghauth_id' => $info['id'],
                            'ghauth_type' => 'github',
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
