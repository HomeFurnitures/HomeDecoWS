<?php

namespace App\Http\Controllers;

use Auth;
use Config;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;

class ImageController extends Controller
{
	protected $authService;
	/**
     * Initialize auth service.
     */
    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function saveImage(Request $request) {

		if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);

        $image = base64_decode($data['Image']);
		$image_name= $data['ImageName'];
		$path = "/xampp/htdocs/images/" . $image_name;

		file_put_contents($path, $image);

		$response = [Config::get('enum.message') => Config::get('enum.successImage')];
        return (new Response($response, 202))->header('Content-Type', 'json');    
    }
}
