<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Helpers;
use App\Models\FAQ;
use App\Models\FAQCategory;
use App\Models\FAQSubCategory;

class FAQCategoryController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $categories = array();
        $categories_arr = FAQCategory::all();
        foreach ($categories_arr as $category) {
            $tmp = array();
            $tmp["id"] = $category->id;
            $tmp["title"] = $category->title;
            $tmp["numFaqs"] = FAQ::getNumFAQsInCategory($category->id);
            $subcategory = $category->getFAQSubCategories;
            $tmp["subcategories"] = array();
            $tmp["numCategories"] = count($subcategory);
            foreach ($subcategory as $thisCategory) {
                $tmp["subcategories"][] = [
                    'id' => $thisCategory->id,
                    'title' => $thisCategory->title,
                    'category_id' => $category->id,
                    'category' => $category->title,
                    'qcode' => $thisCategory->qcode,
                    'numFaqs' => $tmp["numFaqs"],
                ];
            }
            $categories[] = $tmp;
        }

        $vars = [
            'page' => [
                'title' => 'Manage FAQs Categories',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'categories' => $categories,
                'name'=>'manage-faqs'
            ],
        ];
        return $this->view->render($response, 'admin/faqs-category-manager.twig', $vars);

    }

    public function add(Request $request, Response $response, $args)
    {
        $helper = new Helpers();

        $categories = array();
        $categories_arr = FAQCategory::all();
        foreach ($categories_arr as $category) {
            $tmp = array();
            $tmp["id"] = $category["id"];
            $tmp["title"] = $category["title"];
            $tmp["sort_id"] = $category["sort_id"];

            $subcategory = FAQCategory::where('category_id', $category["id"])->getFAQSubCategories;
            $tmp["subcategories"] = array();
            $tmp["numCategories"] = count($subcategory);
            foreach ($subcategory as $thisCategory) {
                $thisCategoryCustom = $helper->getFAQSubCategoryDetails($thisCategory["id"], $this->db);
                array_push($tmp["subcategories"], $thisCategoryCustom);
            }
            array_push($categories, $tmp);
        }

        $updateMode = false;
        $title = 'Add New FAQ';
        //$list_categories = $categoryCRUD->getActiveEbookCategories();
        if ($updateMode) {
            $title = 'Update FAQ';
        }
        $vars = [
            'page' => [
                'title' => $title,
                'categories' => $categories,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ],
        ];
        return $this->view->render($response, 'admin/faq-create.twig', $vars);
    }

    public function addCat(Request $request, Response $response, $args)
    {
        $title = 'Add FAQ Category';
        $vars = [
            'page' => [
                'title' => $title,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ]
        ];
        return $this->view->render($response, 'admin/faq-category-add.twig', $vars);
    }

    public function addSubCat(Request $request, Response $response, $args)
    {

        $title = 'Add FAQ Sub-Category';
        $categories = FAQCategory::all();
        $vars = [
            'page' => [
                'title' => $title,
                'categories' => $categories,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ]
        ];
        return $this->view->render($response, 'admin/faq-subcategory-add.twig', $vars);
    }


    public function editCat(Request $request, Response $response, $args)
    {
        $id = $request->getAttribute('id');
        $faqEntry = FAQCategory::where('id', $id)->get()->first();

        $title = 'Update FAQ Category';
        $vars = [
            'page' => [
                'title' => $title,
                'faqEntry' => $faqEntry,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines'
            ]
        ];
        return $this->view->render($response, 'admin/faq-category-edit.twig', $vars);
    }

    public function editSubCat(Request $request, Response $response, $args)
    {
        $categories = array();
        $docQCode = $request->getAttribute('id');
        $faqEntry = FAQSubCategory::find($docQCode);
        if(!$faqEntry){
            return $response->withRedirect((string) $this->router->pathFor('notFound'));
        }

        $categories_arr = FAQCategory::all();
        foreach ($categories_arr as $category) {
            $tmp = array();
            $tmp["id"] = $category["id"];
            $tmp["title"] = $category["title"];
            $tmp["sort_id"] = $category["sort_id"];
            $tmp["qcode"] = $category["qcode"];
            $categories[] = $tmp;
        }


        $title = 'Update FAQ Sub-Category';
        $vars = [
            'page' => [
                'title' => $title,
                'categories' => $categories,
                'faqEntry' => $faqEntry,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',

            ]
        ];
        return $this->view->render($response, 'admin/edit-faq-subcategory.twig', $vars);

    }

}
