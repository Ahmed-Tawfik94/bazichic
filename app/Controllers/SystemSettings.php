<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Models\SiteSetting;
use App\Service\FileUpload\FileUploader;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Stream;

class SystemSettings extends BaseController
{
    protected $model;
    public function __construct(ContainerInterface $container )
    {
        parent::__construct($container);
        $this->model = new SiteSetting();
    }
    public function index(Request $request, Response $response, $args)
    {
        $id = 1;
        $settings = $this->model->getID($id);
        $vars = [
            'page' => [
                'title' => 'Manage Site Configuration',
                'description' => 'Update your Site Settings',
                'settings' => $settings,
                'name'=>'settings'
            ]
        ];
        return $this->view->render($response, 'admin/site_settings.twig', $vars);
    }
    public function Update(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $files = $request->getUploadedFiles();
        $name = $params['website_name'];
        $email = $params['website_email'];
        $phone = $params['phone'];
        $site_email = $params['site_email'];
        $maintenance_on = 0;
        if (isset($params['website_maintenance'])) {
            $maintenance_on = $params['website_maintenance'];
        }


        try {
                if ($name || $email || $maintenance_on) {
                    $result = $this->model->updateSettings((object)[
                        'name' => $name,
                        'maintenance_on' => $maintenance_on,
                        'admin_email' => $email,
                        'site_email'=>$site_email,
                        'phone'=>$phone], 1);
                }
            if ($result["code"] == Constants::INSERT_FAILURE) {
                return $this->jsonResponse($response, ['error' => true,
                    'message' => "Failed to upload banner. Please try again." . $result["message"], 'id' => 1], 400);
            }
            if($files['logo_image']->getError() !== UPLOAD_ERR_NO_FILE){
                FileUploader::uploadLogo('logo_image');
            }
            if($files['cover_image']->getError() !== UPLOAD_ERR_NO_FILE){
                $res = FileUploader::uploadFile($files, 'cover_image', Constants::BANNER_FOLDER, Constants::IMAGES_EXT, 2000000);
                if ($res['code'] == Constants::INSERT_FAILURE) {
                    return $this->jsonResponse($response, $res, 400);
                }
                if ($res['code'] === Constants::UPLOAD_IS_MISSING) {
                    return $this->jsonResponse($response, ['error' => true, 'message' =>$res["message"] ], 400);
                }
                $result =$this->model->updateBannerLink(1, $res['fileName']);

                if ($result["code"] == Constants::INSERT_FAILURE) {
                    return $this->jsonResponse($response, ['error' => true,
                        'message' => "Failed to upload banner. Please try again." . $result["message"], 'id' => 1], 400);
                }

                return $this->jsonResponse($response, ['error' => false, 'message' => 'Banner has been updated successfully.'], 200);
            }
            return $this->jsonResponse($response, ['error' => false, 'message' => 'Setting has been updated successfully.'], 200);

        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => true, 'message' => $e->getMessage()], 400);
        }
    }
    public function UploadBanner(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        $res = FileUploader::uploadFile($files, 'cover_image', Constants::BANNER_FOLDER, Constants::IMAGES_EXT, 2000000);
        if ($res['code'] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response, $res, 400);
        }
        try {
            if ($res['code'] === Constants::UPLOAD_IS_MISSING) {
                return $this->jsonResponse($response, ['error' => true, 'message' =>$res["message"] ], 400);
            }
            $result =$this->model->updateBannerLink(1, $res['fileName']);

            if ($result["code"] == Constants::INSERT_FAILURE) {
                return $this->jsonResponse($response, ['error' => true,
                    'message' => "Failed to upload banner. Please try again." . $result["message"], 'id' => 1], 400);
            }
            return $this->jsonResponse($response, ['error' => false, 'message' => 'Banner has been updated successfully.'], 200);
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    public function GetBanner(Request $request,Response $response){
        try {
            $getBanner = $this->model->getFrontBannerLink();
            return $this->getImage($response,$getBanner);
        }catch (Exception $e){
            return $this->jsonResponse($response,["message"=>"Failed to load the banner Link"],404);

        }
    }
     public function getImage($response ,$fileKey)
     {
         if (!file_exists($fileKey)){
             return $response->withStatus(404)->write('File not found');
         }
         try {
         // Check if the file exists
             // Assuming $fileResource is the file resource (e.g., fopen or similar)
             $fileResource = fopen($fileKey, 'r');

            // Create a Stream from the file resource
             $stream = new Stream($fileResource);

            // Set the stream as the response body
             $response = $response->withBody($stream);
             // Send the file content
             return $response->withHeader('Content-Type', 'image/jpeg');
         }catch (Exception $e){
             fclose($fileResource);
            return  $response->withHeader('Content-Type','Application/json')->withBody($e->getMessage());
         }


     }

}