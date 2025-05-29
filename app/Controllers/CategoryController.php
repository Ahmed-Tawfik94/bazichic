<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Category;
use App\Helpers\Constants;
class CategoryController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {

        $data = Category::getAllCategories();
        $vars = [
            'page' => [
                'title' => 'Manage categories',
                'description' => 'List of categories',
                'data' => $data,
                'num_categories' => count($data),
                'name'=>'manage-documents'
            ]
        ];

        return $this->view->render($response, 'admin/admin_categories_listing.twig', $vars);
    }
    public function add(Request $request, Response $response, $args)
    {
        $vars = [
            'page' => [
                'title' => 'Add New Category',
                'description' => 'Add New Category',
            ]
        ];
        return $this->view->render($response, 'admin/category-add.twig', $vars);
        
    }
    public function create(Request $request, Response $response, $args)
    {
        //ADMIN ONLY	
        $params =$request->getParsedBody();
        $output = array();
        $output["error"] = false;
        $title = $params['title'];
        $description = $params['description'];
        $is_published = $params['is_published'];
        $magazine_only = 0;
        if (isset($params['magazine_only'])){
            $magazine_only = $params['magazine_only'];
        }

        if (empty($title)) {
            $output['error'] = true;
            $output['message'] = 'Please enter a name for the category.';
            return $this->jsonResponse($response,$output,400);
        }

        $qcode = Category::generateCode();
        $res = Category::createCategory([
            'title'=>$title,
            'description'=>$description,
            'is_published'=>$is_published,
            'qcode'=>$qcode,
            'magazine_only'=>$magazine_only,
            'taxonomy'=>$description
        ]);
        if ($res["code"] !== Constants::INSERT_SUCCESS) {
            $output["error"] = true;
            $output["info"] = $res["message"];
            $output["message"] = "Failed to add category. Please try again.";
            return $this->jsonResponse($response,$output,400);

        }
        $output["error"] = false;
        $output["message"] = "Your category has been added successfully. ";
        $id = $res["id"];
        $output["id"] = $id;
        return $this->jsonResponse($response,$output,200);
    }

    public function edit(Request $request, Response $response, $args)
    {

        $qcode = $request->getAttribute('id');
        $id = Category::getIDByQCode($qcode);
        $categories = Category::getID($id);
        if ($categories !== NULL) {
            $title = $categories["title"];
            $description = $categories["description"];
            $taxonomy = $categories["taxonomy"];
            $qcode = $categories["qcode"];
            $magazine_only = $categories["magazine_only"];
            $is_published = $categories["is_published"];
        }

        $vars = [
            'page' => [
                'title' => 'Update Category',
                'description' => 'Update your Existing category'
            ],
            'category' => [
                'category_id' => $id,
                'title' => $title,
                'description' => $description,
                'taxonomy' => $taxonomy,
                'qcode' => $qcode,
                'magazine_only' => $magazine_only,
                'is_published' => $is_published
            ]
        ];
        return $this->view->render($response, 'admin/category-edit.twig', $vars);
    }


    public function update (Request $request, Response $response, $args) {
        //ADMIN ONLY	
        $output = array();
        $params =$request->getParsedBody();
        $title = $params['title'];
        $taxonomy = $params['taxonomy'];
        $magazine_only = 0;
        if (null !== $params['magazine_only']){
            $magazine_only = $params['magazine_only'];
        }
        $id = $params['category_id'];
        $description = $params['description'];
        $is_published = $params['is_published'];

        if (empty($title)) {
            $output['error'] = true;
            $output['message'] = 'Please enter a name for the category.';
            return $this->jsonResponse($response,$output,400);
        }

        $res = Category::updateCategory($id, [
        'title'=>$title,
        'description'=>$description,
        'taxonomy'=>$taxonomy,
        'magazine_only'=>$magazine_only,
        'is_published'=>$is_published,
        ]);
        if (!$res) {
            $output["error"] = true;
            $output["message"] = "Failed to update category. Please try again.";
            return $this->jsonResponse($response,$output,400);
        }
        $output["error"] = false;
        $output["message"] = "Category " . $title . " has been updated successfully. ";
        $output["id"] = $id;
        return $this->jsonResponse($response,$output,200);
    }

    public function delete (Request $request, Response $response, $args){
        $output = array();
        $params =$request->getParsedBody();
        $id = $params['id'];
        $res = Category::deleteCategory($id);
        if (!$res) {
            $output["error"] = true;
            $output["message"] = "Failed to delete category. Please try again.";
            return $this->jsonResponse($response,$output,400);
        }
            $output["error"] = false;
            $output["message"] = "Document category has been deleted successfully. ";
            $output["id"] = $id;
            return $this->jsonResponse($response,$output,200);
    }

}
