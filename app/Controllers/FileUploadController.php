<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Models\Document;
use App\Service\FileUpload\FileUploader;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FileUploadController extends BaseController
{
    public function Upload(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $output = array();
        $message_suffix = "";
        $user_id = $params['user_id'];
        $title = $params['title'];
        $description = $params['description'];
        $cover = $params['cover'];
        $category_id = $params['category_id'];
        $document_type = $params['documentType'];
        $doc_type_name = $params['doc_type_name'];
        $author_name = $params['author_name'];
        $author_link = $params['author_link'];
        $author_desc = $params['author_desc'];
        $link = "";
        $price = $params['price'];
        $num_pages = $params['num_pages'];
        $listen_time = $params['listen_time'];
        $read_time = $params['read_time'];
        $tag = $params['tag'];
        $is_published = $params['is_published'];
        $is_downloadable = $params['is_downloadable'] ?? 0;
        if (empty($is_published)) {
            $is_published = 0;
        }
        if (empty($is_downloadable)) {
            $is_downloadable = 0;
        }
        if (empty($num_pages)) {
            $num_pages = 0;
        }
        if (empty($listen_time)) {
            $listen_time = 0;
        }
        if (empty($read_time)) {
            $read_time = 0;
        }
        $note = $params['note'];
        $date_created = date('Y-m-d H:i:s');
        $qcode = Document::generateCode();

        if (empty($user_id)) {
            $output['error'] = true;
            $output['message'] = 'Invalid session. Login and try again.';

            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($doc_type_name)) {
            $output['error'] = true;
            $output['message'] = 'Invalid document type selected. Please try again. ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($title)) {
            $output['error'] = true;
            $output['message'] = 'Please enter a title for this ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($category_id) || $category_id <= 0) {
            $output['error'] = true;
            $output['message'] = 'You must select a category for this ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($description)) {
            $output['error'] = true;
            $output['message'] = 'You must enter a detailed description about this ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (strlen($description) < 30) {
            $output['error'] = true;
            $output['message'] = 'Too short description. Add more detail about this ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($author_name)) {
            $output['error'] = true;
            $output['message'] = 'You must enter the author name for this ' . $doc_type_name . '.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        $file_size = 0;
        $uploadFileName = "";
        $ext = "";
        $files = $request->getUploadedFiles();
        if ($is_published == 1) {
            if (empty($files['doc_link']) || empty($files['cover_image'])) {
                $output['error'] = true;
                $output['message'] = 'You must upload a cover photo and file to publish this ' . $doc_type_name . '. Uncheck publish option to save as draft.';
                exit;
            }
        }


        /******************* ########## 4. DOCUMENT TO DATABASE ######### **********************/
        $res = Document::insert($user_id, $title, $description, $qcode, $cover, $category_id, $document_type, $author_name, $author_link, $author_desc, $ext, $uploadFileName, $price, $num_pages, $listen_time, $read_time, $tag, $is_published, $is_downloadable, $note, $date_created);
        if ($res["code"] == Constants::INSERT_SUCCESS) {
            $output['error'] = false;
            $output['id'] = $res["id"];
            $output['qcode'] = $qcode;
            $output['message'] = 'Your ' . $doc_type_name . ' has been saved successfully.' . $message_suffix;

            /********* START OF KEYWORD SAVE **********/
            try {
                $keyword = $params['keyword'];
                if (!empty($keyword)) {
                    $myArray = explode(',', $keyword);
                    foreach ($myArray as $my_Array) {
                        Document::createDocKeyword($res["id"], $my_Array);
                    }
                }
                $jsonData = json_encode($output);
                $response = $response->withHeader('Content-Type', 'application/json');
                return $response->getBody()->write($jsonData);
            } catch (Exception $e) {
            }
            /********* END OF KEYWORD SAVE **********/

        } else {
            $output["error"] = true;
            $output["message"] = "Failed to create " . $doc_type_name . ". Please try again.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
        $jsonData = json_encode($output);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->getBody()->write($jsonData);
    }

    public function upload_media(Request $request, Response $response, $args)
    {
        $qcode = $request->getParsedBody()['doc_id'];
        $doc = Document::where('qcode', $qcode)->first();
        if (!$doc) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Looks like the document you are updating is not available.'
            ], 404);
        }
        $doc_type_name = Document::getDocTypeName($doc->documentType);
        $files = $request->getUploadedFiles();
        $res = FileUploader::uploadFile($files, 'cover_image', 'uploads/images/docs/', ['jpg', 'png', 'jpeg'], 1000000);
        if ($res['code'] == Constants::INSERT_FAILURE) {
            $this->jsonResponse($response, $res, 400);
        }
        $uploadCoverName = $doc->cover;
        if (!$uploadCoverName) {
            try {
                $fileToTest = "uploads/images/docs/$uploadCoverName";
                if (file_exists($fileToTest)) {
                    unlink($fileToTest);
                }
            } catch (Exception $e) {
                return $this->jsonResponse($response, ['message' => 'Exception deleting old file: ' . $e->getMessage() . '.', 'error' => true], 400);
            }
        }
        try {
            if ($res['code'] !== Constants::UPLOAD_IS_MISSING) {
                $res = Document::updateCover($doc->id, $res['fileName']);
                if (!$res) {
                    return $this->jsonResponse($response, ['error' => true, "message" => "Failed to upload cover. Please try again."], 400);
                }
                return $this->jsonResponse($response, ["message" => "Cover has been uploaded successfully. ",
                    "error" => false, 'id' => $doc->id], 200);
            }
        } catch (Exception $e) {
            return $this->jsonResponse($response, [
                "error" => true,
                "message" => "Failed to upload cover image for " . $doc_type_name . ". Please try again.",
                "exception" => $e->getMessage()
            ], 400);
        }

        /********* END OF COVER UPLOAD **********/
    }

    public function upload_files(Request $request, Response $response, $args)
    {
        $qcode = $request->getParam('doc_id');
        $doc = Document::where('qcode', $qcode)->first();
        $num_pages = $request->getParam('num_pages')??0;
        if (!$doc) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Looks like the document you are updating is not available. =>' . $qcode
            ], 404);
        }
        $doc_type_name = Document::getDocTypeName($doc->id);
        $files = $request->getUploadedFiles();
        $max_size = 50 * 1024 * 1024;
        $result = FileUploader::uploadFile($files, 'doc_link', "uploads/documents/", ['mpeg', 'mp4', 'pdf'], $max_size);
        if ($result['code'] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response, $result, 400);
        }
        /*********##############  3. START OF FILE UPLOAD ############# **********/
        /*********** If doc already exists delete it first ****************/
        $existingImage = $doc->link;
        if ($existingImage) {
            $toDelete = "uploads/documents/" . $existingImage;
            try {
                if (file_exists($toDelete)) {
                    unlink($toDelete);
                }
            } catch (Exception $e) {
                return $this->jsonResponse($response, ['error' => true, 'message' => "Existing file can not be deleted. " . $toDelete], 400);
            }
        }


        if ($result['code'] !== Constants::UPLOAD_IS_MISSING) {
            try {
                if (Document::updateFileLink($doc->id, $result['fileName'])) {
                    Document::updateFileInfo($doc->id, $result['file_type'], $num_pages, $result['file_size']);
                    return $this->jsonResponse($response, [
                        "error" => false,
                        "message" => "File uploaded successfully",
                        "data" => $result
                    ], 200);
                } else {
                    return $this->jsonResponse($response, ['error' => true, 'message' => "Failed to upload attachment."], 400);
                }
            } catch (Exception $e) {
                return $this->jsonResponse($response, ['error' => true, 'message' => "Failed to upload attachment with Exception - " . $e->getMessage()], 400);
            }
        } else {
            $this->logger->info($result['message']);
            return $this->jsonResponse($response, $result, 400);
        }
        /********* END OF FILE UPLOAD **********/
    }

    public function serve_file(Request $request, Response $response,$args) {
        $params = $args['params'] ?? null;

        // Define the uploads base directory
        $rootFolder = dirname(ROOT_FOLDER);
        $uploadsPath = $rootFolder . '/uploads';

        // Resolve the requested file path
        $filePath = realpath($uploadsPath . DIRECTORY_SEPARATOR . $params);

        // Security check: Ensure the file is within the uploads directory
        if (!$filePath || strpos($filePath, realpath($uploadsPath)) !== 0) {
            return $response->withStatus(404)->withHeader('Content-Type', 'text/plain')->write('File not found.');
        }

        // Check if the file exists
        if (!is_file($filePath)) {
            return $response->withStatus(404)->withHeader('Content-Type', 'text/plain')->write('File not found.');
        }

        // Serve the file with the correct MIME type
        $mimeType = mime_content_type($filePath);
        $response = $response->withHeader('Content-Type', $mimeType);
        $response->getBody()->write(file_get_contents($filePath));

        return $response;
    }

}