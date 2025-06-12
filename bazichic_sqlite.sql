-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2024 at 06:59 PM
-- Server version: 8.0.39-cll-lve
-- PHP Version: 8.3.11



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bazichic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int NOT NULL,
  `who_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(300) NOT NULL,
  `data_id` varchar(20) NOT NULL,
  `data_title` varchar(30) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `date_created` varchar(30) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `app_usage`
--

CREATE TABLE `app_usage` (
  `id` int NOT NULL,
  `api_key` varchar(40) NOT NULL,
  `ipAddress` varchar(50) NOT NULL DEFAULT '',
  `signature` varchar(200) NOT NULL DEFAULT '',
  `callerInfo` varchar(100) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(300) NOT NULL DEFAULT '',
  `is_published` tinyint NOT NULL DEFAULT '1',
  `qcode` varchar(50) NOT NULL DEFAULT '',
  `magazine_only` tinyint NOT NULL DEFAULT '0',
  `taxonomy` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(300) NOT NULL,
  `date_created` varchar(20) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int NOT NULL,
  `currency` varchar(30) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `factor` double NOT NULL DEFAULT '1',
  `status` varchar(20) NOT NULL DEFAULT 'Active'
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `link` varchar(200) NOT NULL,
  `cover` varchar(300) NOT NULL,
  `is_downloadable` tinyint NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `document_type` int NOT NULL,
  `category_id` int NOT NULL,
  `user_id` int NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `author_link` varchar(500) NOT NULL DEFAULT '',
  `author_desc` varchar(1000) NOT NULL,
  `num_pages` int NOT NULL,
  `price` int NOT NULL,
  `listen_time` int NOT NULL DEFAULT '0',
  `read_time` int NOT NULL DEFAULT '0',
  `tag` varchar(30) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `file_type` varchar(50) NOT NULL DEFAULT 'Pdf',
  `note` text NOT NULL,
  `qcode` varchar(30) NOT NULL,
  `date_created` varchar(30) NOT NULL DEFAULT '',
  `date_updated` varchar(30) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `document_audios`
--

CREATE TABLE `document_audios` (
  `id` int NOT NULL,
  `title` varchar(100)  NOT NULL DEFAULT '''''',
  `document_id` int NOT NULL,
  `file` varchar(500)  NOT NULL,
  `sno` int NOT NULL DEFAULT '1',
  `description` varchar(200)  NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)   ;

-- --------------------------------------------------------

--
-- Table structure for table `document_likes`
--

CREATE TABLE `document_likes` (
  `id` int NOT NULL,
  `doc_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_created` varchar(30) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `document_reviews`
--

CREATE TABLE `document_reviews` (
  `id` int NOT NULL,
  `doc_id` int NOT NULL,
  `user_id` int NOT NULL,
  `stars` varchar(10) NOT NULL,
  `text` text NOT NULL,
  `date_created` varchar(30) NOT NULL,
  `date_updated` varchar(20) NOT NULL DEFAULT ''
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `document_saves`
--

CREATE TABLE `document_saves` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `doc_id` int NOT NULL,
  `page` int NOT NULL,
  `progress` int NOT NULL,
  `date_created` varchar(30) NOT NULL,
  `date_updated` varchar(30) NOT NULL DEFAULT ''
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `url_name` varchar(500) NOT NULL,
  `is_published` tinyint NOT NULL DEFAULT '1'
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `document_views`
--

CREATE TABLE `document_views` (
  `id` int NOT NULL,
  `document_id` int NOT NULL,
  `user_id` int NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)   ;

-- --------------------------------------------------------

--
-- Table structure for table `doc_keywords`
--

CREATE TABLE `doc_keywords` (
  `id` int NOT NULL,
  `doc_id` int NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL DEFAULT '0',
--   `sort_id` int NOT NULL DEFAULT '0',
  `description` text NOT NULL,
--   `qcode` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `is_published` tinyint NOT NULL DEFAULT '1',
  `date_created` timestamp NOT NULL DEFAULT (datetime('now')),
  `date_updated` varchar(20) NOT NULL DEFAULT ''
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
--   `sort_id` int NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `faq_sub_categories`
--

CREATE TABLE `faq_sub_categories` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `qcode` varchar(100) NOT NULL,
  `category_id` int NOT NULL,
  `sort_id` int NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `free_trials`
--

CREATE TABLE `free_trials` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `date_created` varchar(20) NOT NULL,
  `date_expiring` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `generated_otps`
--

CREATE TABLE `generated_otps` (
  `id` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now')),
  `date_used` varchar(20) NOT NULL DEFAULT ''
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `membersip_plans`
--

CREATE TABLE `membersip_plans` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `qcode` varchar(20) NOT NULL,
  `membersip_desc` longtext NOT NULL,
  `action_label` varchar(60) NOT NULL DEFAULT 'Get Started',
  `price` double NOT NULL DEFAULT '0',
  `currency_id` int NOT NULL,
  `duration` int NOT NULL,
  `tagline` varchar(100) NOT NULL,
--   `note` varchar(200) NOT NULL DEFAULT '',
--   `is_available` tinyint NOT NULL DEFAULT '1',
--   `sort_order` tinyint NOT NULL DEFAULT '0',
--   `is_highlighted` tinyint NOT NULL DEFAULT '0',
  `date_created` varchar(20) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL,
  `status` varchar(20) NOT NULL,
  `data_id` int NOT NULL,
  `data_title` varchar(20) NOT NULL,
  `date_created` varchar(20) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `paypal_tests`
--

CREATE TABLE `paypal_tests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `code` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `date_created` varchar(30) NOT NULL,
  `date_updated` varchar(30) NOT NULL,
--   `note` varchar(200) NOT NULL DEFAULT ''
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `reward_points`
--

CREATE TABLE `reward_points` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `points` int NOT NULL,
  `transaction_type` varchar(20) NOT NULL DEFAULT 'None',
  `date_created` varchar(30) NOT NULL,
--   `note` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
--   `pay_key` varchar(50) NOT NULL DEFAULT '',
--   `pay_secret` varchar(200) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
--   `latitude` varchar(20) NOT NULL DEFAULT '0.00',
--   `longitude` varchar(20) NOT NULL DEFAULT '0.00',
--   `enable_member_uploads` tinyint NOT NULL DEFAULT '0',
  `maintenance_on` tinyint NOT NULL DEFAULT '0',
  `facebook_link` varchar(100) NOT NULL DEFAULT '',
  `twitter_link` varchar(100) NOT NULL DEFAULT '',
  `banner_link` varchar(200) NOT NULL DEFAULT 'uploads/images/banners/bg.jpg'
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `store_tags`
--

CREATE TABLE `store_tags` (
  `id` int NOT NULL,
  `title` varchar(30) NOT NULL,
  `enabled` tinyint NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_logs`
--

CREATE TABLE `subscription_logs` (
  `id` int NOT NULL,
  `log` varchar(500) NOT NULL DEFAULT '',
  `date_created` varchar(30) NOT NULL,
  `date_expiring` varchar(30) NOT NULL
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `company` varchar(500) NOT NULL,
  `review` varchar(500) NOT NULL,
  `is_published` tinyint NOT NULL DEFAULT '1'
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `item_code` varchar(30) NOT NULL DEFAULT '',
  `refCode` varchar(30) NOT NULL DEFAULT '',
  `mode` varchar(20) NOT NULL DEFAULT 'Online',
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `currency_code` varchar(20) NOT NULL DEFAULT '',
  `pay_key` varchar(100) NOT NULL,
  `txn_id` varchar(100) NOT NULL,
  `app_status` varchar(30) NOT NULL,
  `note` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'Customer',
  `email` varchar(40) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `country` varchar(30) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Pending',
--   `address` varchar(50) NOT NULL DEFAULT '',
--   `latitude` varchar(20) NOT NULL DEFAULT '',
--   `longitude` varchar(20) NOT NULL DEFAULT '',
  `user_image` varchar(300) NOT NULL DEFAULT '',-- profile_image--
  `role_id` int NOT NULL,
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `ref_user_id` int NOT NULL DEFAULT '0',--set to null--
  `referral_code` varchar(20) NOT NULL DEFAULT '',
  `api_key` varchar(50) NOT NULL,
--   `paypal` varchar(50) NOT NULL DEFAULT '',
  `date_created` timestamp NOT NULL DEFAULT (datetime('now')),
  `date_updated`timestamp NOT NULL DEFAULT (datetime('now')),
  `last_active` varchar(30) NOT NULL DEFAULT '',
  `dob` varchar(30) NOT NULL DEFAULT '',
  `reg_source` varchar(10) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT (datetime('now'))
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `user_memberships`
--

CREATE TABLE `user_memberships` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `date_expiring` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `amount` int NOT NULL DEFAULT '0',
  `qcode` varchar(20) NOT NULL,
  `note` varchar(100) NOT NULL DEFAULT '',
  `date_created` varchar(20) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` varchar(20) NOT NULL DEFAULT CURRENT_TIMESTAMP
)  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_usage`
--
ALTER TABLE `app_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_audios`
--
ALTER TABLE `document_audios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_likes`
--
ALTER TABLE `document_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_reviews`
--
ALTER TABLE `document_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doc_id` (`doc_id`,`user_id`);

--
-- Indexes for table `document_saves`
--
ALTER TABLE `document_saves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_views`
--
ALTER TABLE `document_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_keywords`
--
ALTER TABLE `doc_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq_sub_categories`
--
ALTER TABLE `faq_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `free_trials`
--
ALTER TABLE `free_trials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `generated_otps`
--
ALTER TABLE `generated_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membersip_plans`
--
ALTER TABLE `membersip_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_tests`
--
ALTER TABLE `paypal_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward_points`
--
ALTER TABLE `reward_points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_tags`
--
ALTER TABLE `store_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- Indexes for table `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qcode` (`qcode`);

--
--  for dumped tables
--

--
--  for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int NOT NULL ;

--
--  for table `app_usage`
--
ALTER TABLE `app_usage`
  MODIFY `id` int NOT NULL ;

--
--  for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL ;

--
--  for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL ;

--
--  for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int NOT NULL ;

--
--  for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_audios`
--
ALTER TABLE `document_audios`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_likes`
--
ALTER TABLE `document_likes`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_reviews`
--
ALTER TABLE `document_reviews`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_saves`
--
ALTER TABLE `document_saves`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int NOT NULL ;

--
--  for table `document_views`
--
ALTER TABLE `document_views`
  MODIFY `id` int NOT NULL ;

--
--  for table `doc_keywords`
--
ALTER TABLE `doc_keywords`
  MODIFY `id` int NOT NULL ;

--
--  for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL ;

--
--  for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` int NOT NULL ;

--
--  for table `faq_sub_categories`
--
ALTER TABLE `faq_sub_categories`
  MODIFY `id` int NOT NULL ;

--
--  for table `free_trials`
--
ALTER TABLE `free_trials`
  MODIFY `id` int NOT NULL ;

--
--  for table `generated_otps`
--
ALTER TABLE `generated_otps`
  MODIFY `id` int NOT NULL ;

--
--  for table `membersip_plans`
--
ALTER TABLE `membersip_plans`
  MODIFY `id` int NOT NULL ;

--
--  for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL ;

--
--  for table `paypal_tests`
--
ALTER TABLE `paypal_tests`
  MODIFY `id` int NOT NULL ;

--
--  for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int NOT NULL ;

--
--  for table `reward_points`
--
ALTER TABLE `reward_points`
  MODIFY `id` int NOT NULL ;

--
--  for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL ;

--
--  for table `store_tags`
--
ALTER TABLE `store_tags`
  MODIFY `id` int NOT NULL ;

--
--  for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL ;

--
--  for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int NOT NULL ;

--
--  for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL ;

--
--  for table `user_memberships`
--
ALTER TABLE `user_memberships`
  MODIFY `id` int NOT NULL ;
