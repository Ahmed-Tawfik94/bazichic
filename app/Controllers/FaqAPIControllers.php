<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Constants;
use App\Models\FAQ;
use App\Models\FAQCategory;
use App\Models\FAQSubCategory;

class FaqAPIControllers extends BaseController
{
    public function createFAQCat(Request $request, Response $response, $args)
    {
        $output = array();
        $title = $request->getParam('title');
        if (empty($title)) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "You must enter a title for FAQ entry. "],400);
        }
        if (strlen($title) > 50) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "FAQ category title is too large. "],400);
        }
        $res = FAQCategory::createCategory($title);
        if ($res["code"] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "Failed to create FAQ category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=> "FAQ category has been created successfully."],200);
    }

    public function UpdateFAQCat(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');
        $id = $request->getParam('id');
        if (!FAQCategory::doesIDExists($id)) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "Looks like we did not find the FAQ category you want to update. "],400);
        }
        if (empty($title)) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "You must enter a title for FAQ category. "],400);
        }
        if (strlen($title) > 40) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "FAQ category title is too large. "],400);
        }
        $res = FAQCategory::updateCategory($id, $title);
        if ($res['code']== Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,'message'=>  "Failed to update FAQ category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=>  "FAQ category has been updated successfully."],200);
    }

    public function DeleteFAQCat(Request $request, Response $response, $args)
    {
        $id = $request->getParam('id');

        $res = FAQCategory::deleteCategory($id);
        if ($res['code']== Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,'message'=>  "Failed to delete FAQ category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=>  "FAQ category has been deleted successfully. "],200);
    }

    public function CreateFAQSubCat(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');
        $category_id = $request->getParam('category_id');
        if (empty($title)) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "You must enter a title for FAQ sub-category. "],400);
        }
        $qcode = FAQCategory::generateSubCategoryCode();
        if (strlen($title) > 50) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "FAQ sub-category title is too large. "],400);
        }
        $res = FAQCategory::createSubCategory($title, $qcode, $category_id);
        if ($res["code"]==Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,'message'=> "Failed to create FAQ sub-category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=> "FAQ sub-category has been created successfully."],200);
    }


    public function UpdateFAQSubCat(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');
        $category_id = $request->getParam('category_id');
        $id = $request->getParam('id');
        if (!FAQCategory::doesSubCategoryIDExists($id)) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"Looks like we did not find the FAQ sub-category you want to update. "],404);
        }
        if (empty($title)) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"You must enter a title for FAQ sub-category. "],400);
        }
        if (strlen($title) > 40) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"FAQ sub-category title is too large. "],400);
        }
        $res = FAQCategory::updateSubCategory($id, $title, $category_id);
        if ($res['code']==Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,"Failed to update FAQ sub-category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=>"FAQ sub-category has been updated successfully."],200);
    }

    public function DeleteFAQSubCat(Request $request, Response $response, $args)
    {
        $id = $request->getParam('id');

        $res = FAQCategory::deleteSubCategory($id);
        if ($res['code'] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"Failed to delete FAQ sub-category. Please try again."],400);
        }
        return $this->jsonResponse($response,['error'=>false,'message'=>"FAQ sub-category has been deleted successfully. "],200);
    }

    public function createFAQ(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = true;
        $title = $request->getParam('title');
        $category_id = $request->getParam('category_id');
        $subcategory_id = $request->getParam('subcategory_id');
        $description = $request->getParam('editordata');
        $is_published = 1;
        if ($request->getParam('is_published') !== null) {
            $is_published = $request->getParam('is_published');
        }
        if (empty($title)) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"You must enter a title for FAQ entry. "],400);
        }
        if (strlen($title) > 100) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"FAQ title is too large. "],400);
        }
        if (empty($category_id)) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"You must select a category for FAQ entry. "],400);
        }
        if (empty($subcategory_id)) {
            return $this->jsonResponse($response,['error'=>true,'message'=>"You must select a sub-category for FAQ entry. "],400);
        }
        $title = ltrim($title);
        $url = str_replace(" ", "-", $title);
        $url = strtolower($url);
        $res = FAQ::createFAQ((object)[
            'title' => $title,
            'description' => $description,
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
            'url' => $url,
            'is_published' => $is_published
        ]);
        if ($res["code"] ==Constants::INSERT_FAILURE) {
            $this->logger->info($res['message']);
            return $this->jsonResponse($response,["error" => true,"message"=> "Failed to create FAQ. Please try again."],400);
        }
        return $this->jsonResponse($response,["error" => false,"message"=> "FAQ has been created successfully."],200);
    }

    public function UpdateFAQ(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');
        $category_id = $request->getParam('category_id');
        $subcategory_id = $request->getParam('subcategory_id');
        $description = $request->getParam('editordata');
        $id = $request->getParam('id');
        if (!FAQ::doesIDExists($id)) {
            return $response->withRedirect((string) $this->router->pathFor('notFound'));
        }
        $is_published = 1;
        if ( $request->getParam('is_published') !== null) {
            $is_published = $request->getParam('is_published');
        }
        if (empty($title)) {
            return $this->jsonResponse($response,["error" => true,"message"=> "You must enter a title for FAQ entry. "],400);
        }
        if (strlen($title) > 100) {
            return $this->jsonResponse($response,["error" => true,"message"=>"FAQ title is too large. "],400);
        }
        if (empty($category_id)) {
            return $this->jsonResponse($response,["error" => true,"message"=> "You must select a category for FAQ entry. "],400);
        }
        $title = ltrim($title);
        $url = str_replace(" ", "-", $title);
        $url = strtolower($url);
        $res = FAQ::updateFAQ((object)['id'=>$id,
            'title'=>$title,
            'description'=>$description,
            'category_id'=>$category_id,
            'subcategory_id'=>$subcategory_id,
            'url'=>$url, 'is_published'=>$is_published]);
        if ($res['code']== Constants::INSERT_FAILURE) {
            $this->logger->info($res['message']);
            return $this->jsonResponse($response,["error" => true,"message"=> "Failed to update FAQ. Please try again."],400);
        }
        return $this->jsonResponse($response,["error" => false,"message"=> "FAQ has been updated successfully."],200);
    }

    public function DeleteFAQ(Request $request, Response $response, $args)
    {
        $id = $request->getParam('id');

        $res = FAQ::deleteFAQ($id);
        if ($res['code']== Constants::INSERT_FAILURE) {
            $this->logger->info($res['message']);
            return $this->jsonResponse($response,["error" => true,"message"=>"Failed to delete FAQ. Please try again."],400);
        }
        return $this->jsonResponse($response,["error" => false,"message"=> "FAQ has been deleted successfully. "],200);
    }

    public function listFAQCat(Request $request, Response $response, $args)
    {
        $output = array();
        $selectedID = $request->getAttribute('category');
        if (is_numeric($selectedID) && $selectedID > 0) {
            $data = FAQSubCategory::where('category_id', $selectedID)->get();
        }

        if (!$data) {
            $output["error"] = true;
            $output["message"] = "Failed to list faqs subcategories.";
            return $this->jsonResponse($response,$output,400);
        }
        $output["error"] = false;
        $output["result"] = $data;
        $output["message"] = count($data) . " faqs categories found.";
        return $this->jsonResponse($response,$output,200);
    }
}
