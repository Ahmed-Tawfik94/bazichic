<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Models\Activity;
use App\Models\Category;
use App\Models\DocKeywords;
use App\Models\Document;
use App\Models\DocumentAudio;
use App\Models\DocumentLike;
use App\Models\DocumentReview;
use App\Models\DocumentSave;
use App\Models\DocumentType;
use App\Models\Notification;
use App\Models\StoreTag;
use App\Models\User;
use App\Models\Util;
use App\Service\FileUpload\FileUploader;
use Carbon\Carbon;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocumentController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {

        $vars = [
            'page' => [
                'title' => 'Select Document Type',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ],
        ];
        return $this->view->render($response, 'admin/select-document-type.twig', $vars);
    }

    public function documents(Request $request, Response $response, $args)
    {
        $data = Document::getAllDocuments(0);
        $now = Carbon::now();
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["title"] = $row["title"];
                $tmp["cover"] = $row["cover"];
                $tmp["qcode"] = $row["qcode"];
                $tmp["is_downloadable"] = $row["is_downloadable"];
                $tmp["is_published"] = $row["is_published"];
                //$tmp["num_pages"] = $row["num_pages"];
                // $tmp["author_name"] = $row["author_name"];
                $tmp["category_id"] = $row["category_id"];
                $tmp["category"] = Category::getNameByID($row["category_id"]);
                $tmp["documentType"] = $row["documentType"];
                //$tmp["date_created"] = $utilCRUD->getTimeDifference($row["date_created"]);

                $tmp["avg_rating"] = DocumentReview::getAvgReviewsFor($row["id"]);
                $tmp["num_reviews"] = DocumentReview::getNumReviewsFor($row["id"]);
                $tmp["num_likes"] = DocumentLike::getNumLikes($row["id"]);
                $tmp["num_saves"] = DocumentSave::getNumSaves($row["id"]);

                $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                $tmp["file_type"] = $row["file_type"];
                // $tmp["num_pages"] = $row["num_pages"];
                $tmp["note"] = $row["note"];
                $tmp["date_updated"] = "";
                if (!empty($row["date_updated"])) {
                    $tmp["date_updated"] = Carbon::parse(Util::getFormalDate($row["date_updated"]))->diffForHumans($now) ;
                }

                $custom_data[] = $tmp;
            }
        }

        $vars = [
            'page' => [
                'title' => 'Manage Book Store',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'data' => $custom_data,
                'name'=>'manage-documents'
            ],
        ];
        return $this->view->render($response, 'admin/books-manager.twig', $vars);
    }

    public function add(Request $request, Response $response, $args)
    {
        $doc_type = "";
        $doc_type_id = 0;
        $doc_type_selected = $request->getAttribute('doc_type');
        if (empty($doc_type_selected)) {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('select-document-type'));
            return $response->withRedirect((string)$uri);
        }
        switch ($doc_type_selected) {
            case "ebook":
                $doc_type = "E-Book";
                $doc_type_id = 1;
                break;

            case "audiobook":
                $doc_type = "Audio Book";
                $doc_type_id = 2;
                break;

            case "magazine":
                $doc_type = "Magazine";
                $doc_type_id = 3;
                break;
        }
        if ($doc_type_id <= 0) {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('select-document-type'));
            return $response->withRedirect((string)$uri);
        }

        $title = 'Add New ' . $doc_type;
        $list_categories = Category::getActiveEbookCategories();
        if ($doc_type_id == 3) {
            $list_categories = Category::getAllMagazineCategories();
        }
        $list_ribbon_tags = StoreTag::getAllRibbonTags();
        $vars = [
            'page' => [
                'title' => $title,
                'doc_type' => $doc_type,
                'doc_type_id' => $doc_type_id,
                'list_categories' => $list_categories,
                'list_ribbon_tags' => $list_ribbon_tags,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ],
        ];
        return $this->view->render($response, 'admin/book-add.twig', $vars);
    }

    public function get(Request $request, Response $response, $args)
    {

        $doc_type = "";
        $doc_type_selected = 0;
        $qcode = $request->getAttribute('qcode');
        $documentID = Document::getIDByQCode($qcode);

        if (!Document::isQCodeExists($qcode)) {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('unauthorized'));
            return $response->withRedirect((string)$uri);
        }
        $accessorRole = 0;
        $thisUser = User::getUserByAPIKey($_SESSION["api_key"]);
        if ($thisUser != null) {
            $accessorRole = $thisUser["role_id"];
        } else {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('404'));
            return $response->withRedirect((string)$uri);
        }
        $owner_id = Document::getOwnerID($documentID);
        $document = Document::getID($documentID);
        if ($accessorRole != 1) {
            if ($owner_id != $_SESSION["userID"]) {
                $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('unauthorized'));
                return $response->withRedirect((string)$uri);
            }
        }

        $doc_type_selected = $document["documentType"];
        $keyword_list = "";
        $existingTags = DocKeywords::getAllTags($documentID);
        $counters = 0;
        if ($existingTags) {
            foreach ($existingTags as $tag_val) {
                $counters++;
                if ($counters == 1) {
                    $keyword_list .= trim($tag_val['keyword']);
                } else {
                    $keyword_list .= ", " . trim($tag_val['keyword']);
                }
            }
        }

        $warning = "";
        switch ($doc_type_selected) {
            case 1:
                $doc_type = "E-Book";
                break;

            case 2:
                $doc_type = "Audio Book";
                break;

            case 3:
                $doc_type = "Magazine";
                break;
        }

        if ($document["is_published"] <= 0) {
            $warning = 'This ' . $doc_type . ' is saved as draft. ';
            if (empty($document["cover"]) && empty($document["link"])) {
                $warning .= ' Upload a cover image and file.';
            } else {
                if (empty($document["cover"])) {
                    $warning .= ' Upload a cover image.';
                }
                if (empty($document["link"])) {
                    $warning .= ' Upload a file.';
                }
            }
            $warning .= ' Check Publish this document option below to make it available to users.';
        }
        $title = 'Edit ' . $doc_type;
        $list_categories = Category::getActiveEbookCategories();
        if ($doc_type_selected == 3) {
            $list_categories = Category::getAllMagazineCategories();
        }
        $list_ribbon_tags = StoreTag::getAllRibbonTags();
        $vars = [
            'page' => [
                'title' => $title,
                'doc_type' => $doc_type,
                'doc_type_id' => $doc_type_selected,
                'list_categories' => $list_categories,
                'list_ribbon_tags' => $list_ribbon_tags,
                'description' => 'Edit ' . $doc_type,
                'doc_id' => $documentID,
                'document' => $document,
                'warning' => $warning,
                'editMode' => true
            ],
            'document' => [
                'title' => $document["title"],
                'description' => $document["description"],
                'category_id' => $document["category_id"],
                'cover' => $document["cover"],
                'qcode' => $document["qcode"],
                'category' => Category::getNameByID($document["category_id"]),
                'keywords' => $keyword_list,
                'price' => $document["price"],
                'read_time' => $document["read_time"],
                'listen_time' => $document["listen_time"],
                'documentType' => $document["documentType"],
                'user_id' => $document["user_id"],
                'author_name' => $document["author_name"],
                'author_link' => $document["author_link"],
                'link' => $document["link"],
                'tag' => $document["tag"],
                'author_desc' => $document["author_desc"],
                'is_downloadable' => $document["is_downloadable"],
                'is_published' => $document["is_published"],
                'date_created' => Util::getTimeDifference($document["date_created"]),
                'num_pages' => $document["num_pages"],
                'audio_file'=> $document->audio->file ?? null,
            ],
        ];
        return $this->view->render($response, 'admin/book-edit.twig', $vars);
    }

    public function Update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $doc_id = $data["doc_id"];
        $data["documentType"] = Document::find($doc_id)->value('documentType');
        $doc = Document::edit($doc_id, [
            'title' => $data["title"],
            'description' => $data["description"],
            'category_id' => $data["category_id"],
            'documentType' => $data["documentType"],
            'author_name' => $data["author_name"],
            'author_link' => $data["author_link"],
            'author_desc' => $data["author_desc"],
            'price' => $data["price"],
            'num_pages' => $data["num_pages"],
            'listen_time' => $data["listen_time"],
            'read_time' => $data["read_time"],
            'tag' => $data["tag"],
            'is_published' => $data["is_published"] ?? 0,
            'is_downloadable' => $data["is_downloadable"] ?? 0,
            'note' => $data["note"],
        ]);
        if (!$doc) {
            return $this->jsonResponse($response, ['message' => "Something went wrong.", 'error' => true], 400);
        }
        return $this->jsonResponse($response, ['data' => $doc, 'message'=>'Document updated successfully', 'error' => false], 200);
    }

    public function Upload(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $output = array();
        $message_suffix = "";
        $user_id = $params['user_id'];
        $title = $params['title'];
        $description = $params['description'];
        $cover = $params['cover'] ?? '';
        $category_id = $params['category_id'];
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
        $documentType = DocumentType::where('title', 'like', "%{$doc_type_name}%")->first();
        $documentType_id = $documentType ? $documentType->id : null;
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
            return $this->jsonResponse($response,$output,400);
        }

        if (empty($title)) {
            $output['error'] = true;
            $output['message'] = 'Please enter a title for this ' . $doc_type_name . '.';
             return $this->jsonResponse($response,$output,400);
        }

        if (empty($category_id) || $category_id <= 0) {
            $output['error'] = true;
            $output['message'] = 'You must select a category for this ' . $doc_type_name . '.';
            return $this->jsonResponse($response,$output,400);
        }

        if (empty($description)) {
            $output['error'] = true;
            $output['message'] = 'You must enter a detailed description about this ' . $doc_type_name . '.';
            return $this->jsonResponse($response,$output,400);
        }

        if (strlen($description) < 30) {
            $output['error'] = true;
            $output['message'] = 'Too short description. Add more detail about this ' . $doc_type_name . '.';
            return $this->jsonResponse($response,$output,400);
        }

        if (empty($author_name)) {
            $output['error'] = true;
            $output['message'] = 'You must enter the author name for this ' . $doc_type_name . '.';
            return $this->jsonResponse($response,$output,400);
        }

        /******************* ########## 4. DOCUMENT TO DATABASE ######### **********************/
        $res = Document::insert((object)[
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'qcode' => $qcode,
            'cover' => $cover ?? '',
            'category_id' => $category_id,
            'document_type' => $documentType_id,
            'author_name' => $author_name,
            'author_link' =>$author_link,
            'author_desc' =>$author_desc,
            'file_type' =>$file_type ?? null,
            'link' => $link,
            'price' =>$price,
            'num_pages' => $num_pages,
            'listen_time' =>$listen_time,
            'read_time' =>$read_time,
            'tag' => $tag,
            'is_published' =>$is_published,
            'is_downloadable' => $is_downloadable,
            'note' =>$note,
        ]);
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
                return $this->jsonResponse($response,$output,400);
            } catch (Exception $e) {
            }
            /********* END OF KEYWORD SAVE **********/

        } else {
            $output["error"] = true;
            $output["message"] = "Failed to create " . $doc_type_name . ". Please try again.";
            return $this->jsonResponse($response,$output,400);
        }
        return $this->jsonResponse($response,$output,);
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
            return $this->jsonResponse($response, $res, 400);
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
    public function upload_audio(Request $request, Response $response, $args)
    {
        $qcode = $args['qcode'];
        $doc = Document::where('qcode', $qcode)->first();
        if (!$doc) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Looks like the file you are updating is not available.'
            ], 404);
        }
        $doc_type_name = Document::getDocTypeName($doc->documentType);
        $files = $request->getUploadedFiles();
        $res = FileUploader::uploadFile($files, 'audio_file', 'uploads/documents/audio/', Constants::AUDIO_EXT, 500000000);
        if ($res['code'] == Constants::INSERT_FAILURE) {
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400)
                ->write(json_encode(['message' => (string)$res['message']]));
        }
        $uploadCoverName = $doc->audio ? $doc->audio->file : '';
        if (!empty($uploadCoverName)) {
            try {
                $fileToTest = "uploads/documents/audio/$uploadCoverName";
                if (file_exists($fileToTest)) {
                    unlink($fileToTest);
                }
            } catch (Exception $e) {
                return $this->jsonResponse($response, ['message' => 'Exception deleting old file: ' . $e->getMessage() . '.', 'error' => true], 400);
            }
        }
        try {
            if ($res['code'] !== Constants::UPLOAD_IS_MISSING) {
                $doesExist = DocumentAudio::where('document_id',$doc->id)->first();
                if($doesExist){

                    $res = DocumentAudio::update_audio($doc->id, $res['fileName']);
                    if ($res['code'] === Constants::INSERT_FAILURE) {
                        return $this->jsonResponse($response, ['error' => true, "message" => $res['message']], 400);
                    }
                }else{
                    $addAudio = DocumentAudio::upsert((object)
                      [
                          'document_id'=>$doc->id,
                          'title'=>$res['fileName'],
                          'file'=>$res['fileName'],
                      ]
                    );
                    if ($addAudio['code'] === Constants::INSERT_FAILURE){
                        return $this->jsonResponse($response, ['error' => true, "message" => $res['message']], 400);
                    }
                }
                return $this->jsonResponse($response, ["message" => "Audio file has been uploaded successfully. ",
                    "error" => false, 'id' => $doc->id,'redirection_url'=>$this->router->pathFor('manage-documents')], 200);
            }
        } catch (Exception $e) {
            return $this->jsonResponse($response, [
                "error" => true,
                "message" => "Failed to upload audio file for " . $doc_type_name . ". Please try again.",
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

    public function delete(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = true;
        $doc_id = $request->getParam('id');
        $id = Document::getIDByQCode($doc_id);

        if (!Document::isQCodeExists($doc_id)) {
            $output['error'] = true;
            $output['message'] = 'The document you are trying to delete is not available at this moment.';
            return $this->jsonResponse($response,$output,400);
        }

        //Delete Cover Image
        $uploadCoverName = Document::getDocCover($doc_id);
        if (!empty($uploadCoverName)) {
            try {
                $fileToTest = "uploads/images/docs/$uploadCoverName";
                if (file_exists($fileToTest)) {
                    unlink($fileToTest);
                }
            } catch (Exception $e) {
                $this->container->get('logger')($e->getMessage());
            }
        }

        //Delete File
        $uploadFileName = Document::getDocFileLink($doc_id);
        if (!empty($uploadFileName)) {
            try {
                $fileToTest = "uploads/documents/$uploadFileName";
                if (file_exists($fileToTest)) {
                    unlink($fileToTest);
                }
            } catch (Exception $e) {
                $this->container->get('logger')($e->getMessage());
            }
        }


        $res = Document::remove($id);
        if ($res) {
            $output["error"] = false;
            $output["message"] = "Your Document has been deleted successfully. ";
            $output["id"] = $id;
             return $this->jsonResponse($response,$output,200);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to delete document. Please try again.";
             return $this->jsonResponse($response,$output,400);
        }
    }

    public function save(Request $request, Response $response, $args)
    {

        $output = array();
        $output["error"] = false;
        $output["title"] = "";
        $user_id = $request->getParam('user_id');
        $doc_id = $request->getParam('doc_id');
        $page = $request->getParam('page');
        $progress = $request->getParam('progress');
        $date_created = date('Y-m-d H:i:s');

        if (!Document::isIDExists($doc_id)) {
            $output['error'] = true;
            $output["title"] = "Document not found";
            $output['message'] = 'The document you are trying to save is not available at this moment.';
            return $this->jsonResponse($response,$output,404);
        }

        if (empty($user_id)) {
            $output['error'] = true;
            $output['message'] = 'You must be logged in to save your reads.';
            return $this->jsonResponse($response,$output,404);
        }

        $projectName = Document::getNameByID($doc_id);
        $doc_type_selected = Document::getDocType($doc_id);
        $doc_type = "";
        switch ($doc_type_selected) {
            case 1:
                $doc_type = "E-Book";
                break;

            case 2:
                $doc_type = "Audio Book";
                break;

            case 3:
                $doc_type = "Magazine";
                break;
        }

        $whoName = User::getNameByID($user_id);
        if (empty($page)) {
            $page = 0;
        }
        if (empty($progress)) {
            $progress = 0;
        }
        $updateOperation = false;
        if (DocumentSave::isSavedBy($user_id, $doc_id)) {
            $record_id = DocumentSave::getActionRecordID($user_id, $doc_id);
            $output["id"] = $record_id;
            $res = DocumentSave::updateSave($record_id, $page, $progress, $date_created);
            $updateOperation = true;
        } else {
            $res = DocumentSave::createSave($user_id, $doc_id, $page, $progress, $date_created);
        }
        if ($res["code"] == Constants::INSERT_SUCCESS) {
            $output["error"] = false;
            if ($updateOperation) {
                $output["title"] = "Savepoint Updated";
                $output["message"] = 'Your read for ' . $doc_type . ' - ' . $projectName . ' has been saved.';
            } else {
                $output["title"] = $doc_type . " Saved";
                $output["message"] = '' . $doc_type . ' - ' . $projectName . ' saved successfully.';
                $id = $res["id"];
                $output["id"] = $id;
            }

            //Notify now	
            try {
                $title = $doc_type . ' Saved';
                $activity = "";
                if ($updateOperation) {
                    $message = 'You updated the ' . $doc_type . ' - ' . $projectName . ' savepoint.';
                    $activity = $whoName . ' resaved  the ' . $doc_type . ' - ' . $projectName . ' to library.';
                } else {
                    $message = 'You saved the ' . $doc_type . ' - ' . $projectName . ' to your library.';
                    $activity = $whoName . ' saved  the ' . $doc_type . ' - ' . $projectName . ' to library.';
                }
            } catch (Exception $e) {
                $output["message"] .= "Error sending notification.";
            }
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to create savepoint. Please try again.";
            return $this->jsonResponse($response,$output,404);
        }
        return $this->jsonResponse($response,$output,200);
    }

    public function reviews(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = false;
        $output["debug"] = "";
        $user_id = $request->getParam('user_id');
        $doc_id = $request->getParam('doc_id');
        $stars = $request->getParam('rating');
        $text = $request->getParam('text');
        $date_created = date('Y-m-d H:i:s');


        if (!Document::isIDExists($doc_id)) {
            $output['error'] = true;
            $output['message'] = 'The document you are reviewing is not available at this moment.';
            return $this->jsonResponse($response,$output,404);
        }

        if (empty($user_id)) {
            $output['error'] = true;
            $output['message'] = 'You must be logged in to submit your review.';
            return $this->jsonResponse($response,$output,403);
        }

        $projectName = Document::getNameByID($doc_id);
        $doc_type_selected = Document::getDocType($doc_id);
        $doc_type = "";
        switch ($doc_type_selected) {
            case 1:
                $doc_type = "E-Book";
                break;

            case 2:
                $doc_type = "Audio Book";
                break;

            case 3:
                $doc_type = "Magazine";
                break;
        }

        $whoName = User::getNameByID($user_id);
        if (empty($stars)) {
            $output['error'] = true;
            $output['message'] = 'Rate this ' . $doc_type . ' on the scale of 5 stars.';
            return $this->jsonResponse($response,$output,400);
        }

        if ($stars <= 0 || $stars > 5) {
            $output['error'] = true;
            $output['message'] = 'Please give a rating between 1-5 stars based on your experience.';
            return $this->jsonResponse($response,$output,400);
        }
        $updateOperation = false;
        if (DocumentReview::isReviewedBy($user_id, $doc_id)) {
            $record_id = DocumentReview::getReviewedRecordID($user_id, $doc_id);
            $output["id"] = $record_id;
            $res = DocumentReview::updateReview($record_id, $stars, $text, $date_created);
            $updateOperation = true;
        } else {
            $res = DocumentReview::addReview($doc_id, $user_id, $stars, $text, $date_created);
        }
        $ownerID = DocumentReview::where('doc_id',$doc_id)->orderBy('id','desc')->first()->user_id;
        $qcode = Document::getQCodeByID($doc_id);
        if ($res["code"] == Constants::INSERT_SUCCESS) {
            $output["error"] = false;
            if ($updateOperation) {
                $output["message"] = 'Your Review for the ' . $doc_type . ' has been updated successfully.';
            } else {
                $output["message"] = 'Your Review for this ' . $doc_type . ' has been submitted successfully.';
                $id = $res["id"];
                $output["id"] = $id;
            }

            //Notify now	
            try {
                if ($updateOperation) {
                    $title = $doc_type . ' Review Updated';
                    $message = 'You updated your rating to ' . $stars . ' stars for ' . $doc_type . ' ' . $projectName . '.';
                    $activity = $whoName . ' updated his rating to ' . $stars . ' stars for the ' . $doc_type . ' ' . $projectName . '.';
                } else {
                    $title = 'New ' . $doc_type . ' Review';
                    $message = 'You left a ' . $stars . ' stars rating for ' . $doc_type . ' ' . $projectName . '.';
                    $activity = $whoName . ' left a ' . $stars . ' stars rating for the ' . $doc_type . ' ' . $projectName . '.';
                }
                $activity_res = Activity::createActivity((object)[
                    'who_id' =>$user_id,
                    'title' => $title,
                    'message' => $activity,
                    'seen' => 0,
                    'data_id' =>1,
                    'data_title' => "Review",
                ]);
            } catch (Exception $e) {
                $output["debug"] .= "Error sending notification.";
            }
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to post a review. Please try again." . $res["message"];
            return $this->jsonResponse($response,$output,400);
        }
        return $this->jsonResponse($response,$output,200);
    }

    public function delete_reviews(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = true;
        $id = $request->getParam('review_id');
        $review = DocumentReview::getID($id);
        $owner_id = $review["user_id"];
        if ($_SESSION["role_id"] !== 1) {
            if ($owner_id != $_SESSION["userID"]) {
                $output["error"] = true;
                $output["message"] = "You are not authorized to perform this action. ";
                $output["id"] = $id;
                return $this->jsonResponse($response,$output,403);
            }
        }
        $res = DocumentReview::deleteReview($id);
        if ($res) {
            $output["error"] = false;
            $output["message"] = "Your review has been deleted successfully. ";
            $output["id"] = $id;
            return $this->jsonResponse($response,$output,200);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to delete review. Please try again.";
            return $this->jsonResponse($response,$output,400);
        }
    }

    public function endorse(Request $request, Response $response, $args)
    {

        $output = array();
        $output["error"] = false;
        $user_id = 0;
        if (isset($_SESSION["userID"])) {
            $user_id = $_SESSION["userID"];
        } else {
            $user_id = $request->getParam('user_id');
        }
        $doc_id = $request->getParam('doc_id');
        $date_created = date('Y-m-d H:i:s');


        if (!Document::isIDExists($doc_id)) {
            $output['error'] = true;
            $output['message'] = 'The document is not available at this moment.';
            return $this->jsonResponse($response,$output,404);
        }

        $projectName = Document::getNameByID($doc_id);
        $doc_type_selected = Document::getDocType($doc_id);
        $doc_type = "";
        switch ($doc_type_selected) {
            case 1:
                $doc_type = "E-Book";
                break;

            case 2:
                $doc_type = "Audio Book";
                break;

            case 3:
                $doc_type = "Magazine";
                break;
        }

        $whoName = User::getNameByID($user_id);
        if (DocumentLike::isLikedBy($user_id, $doc_id)) {
            $record_id = DocumentLike::getActionRecordID($user_id, $doc_id);
            $output["id"] = $record_id;
            $res = DocumentLike::deleteLike($record_id);
            $updateOperation = true;
            if (!$res) {
                return $this->jsonResponse($response,["error" => true,"message" => 'Failed to remove' . $doc_type . ' from your favorites . Please try again.'],404);
            }
            $output["error"] = false;
            $output["title"] = "Remove form favorites";
//            $output["next_action"] = '<i class="fa fa-heart-o"></i>';
            $output["message"] = $doc_type . ' has been deleted. form your favorites.';
        } else {
            $res = DocumentLike::createLike($user_id, $doc_id, $date_created);
            if ($res["code"] == Constants::INSERT_SUCCESS) {
                $output["error"] = false;
                $output["title"] = $doc_type . " Favourite";
                $output["message"] = $doc_type . '- ' . $projectName . ' added to your favorites.';
//                $output["next_action"] = '<i class="fa fa-heart"></i>';
                $id = $res["id"];
                $output["id"] = $id;
                //Notify now
                $ownerID = Document::getOwnerID($doc_id);
                $qcode = Document::getQCodeByID($doc_id);
                try {
                    $title = $doc_type . " Recommended";
                    $message = "You favourite the " . $doc_type . " - " . $projectName . ".";
                    $activity = $whoName . " favourite the " . $doc_type . " - " . $projectName . ".";
                    $activity_res = Activity::createActivity((object)[
                        'who_id' => $user_id,
                        'title' => $title,
                        'message' => $activity,
                        'seen' => $qcode,
                        'data_id' => $qcode,
                        'data_title' => "Like"]);
                    if ($activity_res["code"] === Constants::INSERT_FAILURE) {
                        //$output["message"] .= " Log Created.";
                        return $this->jsonResponse($response,$activity_res,400);
                    }
                } catch (Exception $e) {
                    $output["info"] .= "Error sending notification => " . $e->getMessage();
                }
            } else {
                $output["error"] = true;
                $output["message"] = 'Failed to endorse ' . $doc_type . '. Please try again.';
                return $this->jsonResponse($response,$output,400);
            }
        }
        return $this->jsonResponse($response,$output);
    }

}
