<?php

namespace App\Service\stripe;

use App\Models\Plan;
use Stripe\Price;
use Stripe\Product;

class ProductService extends StripeService
{
    public function createProduct($data): array
    {
        try {
            // Create a new product in Stripe
            $product = Product::create([
                'name' =>$data['name'],
                'description' =>$data['description'],
            ]);

            // Create a recurring price for the product
            $price = Price::create([
                'unit_amount' => $data['price'],
                'currency' => $data['currency'],
                'recurring' => ['interval' => $data['interval'],'trial_period_days' => $data['trial_period_days']],
                'product' => $product->id,
            ]);

            // Save product details to the database
            Plan::createPlan((object)[
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id,
                'name' => $product->name,
                'description' => $product->description,
                'is_available'=>$data['is_available'],
                'price' => $price->unit_amount,
                'currency' => $price->currency,
                'interval' => $price->recurring->interval,
            ]);

            // Return the session URL
            return [
                'status' => 'success',
                'product' => $product,
                'price' => $price,
                // 'checkout_url' => $session->url,
            ];

        } catch (\Exception $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function getProducts($limit = 10, $startingAfter = null): array
    {
        try {
            // Fetch products from Stripe
            $params = [
                'limit' => $limit,
            ];

            // If startingAfter is provided, add it to the params for pagination
            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $products = Product::all($params);

            // Return the list of products
            return [
                'status' => 'success',
                'products' => $products->data, // Contains the array of product objects
                'has_more' => $products->has_more, // Indicates if there are more products to fetch
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function updateProduct($productId, $data): array
    {
        try {
            // Update the product in Stripe
            $product = Product::update($productId, [
                'name' => $data['name'] ?? null,
                'description' => $data['description'] ?? null,
                // Add any other fields you want to update
            ]);

            // Return the updated product details
            return [
                'status' => 'success',
                'product' => $product,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function deleteProduct($productId): array
    {
        try {
            // Delete the product in Stripe
            $deletedProduct = Product::retrieve($productId);
            $deletedProduct->delete();
            // Return success response
            return [
                'status' => 'success',
                'deleted' => true,
                'id' => $deletedProduct->id,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function createPrice($productId, $amount, $currency = 'usd', $interval = 'month'): array
    {
        try {
            // Create a new price for the specified product
            $price = Price::create([
                'unit_amount' => $amount, // Amount in cents
                'currency' => $currency,
                'recurring' => [
                    'interval' => $interval, // e.g., 'month' or 'year'
                ],
                'product' => $productId,
            ]);

            return [
                'status' => 'success',
                'price' => $price,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function getPrices($productId, $limit = 10, $startingAfter = null): array
    {
        try {
            // Fetch prices for the specified product
            $params = [
                'limit' => $limit,
                'product' => $productId,
            ];

            // If startingAfter is provided, add it to the params for pagination
            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $prices = Price::all($params);

            return [
                'status' => 'success',
                'prices' => $prices->data,
                'has_more' => $prices->has_more,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function updatePrice($productId, $oldPriceId, $newAmount, $interval): array
    {
        try {
            // Step 1: Create a new price in Stripe
            $newPrice = \Stripe\Price::create([
                'unit_amount' => $newAmount * 100, // Convert to cents
                'currency' => 'usd',
                'recurring' => ['interval' => $interval], // e.g., 'month', 'year'
                'product' => $productId,
            ]);

            // Step 2: (Optional) Archive the old price in Stripe
            \Stripe\Price::update($oldPriceId, [
                'active' => false
            ]);

            return [
                'status' => 'success',
                'price' => $newPrice,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function deletePrice($priceId): array
    {
        try {
            // Delete the specified price
            $deletedPrice = Price::retrieve($priceId);
            $deletedPrice->active=false;


            return [
                'status' => 'success',
                'deleted' => true,
                'id' => $deletedPrice->id,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

}