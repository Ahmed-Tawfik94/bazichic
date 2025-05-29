<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Helpers;
use App\Models\FAQ;
use App\Models\FAQCategory;
use App\Models\FAQSubCategory;
use App\Models\Util;

class FAQController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $data = FAQ::getAllFAQs(1);

        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["title"] = $row["title"];
                $tmp["description"] = $row["description"];
                $tmp["qcode"] = $row["qcode"];
                $tmp["url"] = $row["url"];
                $tmp["category_id"] = $row["category_id"];
                $tmp["category"] = FAQCategory::getNameByID($row["category_id"]);
                $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);
                $custom_data[] = $tmp;
            }
        }


        $categories_arr = FAQCategory::all();
        $categories = $this->getCategories($categories_arr);
        $subcategories = FAQSubCategory::all();
        $vars = [
            'page' => [
                'title' => 'Manage FAQs',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'data' => $custom_data,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'name'=>'manage-faqs'
            ],
        ];
        return $this->view->render($response, 'admin/manage-faqs.twig', $vars);

    }

    /**
     * @param $categories_arr
     * @return array
     */
    public function getCategories($categories_arr): array
    {
        $helper = new Helpers();
        $categories = [];
        foreach ($categories_arr as $category) {
            $tmp = array();
            $tmp["id"] = $category->id;
            $tmp["title"] = $category->title;
            // Access the related FAQSubCategory instances
            $subcategories = $category->getFAQSubCategories;

            $tmp["subcategories"] = array();
            $tmp["numCategories"] = count($subcategories);
            foreach ($subcategories as $thisCategory) {
                $tmp["subcategories"][] =$helper->getFAQSubCategoryDetails($thisCategory->id);
            }
            $categories[] = $tmp;
        }
        return $categories;
    }

    public function add(Request $request, Response $response, $args)
    {
        $helper = new Helpers();

        $categories_arr = FAQCategory::all();
        $categories = $this->getCategories($categories_arr);
        $title = 'Add New FAQ';
        $vars = [
            'page' => [
                'title' => $title,
                'categories' => $categories,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'name'=>'manage-faqs'
            ],
        ];
        return $this->view->render($response, 'admin/faq-create.twig', $vars);
    }

    public function listFAQ(Request $request, Response $response, $args)
    {
        $helper = new Helpers();
        $subcategory_qcode = $args['qcode'] ?? null;
        $custom_data = FAQ::getAllFAQs(1);


        $categories_arr = FAQCategory::all();
        $categories = $this->getCategories($categories_arr);


        /****************** EXAMINE SELECTION *****************/
        $custom_info = "";
        $query = FAQ::query();

        // Only fetch published FAQs
        $query->where('is_published', 1);

        // Filter by category (if provided)
        if (!empty($subcategory_qcode) && FAQCategory::isCodeValid($subcategory_qcode)) {
            $categoryID = FAQSubCategory::where('qcode',$subcategory_qcode)->first();
            $query->where('subcategory_id', $categoryID->id);
        }

        // Sort results
        $query->orderBy('id', 'DESC');

        // Execute query and fetch results
        $data = $query->get();
        $custom_data = array();
        foreach ($data as $thisCategory) {
            $thisCategoryCustom = $helper->getFAQDetails($thisCategory["id"]);
            $custom_data[] = $thisCategoryCustom;
        }
        /****************** EXAMINE SELECTION *****************/

        $vars = [
            'page' => [
                'title' => 'View FAQs',
                'description' => 'Find E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'data' => $custom_data,
                'categories' => $categories
            ],
        ];
        return $this->view->render($response, 'list-faqs.twig', $vars);

    }

    public function edit(Request $request, Response $response, $args)
    {

        $categories = array();

        $docQCode = $request->getAttribute('id');
        $faqEntry = FAQ::find($docQCode);
        if(!$faqEntry){
            return $response->withRedirect((string)$this->router->pathFor('notFound'));
        }

        $category_name = $faqEntry->title;
        $subcategory_name = $faqEntry->sub_category->title;
//        $subcategory_name = $subcategory_name->title;
        $categories_arr = FAQCategory::all();
        $subcategories = FAQSubCategory::all();


        $title = 'Update FAQ';
        $vars = [
            'page' => [
                'title' => $title,
                'categories' => $categories_arr,
                'subCategories' => $subcategories,
                'faqEntry' => $faqEntry,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'category_name' => $category_name,
                'subcategory_name' => $subcategory_name
            ]
        ];
        return $this->view->render($response, 'admin/faq-edit.twig', $vars);

    }

    public function get_all(Request $request, Response $response, $args)
    {
        $categories_arr = FAQCategory::all();
        $categories = $this->getCategories($categories_arr);
        $vars = [
            'page' => [
                'title' => 'FAQs | BaziChic Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'categories' => $categories
            ]
        ];
        return $this->view->render($response, 'frequently-asked-questions.twig', $vars);
    }

    public function get_one(Request $request, Response $response, $args)
    {
        $id = $request->getAttribute('id');
        $categories_arr = FAQCategory::all();
        $categories = $this->getCategories($categories_arr);
        $faqEntry = FAQ::find($id);
        if(!$faqEntry){
            return $response->withRedirect((string)$this->router->pathFor('notFound'));
        }
        //$faqEntry = $faqCRUD->getBySEOUrl($docQCode);
        $vars = [
            'page' => [
                'title' => 'View FAQ | Bazichic Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'categories' => $categories,
                'faq' => [
                    'id' => $faqEntry["id"],
                    'title' => $faqEntry["title"],
                    'description' => $faqEntry["description"],
                    'category_id' => $faqEntry["category_id"],
                    'url' => $faqEntry["url"],
                    'qcode' => $faqEntry["qcode"]
                ]
            ]
        ];
        return $this->view->render($response, 'faq.twig', $vars);
    }

}
