-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: sdb-j.hosting.stackcp.net
-- Generation Time: Dec 22, 2023 at 02:16 PM
-- Server version: 10.4.31-MariaDB-log
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `6cash-agent-3139308d79`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_money`
--

CREATE TABLE `add_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','approve','reject') NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `add_money`
--

INSERT INTO `add_money` (`id`, `amount`, `transaction_id`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(16, '500.00', 'BDQo7Y-905', 'approve', 16, '2023-12-05 11:06:33', '2023-12-05 11:38:16'),
(17, '1.00', 'x767hV-770', 'reject', 16, '2023-12-05 11:15:19', '2023-12-05 11:51:59'),
(18, '1855.00', 'bZPcuY-873', 'approve', 16, '2023-12-05 11:18:23', '2023-12-05 11:37:16'),
(19, '1000.00', '5hayKO-756', 'approve', 16, '2023-12-05 11:38:36', '2023-12-05 11:38:54'),
(20, '2000.00', 'uI0NrX-547', 'reject', 16, '2023-12-05 11:39:32', '2023-12-05 11:40:08'),
(21, '500.00', '2TUoor-706', 'reject', 16, '2023-12-05 11:55:45', '2023-12-05 11:56:28'),
(22, '2000.00', '9meTlu-515', 'reject', 16, '2023-12-05 11:56:12', '2023-12-05 11:56:34'),
(23, '1000.00', 'UH5H07-454', 'pending', 16, '2023-12-06 06:52:40', '2023-12-06 06:52:40'),
(24, '21.00', '9hz8TU-521', 'pending', 16, '2023-12-06 06:55:00', '2023-12-06 06:55:00'),
(25, '500.00', 'A1TTrf-263', 'pending', 16, '2023-12-06 06:58:00', '2023-12-06 06:58:00'),
(26, '34.00', 'jq1CTT-714', 'pending', 16, '2023-12-06 07:00:04', '2023-12-06 07:00:04'),
(27, '1300.00', 'RRMk1p-655', 'reject', 16, '2023-12-06 07:02:41', '2023-12-15 06:39:48'),
(28, '2000.00', 'mkyhmP-112', 'approve', 16, '2023-12-08 13:05:44', '2023-12-15 06:39:43'),
(29, '2000.00', 'wrSWsr-217', 'approve', 16, '2023-12-14 11:18:17', '2023-12-14 11:18:51'),
(30, '30.00', 'wMuTyl-29', 'pending', 16, '2023-12-15 07:36:11', '2023-12-15 07:36:11'),
(31, '500.00', 'Qgh7IF-812', 'pending', 16, '2023-12-15 07:36:40', '2023-12-15 07:36:40'),
(32, '50.00', 'LjLA1u-798', 'pending', 22, '2023-12-15 10:58:01', '2023-12-15 10:58:01'),
(33, '1000.00', '8uqOmS-52', 'approve', 22, '2023-12-15 11:13:19', '2023-12-15 11:13:43'),
(34, '12.00', 'lzF2mp-708', 'pending', 18, '2023-12-17 10:58:28', '2023-12-17 10:58:28'),
(35, '12.00', 'NDWBYd-657', 'approve', 18, '2023-12-17 10:59:08', '2023-12-17 11:06:56'),
(36, '500.00', 'EOtnas-585', 'pending', 24, '2023-12-18 14:50:28', '2023-12-18 14:50:28'),
(37, '700.00', '5XRWjR-671', 'pending', 19, '2023-12-20 08:23:26', '2023-12-20 08:23:26'),
(38, '23.00', 'VUJ7R9-82', 'pending', 25, '2023-12-20 12:25:18', '2023-12-20 12:25:18'),
(39, '500.00', 'IhZrSr-979', 'pending', 19, '2023-12-20 12:29:20', '2023-12-20 12:29:20'),
(40, '1000.00', 'bENZuR-920', 'pending', 19, '2023-12-20 12:29:29', '2023-12-20 12:29:29'),
(41, '2000.00', 'M3VuYy-487', 'pending', 19, '2023-12-20 12:29:37', '2023-12-20 12:29:37'),
(42, '876.00', 'FpgeO4-472', 'pending', 19, '2023-12-20 13:18:18', '2023-12-20 13:18:18'),
(43, '1000.00', 'fmcLLR-236', 'pending', 25, '2023-12-21 10:38:53', '2023-12-21 10:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bonuses`
--

CREATE TABLE `bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `min_add_money_amount` double(14,2) NOT NULL DEFAULT 0.00,
  `limit_per_user` int(11) NOT NULL DEFAULT 0,
  `bonus_type` varchar(50) DEFAULT NULL,
  `bonus` double(14,2) NOT NULL DEFAULT 0.00,
  `max_bonus_amount` double(14,2) NOT NULL DEFAULT 0.00,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bonuses`
--

INSERT INTO `bonuses` (`id`, `title`, `user_type`, `min_add_money_amount`, `limit_per_user`, `bonus_type`, `bonus`, `max_bonus_amount`, `start_date_time`, `end_date_time`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bonus', 'all', 500.00, 15, 'percentage', 10.00, 100.00, '2023-12-14 12:59:00', '2023-12-15 12:59:00', 1, '2023-12-14 08:29:25', '2023-12-14 08:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'business_name', 'Bitsclan', NULL, NULL),
(2, 'currency', 'EUR', NULL, NULL),
(3, 'pagination_limit', '15', NULL, NULL),
(4, 'timezone', 'Asia/Karachi', NULL, NULL),
(5, 'agent_commission_percent', '10', NULL, NULL),
(6, 'cashout_charge_percent', '5', NULL, NULL),
(7, 'addmoney_charge_percent', '12', NULL, NULL),
(8, 'sendmoney_charge_flat', '10', NULL, NULL),
(9, 'logo', '2022-04-17-625bc937c0958.png', NULL, NULL),
(10, 'phone', '000000000', NULL, NULL),
(11, 'email', 'demo@gmail.com', NULL, NULL),
(12, 'theme', 'theme_1', NULL, NULL),
(13, 'two_factor', '1', NULL, NULL),
(14, 'phone_verification', '1', NULL, NULL),
(15, 'email_verification', NULL, NULL, NULL),
(16, 'refer_commission', NULL, NULL, NULL),
(17, 'address', 'road 12, Dhaka', NULL, NULL),
(18, 'footer_text', 'This is footer test for 6cash', NULL, NULL),
(19, 'currency_symbol_position', 'left', NULL, NULL),
(20, 'admin_commission', NULL, NULL, NULL),
(21, 'country', 'PK', NULL, NULL),
(22, 'ssl_commerz_payment', '{\"status\":\"0\",\"store_id\":\"\",\"store_password\":\"@ssl\"}', '2022-04-07 08:27:08', '2022-04-07 08:27:16'),
(23, 'money_transfer_message', '{\"status\":1,\"message\":\"EMoney Transfer from admin\"}', NULL, NULL),
(24, 'cash_in', '{\"status\":1,\"message\":\"Cash In successfully completed.\"}', NULL, NULL),
(25, 'cash_out', '{\"status\":1,\"message\":\"Cash Out\"}', NULL, NULL),
(26, 'send_money', '{\"status\":1,\"message\":\"Send Money successfully completed.\"}', NULL, NULL),
(27, 'request_money', '{\"status\":1,\"message\":\"Money successfully requested.\"}', NULL, NULL),
(28, 'denied_money', '{\"status\":1,\"message\":\"Denied Money\"}', NULL, NULL),
(29, 'approved_money', '{\"status\":1,\"message\":\"Approved Money\"}', NULL, NULL),
(30, 'add_money', '{\"status\":1,\"message\":\"Added to your account.\"}', NULL, NULL),
(31, 'received_money', '{\"status\":1,\"message\":\"Received Money\"}', NULL, NULL),
(32, 'push_notification_key', 'add-token', NULL, NULL),
(33, 'language', '[{\"id\":\"1\",\"name\":\"english\",\"direction\":\"ltr\",\"code\":\"en\",\"status\":1,\"default\":true},{\"id\":2,\"name\":\"English\",\"direction\":\"ltr\",\"code\":\"en-US\",\"status\":0,\"default\":false},{\"id\":3,\"name\":\"Bangla\",\"direction\":\"ltr\",\"code\":\"bn\",\"status\":0,\"default\":false},{\"id\":4,\"name\":\"Arabic\",\"direction\":\"rtl\",\"code\":\"ar\",\"status\":0,\"default\":false},{\"id\":4,\"name\":\"French\",\"direction\":\"rtl\",\"code\":\"fr\",\"status\":1,\"default\":false}]', NULL, '2023-11-28 13:46:54'),
(34, 'paypal', '{\"status\":\"0\",\"paypal_client_id\":\"\",\"paypal_secret\":\"\"}', '2022-04-10 09:41:04', '2022-04-10 09:41:24'),
(35, 'privacy_policy', '', NULL, NULL),
(36, 'terms_and_conditions', '', NULL, NULL),
(37, 'inactive_auth_minute', '30', NULL, NULL),
(38, 'about_us', '', NULL, NULL),
(39, 'stripe', '{\"status\":\"0\",\"api_key\":\"sk_test_51O5RrIH6Csn58Wp54tHcprMKjc9ssHkva4IeINhJE2Vj7tIzSQy1G41NtH66NXxKPkLw4BylLLdv20v5oDi7k0yr00ElAx263W\",\"published_key\":\"pk_test_51O5RrIH6Csn58Wp5KbGh7wKIHdNXJihVofUgw0PwtEd6VQL849isWnLZgiQrVoU4Lxay7fF399lbldBYTCJnRp2a00m95mMMqt\"}', '2022-04-16 07:43:45', '2023-11-06 13:02:03'),
(40, 'razor_pay', '{\"status\":\"0\",\"razor_key\":\"\",\"razor_secret\":\"\"}', '2022-04-16 08:04:01', '2022-04-19 06:31:39'),
(41, 'paystack', '{\"status\":\"0\",\"publicKey\":\"\",\"secretKey\":\"\",\"paymentUrl\":\"https:\\/\\/api.paystack.co\",\"merchantEmail\":\"demo@gmail.com\"}', '2022-04-16 08:04:29', '2022-04-19 06:29:56'),
(42, 'bkash', '{\"status\":\"0\",\"api_key\":null,\"api_secret\":null,\"username\":null,\"password\":null}', NULL, NULL),
(43, 'paymob', '{\"status\":\"0\",\"api_key\":\"==\",\"iframe_id\":\"\",\"integration_id\":\"\",\"hmac\":\"\"}', NULL, NULL),
(44, 'mercadopago', '{\"status\":\"0\",\"public_key\":\"\",\"access_token\":\"\"}', NULL, NULL),
(45, 'flutterwave', '{\"status\":\"0\",\"public_key\":\"\",\"secret_key\":\"\",\"hash\":\"\"}', NULL, NULL),
(46, 'senang_pay', '{\"status\":\"0\",\"secret_key\":\"\",\"merchant_id\":\"\"}', '2022-04-16 08:05:57', '2022-04-16 08:17:12'),
(47, 'app_theme', '1', NULL, NULL),
(48, 'payment_otp_verification', '1', NULL, NULL),
(49, 'hotline_number', '134679', NULL, NULL),
(50, 'merchant_commission_percent', '10', NULL, NULL),
(51, 'payment', '{\"status\":1,\"message\":\"payment done successfully.\"}', NULL, NULL),
(52, 'withdraw_charge_percent', '5', NULL, NULL),
(53, 'add_money_bonus', '{\"status\":1,\"message\":\"Added to your account with bonus.\"}', NULL, NULL),
(54, 'agent_self_registration', '1', NULL, NULL),
(55, 'maximum_otp_hit', '5', NULL, NULL),
(56, 'otp_resend_time', '60', NULL, NULL),
(57, 'temporary_block_time', '600', NULL, NULL),
(58, 'maximum_login_hit', '5', NULL, NULL),
(59, 'temporary_login_block_time', '600', NULL, NULL),
(60, 'add_money_status', '1', NULL, NULL),
(61, 'send_money_status', '1', NULL, NULL),
(62, 'cash_out_status', '1', NULL, NULL),
(63, 'send_money_request_status', '1', NULL, NULL),
(64, 'withdraw_request_status', '1', NULL, NULL),
(65, 'linked_website_status', '1', NULL, NULL),
(66, 'banner_status', '1', NULL, NULL),
(67, 'customer_add_money_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(68, 'customer_send_money_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(69, 'customer_send_money_request_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(70, 'customer_cash_out_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(71, 'customer_withdraw_request_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(72, 'agent_add_money_limit', '{\"status\":1,\"transaction_limit_per_day\":10000,\"max_amount_per_transaction\":2000,\"total_transaction_amount_per_day\":50000,\"transaction_limit_per_month\":60000,\"total_transaction_amount_per_month\":500000}', NULL, NULL),
(73, 'agent_send_money_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(74, 'agent_send_money_request_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(75, 'agent_withdraw_request_limit', '{\"status\":0,\"transaction_limit_per_day\":3,\"max_amount_per_transaction\":10,\"total_transaction_amount_per_day\":20,\"transaction_limit_per_month\":5,\"total_transaction_amount_per_month\":50}', NULL, NULL),
(76, 'favicon', '', NULL, NULL),
(77, 'twilio_sms', '{\"status\":\"1\",\"sid\":\"AC6a96436ba82d4a587adecdd137d55a9a\",\"messaging_service_sid\":\"AC6a96436ba82d4a587adecdd137d55a9a\",\"token\":\"42c90a18cacc36c865f4bb11d70fd87a\",\"from\":\"(562) 354-4249\",\"otp_template\":\"ABC\"}', '2023-11-02 11:18:20', '2023-11-02 11:18:20');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Islamabad', 165, 'active', '2023-12-14 12:31:48', '2023-12-14 12:31:48'),
(2, 'Lahore', 165, 'block', '2023-12-14 12:31:58', '2023-12-22 06:42:52'),
(3, 'Karachi', 165, 'active', '2023-12-14 12:32:10', '2023-12-14 12:32:10'),
(4, 'Copenhagon', 53, 'active', '2023-12-14 13:11:23', '2023-12-14 13:11:23'),
(5, 'Roskilde', 53, 'active', '2023-12-14 13:11:42', '2023-12-14 13:11:42'),
(6, 'Aalborg', 53, 'active', '2023-12-14 13:11:54', '2023-12-14 13:11:54'),
(7, 'Vejle', 53, 'active', '2023-12-14 13:12:09', '2023-12-14 13:12:09'),
(8, 'Sharja', 2, 'active', '2023-12-14 16:45:34', '2023-12-14 16:45:34'),
(9, 'Dubai', 2, 'active', '2023-12-20 08:04:28', '2023-12-21 15:20:53'),
(10, 'testing', 29, 'active', '2023-12-22 06:42:19', '2023-12-22 06:42:19');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Andorra', 'ad', 'active', NULL, '2023-12-13 13:38:00'),
(2, 'United Arab Emirates', 'ae', 'active', NULL, NULL),
(3, 'Afghanistan', 'af', 'active', NULL, '2023-12-13 13:38:01'),
(4, 'Antigua and Barbuda', 'ag', 'active', NULL, '2023-12-13 13:38:09'),
(5, 'Anguilla', 'ai', 'active', NULL, '2023-12-13 13:38:12'),
(6, 'Albania', 'al', 'active', NULL, '2023-12-13 13:38:11'),
(7, 'Armenia', 'am', 'active', NULL, '2023-12-13 13:38:13'),
(8, 'Netherlands Antilles', 'an', 'active', NULL, '2023-12-13 13:38:14'),
(9, 'Angola', 'ao', 'active', NULL, '2023-12-13 13:38:15'),
(10, 'Argentina', 'ar', 'active', NULL, '2023-12-13 13:38:22'),
(11, 'Austria', 'at', 'block', NULL, '2023-12-13 13:23:07'),
(12, 'Australia', 'au', 'active', NULL, '2023-12-14 07:42:44'),
(13, 'Aruba', 'aw', 'block', NULL, '2023-12-13 13:23:11'),
(14, 'Azerbaijan', 'az', 'block', NULL, '2023-12-13 13:23:11'),
(15, 'Bosnia and Herzegovina', 'ba', 'block', NULL, '2023-12-13 13:23:13'),
(16, 'Barbados', 'bb', 'block', NULL, '2023-12-13 13:23:14'),
(17, 'Bangladesh', 'bd', 'block', NULL, '2023-12-13 13:23:15'),
(18, 'Belgium', 'be', 'block', NULL, '2023-12-13 13:23:17'),
(19, 'Burkina Faso', 'bf', 'active', NULL, '2023-12-05 12:55:24'),
(20, 'Bulgaria', 'bg', 'active', NULL, '2023-12-06 07:45:47'),
(21, 'Bahrain', 'bh', 'active', NULL, '2023-12-14 07:42:29'),
(22, 'Burundi', 'bi', 'block', NULL, NULL),
(23, 'Benin', 'bj', 'block', NULL, NULL),
(24, 'Bermuda', 'bm', 'block', NULL, NULL),
(25, 'Brunei Darussalam', 'bn', 'block', NULL, NULL),
(26, 'Bolivia', 'bo', 'block', NULL, NULL),
(27, 'Brazil', 'br', 'block', NULL, NULL),
(28, 'Bahamas', 'bs', 'block', NULL, NULL),
(29, 'Bhutan', 'bt', 'active', NULL, '2023-12-22 06:41:52'),
(30, 'Botswana', 'bw', 'block', NULL, NULL),
(31, 'Belarus', 'by', 'active', NULL, '2023-12-06 07:46:05'),
(32, 'Belize', 'bz', 'active', NULL, '2023-12-06 07:46:06'),
(33, 'Canada', 'ca', 'active', NULL, '2023-12-06 07:46:07'),
(34, 'Cocos (Keeling) Islands', 'cc', 'active', NULL, '2023-12-06 07:46:08'),
(35, 'Democratic Republic of the Congo', 'cd', 'active', NULL, '2023-12-06 07:46:09'),
(36, 'Central African Republic', 'cf', 'active', NULL, '2023-12-06 07:46:11'),
(37, 'Congo', 'cg', 'active', NULL, '2023-12-06 07:46:12'),
(38, 'Switzerland', 'ch', 'active', NULL, '2023-12-06 07:46:13'),
(39, 'Cote D\'Ivoire (Ivory Coast)', 'ci', 'active', NULL, '2023-12-06 07:46:15'),
(40, 'Cook Islands', 'ck', 'active', NULL, '2023-12-06 07:46:17'),
(41, 'Chile', 'cl', 'block', NULL, NULL),
(42, 'Cameroon', 'cm', 'block', NULL, NULL),
(43, 'China', 'cn', 'block', NULL, NULL),
(44, 'Colombia', 'co', 'block', NULL, NULL),
(45, 'Costa Rica', 'cr', 'block', NULL, NULL),
(46, 'Cuba', 'cu', 'block', NULL, NULL),
(47, 'Cape Verde', 'cv', 'block', NULL, NULL),
(48, 'Christmas Island', 'cx', 'block', NULL, NULL),
(49, 'Cyprus', 'cy', 'block', NULL, NULL),
(50, 'Czech Republic', 'cz', 'block', NULL, NULL),
(51, 'Germany', 'de', 'block', NULL, NULL),
(52, 'Djibouti', 'dj', 'block', NULL, NULL),
(53, 'Denmark', 'dk', 'active', NULL, '2023-12-14 12:05:18'),
(54, 'Dominica', 'dm', 'block', NULL, NULL),
(55, 'Dominican Republic', 'do', 'block', NULL, NULL),
(56, 'Algeria', 'dz', 'block', NULL, NULL),
(57, 'Ecuador', 'ec', 'block', NULL, NULL),
(58, 'Estonia', 'ee', 'block', NULL, NULL),
(59, 'Egypt', 'eg', 'block', NULL, NULL),
(60, 'Western Sahara', 'eh', 'block', NULL, NULL),
(61, 'Eritrea', 'er', 'block', NULL, NULL),
(62, 'Spain', 'es', 'block', NULL, NULL),
(63, 'Ethiopia', 'et', 'block', NULL, NULL),
(64, 'Finland', 'fi', 'block', NULL, NULL),
(65, 'Fiji', 'fj', 'block', NULL, NULL),
(66, 'Falkland Islands (Malvinas)', 'fk', 'block', NULL, NULL),
(67, 'Federated States of Micronesia', 'fm', 'block', NULL, NULL),
(68, 'Faroe Islands', 'fo', 'block', NULL, NULL),
(69, 'France', 'fr', 'block', NULL, NULL),
(70, 'Gabon', 'ga', 'block', NULL, NULL),
(71, 'Great Britain (UK)', 'gb', 'block', NULL, NULL),
(72, 'Grenada', 'gd', 'block', NULL, NULL),
(73, 'Georgia', 'ge', 'block', NULL, NULL),
(74, 'French Guiana', 'gf', 'block', NULL, NULL),
(75, 'NULL', 'gg', 'block', NULL, NULL),
(76, 'Ghana', 'gh', 'block', NULL, NULL),
(77, 'Gibraltar', 'gi', 'block', NULL, NULL),
(78, 'Greenland', 'gl', 'block', NULL, NULL),
(79, 'Gambia', 'gm', 'block', NULL, NULL),
(80, 'Guinea', 'gn', 'block', NULL, NULL),
(81, 'Guadeloupe', 'gp', 'block', NULL, NULL),
(82, 'Equatorial Guinea', 'gq', 'block', NULL, NULL),
(83, 'Greece', 'gr', 'block', NULL, NULL),
(84, 'S. Georgia and S. Sandwich Islands', 'gs', 'block', NULL, NULL),
(85, 'Guatemala', 'gt', 'block', NULL, NULL),
(86, 'Guinea-Bissau', 'gw', 'block', NULL, NULL),
(87, 'Guyana', 'gy', 'block', NULL, NULL),
(88, 'Hong Kong', 'hk', 'block', NULL, NULL),
(89, 'Honduras', 'hn', 'block', NULL, NULL),
(90, 'Croatia (Hrvatska)', 'hr', 'block', NULL, NULL),
(91, 'Haiti', 'ht', 'block', NULL, NULL),
(92, 'Hungary', 'hu', 'block', NULL, NULL),
(93, 'Indonesia', 'id', 'block', NULL, NULL),
(94, 'Ireland', 'ie', 'block', NULL, NULL),
(95, 'Israel', 'il', 'block', NULL, NULL),
(96, 'India', 'in', 'block', NULL, NULL),
(97, 'Iraq', 'iq', 'block', NULL, NULL),
(98, 'Iran', 'ir', 'block', NULL, NULL),
(99, 'Iceland', 'is', 'block', NULL, NULL),
(100, 'Italy', 'it', 'block', NULL, NULL),
(101, 'Jamaica', 'jm', 'block', NULL, NULL),
(102, 'Jordan', 'jo', 'block', NULL, NULL),
(103, 'Japan', 'jp', 'block', NULL, NULL),
(104, 'Kenya', 'ke', 'block', NULL, NULL),
(105, 'Kyrgyzstan', 'kg', 'block', NULL, NULL),
(106, 'Cambodia', 'kh', 'block', NULL, NULL),
(107, 'Kiribati', 'ki', 'block', NULL, NULL),
(108, 'Comoros', 'km', 'block', NULL, NULL),
(109, 'Saint Kitts and Nevis', 'kn', 'block', NULL, NULL),
(110, 'Korea (North)', 'kp', 'block', NULL, NULL),
(111, 'Korea (South)', 'kr', 'block', NULL, NULL),
(112, 'Kuwait', 'kw', 'block', NULL, NULL),
(113, 'Cayman Islands', 'ky', 'block', NULL, NULL),
(114, 'Kazakhstan', 'kz', 'block', NULL, NULL),
(115, 'Laos', 'la', 'block', NULL, NULL),
(116, 'Lebanon', 'lb', 'block', NULL, NULL),
(117, 'Saint Lucia', 'lc', 'block', NULL, NULL),
(118, 'Liechtenstein', 'li', 'block', NULL, NULL),
(119, 'Sri Lanka', 'lk', 'block', NULL, NULL),
(120, 'Liberia', 'lr', 'block', NULL, NULL),
(121, 'Lesotho', 'ls', 'block', NULL, NULL),
(122, 'Lithuania', 'lt', 'block', NULL, NULL),
(123, 'Luxembourg', 'lu', 'block', NULL, NULL),
(124, 'Latvia', 'lv', 'block', NULL, NULL),
(125, 'Libya', 'ly', 'block', NULL, NULL),
(126, 'Morocco', 'ma', 'block', NULL, NULL),
(127, 'Monaco', 'mc', 'block', NULL, NULL),
(128, 'Moldova', 'md', 'block', NULL, NULL),
(129, 'Madagascar', 'mg', 'block', NULL, NULL),
(130, 'Marshall Islands', 'mh', 'block', NULL, NULL),
(131, 'Macedonia', 'mk', 'block', NULL, NULL),
(132, 'Mali', 'ml', 'block', NULL, NULL),
(133, 'Myanmar', 'mm', 'block', NULL, NULL),
(134, 'Mongolia', 'mn', 'block', NULL, NULL),
(135, 'Macao', 'mo', 'block', NULL, NULL),
(136, 'Northern Mariana Islands', 'mp', 'block', NULL, NULL),
(137, 'Martinique', 'mq', 'block', NULL, NULL),
(138, 'Mauritania', 'mr', 'block', NULL, NULL),
(139, 'Montserrat', 'ms', 'block', NULL, NULL),
(140, 'Malta', 'mt', 'block', NULL, NULL),
(141, 'Mauritius', 'mu', 'block', NULL, NULL),
(142, 'Maldives', 'mv', 'block', NULL, NULL),
(143, 'Malawi', 'mw', 'block', NULL, NULL),
(144, 'Mexico', 'mx', 'block', NULL, NULL),
(145, 'Malaysia', 'my', 'block', NULL, NULL),
(146, 'Mozambique', 'mz', 'block', NULL, NULL),
(147, 'Namibia', 'na', 'block', NULL, NULL),
(148, 'New Caledonia', 'nc', 'block', NULL, NULL),
(149, 'Niger', 'ne', 'block', NULL, NULL),
(150, 'Norfolk Island', 'nf', 'block', NULL, NULL),
(151, 'Nigeria', 'ng', 'block', NULL, NULL),
(152, 'Nicaragua', 'ni', 'block', NULL, NULL),
(153, 'Netherlands', 'nl', 'block', NULL, NULL),
(154, 'Norway', 'no', 'block', NULL, NULL),
(155, 'Nepal', 'np', 'block', NULL, NULL),
(156, 'Nauru', 'nr', 'block', NULL, NULL),
(157, 'Niue', 'nu', 'block', NULL, NULL),
(158, 'New Zealand (Aotearoa)', 'nz', 'block', NULL, NULL),
(159, 'Oman', 'om', 'block', NULL, NULL),
(160, 'Panama', 'pa', 'block', NULL, NULL),
(161, 'Peru', 'pe', 'block', NULL, NULL),
(162, 'French Polynesia', 'pf', 'block', NULL, NULL),
(163, 'Papua New Guinea', 'pg', 'block', NULL, NULL),
(164, 'Philippines', 'ph', 'block', NULL, NULL),
(165, 'Pakistan', 'pk', 'active', NULL, '2023-11-28 06:02:15'),
(166, 'Poland', 'pl', 'block', NULL, NULL),
(167, 'Saint Pierre and Miquelon', 'pm', 'block', NULL, NULL),
(168, 'Pitcairn', 'pn', 'block', NULL, NULL),
(169, 'Palestinian Territory', 'ps', 'block', NULL, NULL),
(170, 'Portugal', 'pt', 'block', NULL, NULL),
(171, 'Palau', 'pw', 'block', NULL, NULL),
(172, 'Paraguay', 'py', 'block', NULL, NULL),
(173, 'Qatar', 'qa', 'block', NULL, NULL),
(174, 'Reunion', 're', 'block', NULL, NULL),
(175, 'Romania', 'ro', 'block', NULL, NULL),
(176, 'Russian Federation', 'ru', 'block', NULL, NULL),
(177, 'Rwanda', 'rw', 'block', NULL, NULL),
(178, 'Saudi Arabia', 'sa', 'block', NULL, NULL),
(179, 'Solomon Islands', 'sb', 'block', NULL, NULL),
(180, 'Seychelles', 'sc', 'block', NULL, NULL),
(181, 'Sudan', 'sd', 'block', NULL, NULL),
(182, 'Sweden', 'se', 'block', NULL, NULL),
(183, 'Singapore', 'sg', 'block', NULL, NULL),
(184, 'Saint Helena', 'sh', 'block', NULL, NULL),
(185, 'Slovenia', 'si', 'block', NULL, NULL),
(186, 'Svalbard and Jan Mayen', 'sj', 'block', NULL, NULL),
(187, 'Slovakia', 'sk', 'block', NULL, NULL),
(188, 'Sierra Leone', 'sl', 'block', NULL, NULL),
(189, 'San Marino', 'sm', 'block', NULL, NULL),
(190, 'Senegal', 'sn', 'block', NULL, NULL),
(191, 'Somalia', 'so', 'block', NULL, NULL),
(192, 'Suriname', 'sr', 'block', NULL, NULL),
(193, 'Sao Tome and Principe', 'st', 'block', NULL, NULL),
(194, 'El Salvador', 'sv', 'block', NULL, NULL),
(195, 'Syria', 'sy', 'block', NULL, NULL),
(196, 'Swaziland', 'sz', 'block', NULL, NULL),
(197, 'Turks and Caicos Islands', 'tc', 'block', NULL, NULL),
(198, 'Chad', 'td', 'block', NULL, NULL),
(199, 'French Southern Territories', 'tf', 'block', NULL, NULL),
(200, 'Togo', 'tg', 'block', NULL, NULL),
(201, 'Thailand', 'th', 'block', NULL, NULL),
(202, 'Tajikistan', 'tj', 'block', NULL, NULL),
(203, 'Tokelau', 'tk', 'block', NULL, NULL),
(204, 'Turkmenistan', 'tm', 'block', NULL, NULL),
(205, 'Tunisia', 'tn', 'block', NULL, NULL),
(206, 'Tonga', 'to', 'block', NULL, NULL),
(207, 'Turkey', 'tr', 'block', NULL, NULL),
(208, 'Trinidad and Tobago', 'tt', 'block', NULL, NULL),
(209, 'Tuvalu', 'tv', 'block', NULL, NULL),
(210, 'Taiwan', 'tw', 'block', NULL, NULL),
(211, 'Tanzania', 'tz', 'block', NULL, NULL),
(212, 'Ukraine', 'ua', 'block', NULL, NULL),
(213, 'Uganda', 'ug', 'block', NULL, NULL),
(214, 'Uruguay', 'uy', 'block', NULL, NULL),
(215, 'Uzbekistan', 'uz', 'block', NULL, NULL),
(216, 'Saint Vincent and the Grenadines', 'vc', 'block', NULL, NULL),
(217, 'Venezuela', 've', 'block', NULL, NULL),
(218, 'Virgin Islands (British)', 'vg', 'block', NULL, NULL),
(219, 'Virgin Islands (U.S.)', 'vi', 'block', NULL, NULL),
(220, 'Viet Nam', 'vn', 'block', NULL, NULL),
(221, 'Vanuatu', 'vu', 'block', NULL, NULL),
(222, 'Wallis and Futuna', 'wf', 'block', NULL, NULL),
(223, 'Samoa', 'ws', 'block', NULL, NULL),
(224, 'Yemen', 'ye', 'block', NULL, NULL),
(225, 'Mayotte', 'yt', 'block', NULL, NULL),
(226, 'South Africa', 'za', 'block', NULL, NULL),
(227, 'Zambia', 'zm', 'block', NULL, NULL),
(228, 'Zaire (former)', 'zr', 'block', NULL, NULL),
(229, 'Zimbabwe', 'zw', 'block', NULL, NULL),
(230, 'United States of America', 'us', 'active', NULL, '2023-12-13 13:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `currency_code` varchar(255) DEFAULT NULL,
  `currency_symbol` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(8,2) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency_code`, `currency_symbol`, `exchange_rate`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', '1.00', 230, NULL, NULL),
(2, 'Canadian Dollar', 'CAD', 'CA$', '5.00', 33, NULL, '2023-12-05 13:04:38'),
(3, 'Euro', 'EUR', '€', '1.00', NULL, NULL, NULL),
(4, 'United Arab Emirates Dirham', 'AED', 'د.إ.‏', '1.00', 2, NULL, NULL),
(5, 'Afghan Afghani', 'AFN', '؋', '10.00', 3, NULL, '2023-12-05 13:10:09'),
(6, 'Albanian Lek', 'ALL', 'L', '1.00', 6, NULL, NULL),
(7, 'Armenian Dram', 'AMD', '֏', '1.00', 7, NULL, NULL),
(8, 'Argentine Peso', 'ARS', '$', '1.00', 10, NULL, NULL),
(9, 'Australian Dollar', 'AUD', '$', '1.00', 12, NULL, NULL),
(10, 'Azerbaijani Manat', 'AZN', '₼', '15.00', 14, NULL, '2023-12-05 13:10:32'),
(11, 'Bosnia-Herzegovina Convertible Mark', 'BAM', 'KM', '1.00', 15, NULL, NULL),
(12, 'Bangladeshi Taka', 'BDT', '৳', '1.00', 17, NULL, NULL),
(13, 'Bulgarian Lev', 'BGN', 'лв.', '1.00', 20, NULL, NULL),
(14, 'Bahraini Dinar', 'BHD', 'د.ب.‏', '1.00', 21, NULL, NULL),
(15, 'Burundian Franc', 'BIF', 'FBu', '1.00', 22, NULL, NULL),
(16, 'Brunei Dollar', 'BND', 'B$', '1.00', 25, NULL, NULL),
(17, 'Bolivian Boliviano', 'BOB', 'Bs', '1.00', 26, NULL, NULL),
(18, 'Brazilian Real', 'BRL', 'R$', '1.00', 27, NULL, NULL),
(19, 'Botswanan Pula', 'BWP', 'P', '1.00', 30, NULL, NULL),
(20, 'Belarusian Ruble', 'BYN', 'Br', '1.00', 31, NULL, NULL),
(21, 'Belize Dollar', 'BZD', '$', '1.00', 32, NULL, NULL),
(22, 'Congolese Franc', 'CDF', 'FC', '1.00', 37, NULL, NULL),
(23, 'Swiss Franc', 'CHF', 'CHf', '1.00', 38, NULL, NULL),
(24, 'Chilean Peso', 'CLP', '$', '1.00', 41, NULL, NULL),
(25, 'Chinese Yuan', 'CNY', '¥', '1.00', 43, NULL, NULL),
(26, 'Colombian Peso', 'COP', '$', '1.00', 44, NULL, NULL),
(27, 'Costa Rican Colón', 'CRC', '₡', '1.00', 45, NULL, NULL),
(28, 'Cape Verdean Escudo', 'CVE', '$', '1.00', 47, NULL, NULL),
(29, 'Czech Republic Koruna', 'CZK', 'Kč', '1.00', 50, NULL, NULL),
(30, 'Djiboutian Franc', 'DJF', 'Fdj', '1.00', 52, NULL, NULL),
(31, 'Danish Krone', 'DKK', 'Kr.', '1.00', 53, NULL, NULL),
(32, 'Dominican Peso', 'DOP', 'RD$', '1.00', 54, NULL, NULL),
(33, 'Algerian Dinar', 'DZD', 'د.ج.‏', '1.00', 56, NULL, NULL),
(34, 'Estonian Kroon', 'EEK', 'kr', '1.00', 58, NULL, NULL),
(35, 'Egyptian Pound', 'EGP', 'E£‏', '1.00', 59, NULL, NULL),
(36, 'Eritrean Nakfa', 'ERN', 'Nfk', '1.00', NULL, NULL, NULL),
(37, 'Ethiopian Birr', 'ETB', 'Br', '1.00', NULL, NULL, NULL),
(38, 'British Pound Sterling', 'GBP', '£', '1.00', NULL, NULL, NULL),
(39, 'Georgian Lari', 'GEL', 'GEL', '1.00', NULL, NULL, NULL),
(40, 'Ghanaian Cedi', 'GHS', 'GH¢', '1.00', NULL, NULL, NULL),
(41, 'Guinean Franc', 'GNF', 'FG', '1.00', NULL, NULL, NULL),
(42, 'Guatemalan Quetzal', 'GTQ', 'Q', '1.00', NULL, NULL, NULL),
(43, 'Hong Kong Dollar', 'HKD', 'HK$', '1.00', NULL, NULL, NULL),
(44, 'Honduran Lempira', 'HNL', 'L', '1.00', NULL, NULL, NULL),
(45, 'Croatian Kuna', 'HRK', 'kn', '1.00', NULL, NULL, NULL),
(46, 'Hungarian Forint', 'HUF', 'Ft', '1.00', NULL, NULL, NULL),
(47, 'Indonesian Rupiah', 'IDR', 'Rp', '1.00', NULL, NULL, NULL),
(48, 'Israeli New Sheqel', 'ILS', '₪', '1.00', NULL, NULL, NULL),
(49, 'Indian Rupee', 'INR', '₹', '1.00', NULL, NULL, NULL),
(50, 'Iraqi Dinar', 'IQD', 'ع.د', '1.00', NULL, NULL, NULL),
(51, 'Iranian Rial', 'IRR', '﷼', '1.00', NULL, NULL, NULL),
(52, 'Icelandic Króna', 'ISK', 'kr', '1.00', NULL, NULL, NULL),
(53, 'Jamaican Dollar', 'JMD', '$', '1.00', NULL, NULL, NULL),
(54, 'Jordanian Dinar', 'JOD', 'د.ا‏', '1.00', NULL, NULL, NULL),
(55, 'Japanese Yen', 'JPY', '¥', '1.00', NULL, NULL, NULL),
(56, 'Kenyan Shilling', 'KES', 'Ksh', '1.00', NULL, NULL, NULL),
(57, 'Cambodian Riel', 'KHR', '៛', '1.00', NULL, NULL, NULL),
(58, 'Comorian Franc', 'KMF', 'FC', '1.00', NULL, NULL, NULL),
(59, 'South Korean Won', 'KRW', 'CF', '1.00', NULL, NULL, NULL),
(60, 'Kuwaiti Dinar', 'KWD', 'د.ك.‏', '1.00', NULL, NULL, NULL),
(61, 'Kazakhstani Tenge', 'KZT', '₸.', '1.00', NULL, NULL, NULL),
(62, 'Lebanese Pound', 'LBP', 'ل.ل.‏', '1.00', NULL, NULL, NULL),
(63, 'Sri Lankan Rupee', 'LKR', 'Rs', '1.00', NULL, NULL, NULL),
(64, 'Lithuanian Litas', 'LTL', 'Lt', '1.00', NULL, NULL, NULL),
(65, 'Latvian Lats', 'LVL', 'Ls', '1.00', NULL, NULL, NULL),
(66, 'Libyan Dinar', 'LYD', 'د.ل.‏', '1.00', NULL, NULL, NULL),
(67, 'Moroccan Dirham', 'MAD', 'د.م.‏', '1.00', NULL, NULL, NULL),
(68, 'Moldovan Leu', 'MDL', 'L', '1.00', NULL, NULL, NULL),
(69, 'Malagasy Ariary', 'MGA', 'Ar', '1.00', NULL, NULL, NULL),
(70, 'Macedonian Denar', 'MKD', 'Ден', '1.00', NULL, NULL, NULL),
(71, 'Myanma Kyat', 'MMK', 'K', '1.00', NULL, NULL, NULL),
(72, 'Macanese Pataca', 'MOP', 'MOP$', '1.00', NULL, NULL, NULL),
(73, 'Mauritian Rupee', 'MUR', 'Rs', '1.00', NULL, NULL, NULL),
(74, 'Mexican Peso', 'MXN', '$', '1.00', NULL, NULL, NULL),
(75, 'Malaysian Ringgit', 'MYR', 'RM', '1.00', NULL, NULL, NULL),
(76, 'Mozambican Metical', 'MZN', 'MT', '1.00', NULL, NULL, NULL),
(77, 'Namibian Dollar', 'NAD', 'N$', '1.00', NULL, NULL, NULL),
(78, 'Nigerian Naira', 'NGN', '₦', '1.00', NULL, NULL, NULL),
(79, 'Nicaraguan Córdoba', 'NIO', 'C$', '1.00', NULL, NULL, NULL),
(80, 'Norwegian Krone', 'NOK', 'kr', '1.00', NULL, NULL, NULL),
(81, 'Nepalese Rupee', 'NPR', 'Re.', '1.00', NULL, NULL, NULL),
(82, 'New Zealand Dollar', 'NZD', '$', '1.00', NULL, NULL, NULL),
(83, 'Omani Rial', 'OMR', 'ر.ع.‏', '1.00', NULL, NULL, NULL),
(84, 'Panamanian Balboa', 'PAB', 'B/.', '1.00', NULL, NULL, NULL),
(85, 'Peruvian Nuevo Sol', 'PEN', 'S/', '1.00', NULL, NULL, NULL),
(86, 'Philippine Peso', 'PHP', '₱', '1.00', NULL, NULL, NULL),
(87, 'Pakistani Rupee', 'PKR', 'Rs', '275.00', 165, NULL, NULL),
(88, 'Polish Zloty', 'PLN', 'zł', '1.00', NULL, NULL, NULL),
(89, 'Paraguayan Guarani', 'PYG', '₲', '1.00', NULL, NULL, NULL),
(90, 'Qatari Rial', 'QAR', 'ر.ق.‏', '1.00', NULL, NULL, NULL),
(91, 'Romanian Leu', 'RON', 'lei', '1.00', NULL, NULL, NULL),
(92, 'Serbian Dinar', 'RSD', 'din.', '1.00', NULL, NULL, NULL),
(93, 'Russian Ruble', 'RUB', '₽.', '1.00', NULL, NULL, NULL),
(94, 'Rwandan Franc', 'RWF', 'FRw', '1.00', NULL, NULL, NULL),
(95, 'Saudi Riyal', 'SAR', 'ر.س.‏', '1.00', NULL, NULL, NULL),
(96, 'Sudanese Pound', 'SDG', 'ج.س.', '1.00', NULL, NULL, NULL),
(97, 'Swedish Krona', 'SEK', 'kr', '1.00', NULL, NULL, NULL),
(98, 'Singapore Dollar', 'SGD', '$', '1.00', NULL, NULL, NULL),
(99, 'Somali Shilling', 'SOS', 'Sh.so.', '1.00', NULL, NULL, NULL),
(100, 'Syrian Pound', 'SYP', 'LS‏', '1.00', NULL, NULL, NULL),
(101, 'Thai Baht', 'THB', '฿', '1.00', NULL, NULL, NULL),
(102, 'Tunisian Dinar', 'TND', 'د.ت‏', '1.00', NULL, NULL, NULL),
(103, 'Tongan Paʻanga', 'TOP', 'T$', '1.00', NULL, NULL, NULL),
(104, 'Turkish Lira', 'TRY', '₺', '1.00', NULL, NULL, NULL),
(105, 'Trinidad and Tobago Dollar', 'TTD', '$', '1.00', NULL, NULL, NULL),
(106, 'New Taiwan Dollar', 'TWD', 'NT$', '1.00', NULL, NULL, NULL),
(107, 'Tanzanian Shilling', 'TZS', 'TSh', '1.00', NULL, NULL, NULL),
(108, 'Ukrainian Hryvnia', 'UAH', '₴', '1.00', NULL, NULL, NULL),
(109, 'Ugandan Shilling', 'UGX', 'USh', '1.00', NULL, NULL, NULL),
(110, 'Uruguayan Peso', 'UYU', '$', '1.00', NULL, NULL, NULL),
(111, 'Uzbekistan Som', 'UZS', 'so\'m', '1.00', NULL, NULL, NULL),
(112, 'Venezuelan Bolívar', 'VEF', 'Bs.F.', '1.00', NULL, NULL, NULL),
(113, 'Vietnamese Dong', 'VND', '₫', '1.00', NULL, NULL, NULL),
(114, 'CFA Franc BEAC', 'XAF', 'FCFA', '1.00', NULL, NULL, NULL),
(115, 'CFA Franc BCEAO', 'XOF', 'CFA', '1.00', NULL, NULL, NULL),
(116, 'Yemeni Rial', 'YER', '﷼‏', '1.00', NULL, NULL, NULL),
(117, 'South African Rand', 'ZAR', 'R', '1.00', NULL, NULL, NULL),
(118, 'Zambian Kwacha', 'ZMK', 'ZK', '1.00', NULL, NULL, NULL),
(119, 'Zimbabwean Dollar', 'ZWL', 'Z$', '1.00', NULL, NULL, NULL),
(120, 'Pakistani Rupee', 'PKR', 'Rs', '1.00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `type` enum('sender','receiver') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Tayyab', '+923032132132', 'sender', '2023-12-20 07:17:18', '2023-12-20 07:17:18'),
(2, 'Manan', '+923032132133', 'receiver', '2023-12-20 07:17:18', '2023-12-20 07:17:18'),
(3, 'Tayyab', '+2633032132132', 'sender', '2023-12-20 07:59:37', '2023-12-20 07:59:37'),
(4, 'Manan', '+2633032132133', 'receiver', '2023-12-20 07:59:37', '2023-12-20 07:59:37'),
(5, 'Tayyab', '+923032132132', 'sender', '2023-12-20 08:14:49', '2023-12-20 08:14:49'),
(6, 'Manan', '+923032132133', 'receiver', '2023-12-20 08:14:49', '2023-12-20 08:14:49'),
(7, 'Tayyab', '+2633032132132', 'sender', '2023-12-20 08:15:25', '2023-12-20 08:15:25'),
(8, 'Manan', '+2633032132133', 'receiver', '2023-12-20 08:15:25', '2023-12-20 08:15:25'),
(9, 'Tayyab', '+923032132132', 'sender', '2023-12-20 08:23:34', '2023-12-20 08:23:34'),
(10, 'Manan', '+923032132133', 'receiver', '2023-12-20 08:23:34', '2023-12-20 08:23:34'),
(11, 'Tayyab', '+2633032132132', 'sender', '2023-12-20 08:42:25', '2023-12-20 08:42:25'),
(12, 'Manan', '+2633032132133', 'receiver', '2023-12-20 08:42:25', '2023-12-20 08:42:25'),
(13, 'Tayyab', '+2633032132132', 'sender', '2023-12-20 09:38:26', '2023-12-20 09:38:26'),
(14, 'Manan', '+2633032132133', 'receiver', '2023-12-20 09:38:26', '2023-12-20 09:38:26'),
(15, 'Tayyab', '+2633032132132', 'sender', '2023-12-21 02:02:37', '2023-12-21 02:02:37'),
(16, 'Manan', '+2633032132133', 'receiver', '2023-12-21 02:02:37', '2023-12-21 02:02:37'),
(17, 'Tahir Ka Beta', '+2633032132132', 'sender', '2023-12-21 02:11:33', '2023-12-21 02:11:33'),
(18, 'Manan', '+2633032132133', 'receiver', '2023-12-21 02:11:33', '2023-12-21 02:11:33'),
(19, 'Nasir', '+923032132132', 'sender', '2023-12-21 03:30:10', '2023-12-21 03:30:10'),
(20, 'Arslan', '+923032132133', 'receiver', '2023-12-21 03:30:10', '2023-12-21 03:30:10'),
(21, 'Nasir', '+923032132132', 'sender', '2023-12-21 03:35:24', '2023-12-21 03:35:24'),
(22, 'Arslan', '+923032132133', 'receiver', '2023-12-21 03:35:24', '2023-12-21 03:35:24'),
(23, 'Hamid', '+923032132132', 'sender', '2023-12-21 03:40:30', '2023-12-21 03:40:30'),
(24, 'Momin', '+923032132133', 'receiver', '2023-12-21 03:40:30', '2023-12-21 03:40:30');

-- --------------------------------------------------------

--
-- Table structure for table `e_money`
--

CREATE TABLE `e_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `current_balance` double(14,2) NOT NULL DEFAULT 0.00,
  `charge_earned` double(14,2) NOT NULL DEFAULT 0.00,
  `pending_balance` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `e_money`
--

INSERT INTO `e_money` (`id`, `user_id`, `current_balance`, `charge_earned`, `pending_balance`, `created_at`, `updated_at`) VALUES
(1, 1, 15051535.14, 143.15, 10, '2023-09-21 11:59:46', '2023-12-22 12:43:25'),
(2, 2, 0.00, 0.00, 0, '2023-09-22 23:31:46', '2023-09-22 23:31:46'),
(3, 3, 0.00, 0.00, 0, '2023-10-18 13:43:41', '2023-10-18 13:43:41'),
(4, 4, 0.00, 0.00, 0, '2023-10-23 18:18:31', '2023-10-23 18:18:31'),
(5, 5, 0.00, 0.00, 0, '2023-10-24 07:54:08', '2023-10-24 07:54:08'),
(6, 6, 0.00, 0.00, 0, '2023-10-25 14:10:04', '2023-10-25 14:10:04'),
(7, 7, 0.00, 0.00, 0, '2023-10-26 07:09:04', '2023-10-26 07:09:04'),
(8, 8, 1061.35, 5000.00, 11563.65, '2023-10-26 10:25:50', '2023-11-02 06:34:17'),
(9, 9, 0.00, 0.00, 0, '2023-11-02 07:06:09', '2023-11-02 07:06:09'),
(10, 10, 50010.00, 2000.00, 0, '2023-11-02 07:09:51', '2023-11-02 07:19:31'),
(11, 11, 48685.00, 2000.00, 1365, '2023-11-02 07:30:25', '2023-11-02 10:22:33'),
(12, 12, 0.95, 0.00, 1.05, '2023-11-02 08:26:24', '2023-11-02 08:44:47'),
(13, 13, 0.00, 0.00, 0, '2023-11-02 10:39:12', '2023-11-02 10:39:12'),
(14, 14, 44026.55, 2000.00, 5406.45, '2023-11-02 11:03:03', '2023-11-10 10:22:22'),
(15, 15, 1027.00, 0.00, 0, '2023-11-02 14:10:27', '2023-11-10 10:20:19'),
(16, 16, 22471.00, 0.00, 0, '2023-12-05 11:02:52', '2023-12-15 06:39:47'),
(17, 17, 500.00, 0.00, 0, '2023-12-14 07:34:53', '2023-12-15 07:07:57'),
(18, 18, 12.00, 0.00, 0, '2023-12-14 07:50:54', '2023-12-17 11:06:56'),
(19, 19, 0.00, 0.00, 0, '2023-12-14 07:54:54', '2023-12-14 07:54:54'),
(20, 20, 0.00, 0.00, 0, '2023-12-14 07:59:39', '2023-12-14 07:59:39'),
(21, 21, 0.00, 0.00, 0, '2023-12-14 08:01:16', '2023-12-14 08:01:16'),
(22, 22, 1050.18, 0.00, 0, '2023-12-15 10:39:44', '2023-12-15 11:15:48'),
(23, 23, 0.00, 0.00, 0, '2023-12-18 13:59:43', '2023-12-18 13:59:43'),
(24, 24, 0.00, 0.00, 0, '2023-12-18 14:40:44', '2023-12-18 14:40:44'),
(25, 25, 0.00, 0.00, 0, '2023-12-20 08:05:00', '2023-12-20 08:05:00'),
(26, 26, 0.00, 0.00, 0, '2023-12-21 07:52:20', '2023-12-21 07:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fee` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `fee`, `created_at`, `updated_at`) VALUES
(1, 5, '2023-11-27 09:55:58', '2023-11-28 12:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `funds`
--

CREATE TABLE `funds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `funds`
--

INSERT INTO `funds` (`id`, `user_id`, `amount`, `payment_method`, `tran_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 200.00, 'stripe', NULL, 'success', '2023-10-26 12:11:24', '2023-10-26 12:11:24'),
(2, 8, 500.00, 'stripe', NULL, 'failed', '2023-10-27 10:55:51', '2023-10-27 10:55:51'),
(3, 8, 500.00, 'stripe', NULL, 'failed', '2023-10-27 10:56:37', '2023-10-27 10:56:37'),
(4, 8, 100.00, 'stripe', NULL, 'failed', '2023-10-27 10:57:39', '2023-10-27 10:57:39'),
(5, 8, 200.00, 'stripe', NULL, 'success', '2023-10-31 08:38:05', '2023-10-31 08:38:05'),
(6, 8, 124.00, 'stripe', NULL, 'success', '2023-10-31 10:37:26', '2023-10-31 10:37:26'),
(7, 8, 1.00, 'stripe', NULL, 'success', '2023-11-01 17:38:44', '2023-11-01 17:38:44'),
(8, 10, 10.00, 'stripe', NULL, 'success', '2023-11-02 07:19:31', '2023-11-02 07:19:31'),
(9, 11, 10.00, 'stripe', NULL, 'success', '2023-11-02 07:33:10', '2023-11-02 07:33:10'),
(10, 11, 20.00, 'stripe', NULL, 'success', '2023-11-02 07:49:53', '2023-11-02 07:49:53'),
(11, 12, 2.00, 'stripe', NULL, 'success', '2023-11-02 08:42:29', '2023-11-02 08:42:29'),
(12, 11, 20.00, 'stripe', NULL, 'success', '2023-11-02 10:21:41', '2023-11-02 10:21:41'),
(13, 16, 1855.00, 'not specified', NULL, 'success', '2023-12-05 11:37:16', '2023-12-05 11:37:16'),
(14, 16, 500.00, 'not specified', NULL, 'success', '2023-12-05 11:38:16', '2023-12-05 11:38:16'),
(15, 16, 1000.00, 'not specified', NULL, 'success', '2023-12-05 11:38:54', '2023-12-05 11:38:54'),
(16, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-05 11:40:08', '2023-12-05 11:40:08'),
(17, 16, 1.00, 'not specified', NULL, 'success', '2023-12-05 11:51:59', '2023-12-05 11:51:59'),
(18, 16, 1.00, 'not specified', NULL, 'success', '2023-12-05 11:51:59', '2023-12-05 11:51:59'),
(19, 16, 1.00, 'not specified', NULL, 'success', '2023-12-05 11:51:59', '2023-12-05 11:51:59'),
(20, 16, 1.00, 'not specified', NULL, 'success', '2023-12-05 11:52:00', '2023-12-05 11:52:00'),
(21, 16, 500.00, 'not specified', NULL, 'success', '2023-12-05 11:56:28', '2023-12-05 11:56:28'),
(22, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-05 11:56:34', '2023-12-05 11:56:34'),
(23, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-14 11:18:51', '2023-12-14 11:18:51'),
(24, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-14 11:18:52', '2023-12-14 11:18:52'),
(25, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-14 11:18:53', '2023-12-14 11:18:53'),
(26, 16, 2000.00, 'not specified', NULL, 'success', '2023-12-15 06:39:43', '2023-12-15 06:39:43'),
(27, 16, 1300.00, 'not specified', NULL, 'success', '2023-12-15 06:39:48', '2023-12-15 06:39:48'),
(28, 22, 1000.00, 'not specified', NULL, 'success', '2023-12-15 11:13:43', '2023-12-15 11:13:43'),
(29, 18, 12.00, 'not specified', NULL, 'success', '2023-12-17 11:06:56', '2023-12-17 11:06:56');

-- --------------------------------------------------------

--
-- Table structure for table `help_topics`
--

CREATE TABLE `help_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `ranking` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `linked_websites`
--

CREATE TABLE `linked_websites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linked_websites`
--

INSERT INTO `linked_websites` (`id`, `name`, `image`, `url`, `status`, `created_at`, `updated_at`) VALUES
(5, 'amazon', '2023-11-02-654366286c3e0.png', 'https://amazon.com', 1, '2023-11-02 09:04:40', '2023-11-02 09:04:40'),
(6, 'Test', '2023-11-02-654366407ae21.png', 'https://www.google.com', 1, '2023-11-02 09:05:04', '2023-11-02 09:05:04'),
(7, 'Wajid', '2023-11-02-65436650ee5f6.png', 'https://www.kfc.com', 1, '2023-11-02 09:05:20', '2023-11-02 09:05:20'),
(8, 'Cypress', '2023-11-02-6543665ea6423.png', 'https://www.google.com', 1, '2023-11-02 09:05:34', '2023-11-02 09:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `callback` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `bin` varchar(255) DEFAULT NULL,
  `public_key` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `merchant_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(241, '2014_10_12_000000_create_users_table', 1),
(242, '2014_10_12_100000_create_password_resets_table', 1),
(243, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(244, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(245, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(246, '2016_06_01_000004_create_oauth_clients_table', 1),
(247, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(248, '2019_08_19_000000_create_failed_jobs_table', 1),
(249, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(250, '2021_11_18_104105_create_business_settings_table', 1),
(251, '2021_11_20_090531_create_currencies_table', 1),
(252, '2021_11_22_065212_add_last_active_at_to_users_table', 1),
(253, '2021_11_23_090107_create_linked_websites_table', 1),
(254, '2021_11_23_104425_add_reference_columns_to_users_table', 1),
(255, '2021_11_23_123056_create_notifications_table', 1),
(256, '2021_11_27_041913_create_phone_verifications_table', 1),
(257, '2021_11_27_052236_add_columns_to_users_table', 1),
(258, '2021_11_29_100204_create_transfers_table', 1),
(259, '2021_12_01_053955_create_transactions_table', 1),
(260, '2021_12_01_063108_create_e_money_table', 1),
(261, '2021_12_04_113130_create_request_money_table', 1),
(262, '2021_12_05_051247_create_funds_table', 1),
(263, '2021_12_06_101224_create_purposes_table', 1),
(264, '2021_12_14_104755_add_note_column_to_transaction', 1),
(265, '2021_12_19_071059_add_twofactor_and_fcmtoken_to_users_table', 1),
(266, '2021_12_21_110529_create_banners_table', 1),
(267, '2021_12_22_121505_add_receiver_column_to_notifications', 1),
(268, '2021_12_26_061202_create_help_topics_table', 1),
(269, '2022_02_01_041254_add_transaction_i_d_to_transactions', 1),
(270, '2022_02_01_065231_type_change_of_ref_trans_id_to_transactions', 1),
(271, '2022_04_07_045435_add_receiver_to_banner_table', 2),
(272, '2022_04_07_060244_add_is_active_column_to_to_users_table', 3),
(273, '2021_06_17_054551_create_soft_credentials_table', 4),
(274, '2022_06_30_051435_add_column_to_user_table', 5),
(275, '2022_07_05_102531_change_data_type_of_transfer_table', 6),
(276, '2022_10_16_063545_create_withdrawal_methods_table', 7),
(277, '2022_10_18_040302_create_withdraw_requests_table', 7),
(278, '2022_10_18_141838_create_user_log_histories_table', 7),
(279, '2022_11_08_055006_change_default_kyc_status', 7),
(280, '2022_12_08_045549_create_merchants_table', 8),
(281, '2022_12_11_050638_create_payment_records_table', 8),
(282, '2022_12_21_041139_add_column_dail_country_code_to_users_table', 8),
(283, '2022_12_26_122524_add_expired_at_column_in_payment_records_table', 8),
(284, '2023_01_23_065548_add_pending_balance_in_e_money_table', 8),
(285, '2023_03_25_082756_create_bonuses_table', 9),
(286, '2023_03_29_085117_add_col_to_withdraw_requests_table', 9),
(287, '2023_04_03_030436_add_column_to_transactions_table', 9),
(288, '2023_05_11_084421_change_notifications_table_column_type', 10),
(289, '2023_05_15_153550_add_otp_hist_counts_column_in_phone_verification_tabel', 10),
(290, '2023_05_25_083248_add_multiple_column_to_password_resets', 10),
(291, '2023_05_25_083248_add_multiple_column_to_users', 10),
(292, '2023_05_28_085211_create_transaction_limits_table', 10),
(293, '2023_05_31_051107_add_soft_delete_in_users', 10);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receiver` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('101948455ef3cd15f74305328137bdeb0176d5603d19c1264313b8c5179fbbbbd22d868015f3c1a4', 15, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 15:00:45', '2023-11-02 15:00:45', '2023-11-02 15:40:45'),
('138eccbedee19b87ec9540767a600cfc76857bd27de8ecd31569a1642981afcaa5c94328d8da68b8', 7, 3, 'AgentAuthToken', '[]', 0, '2023-10-26 10:19:16', '2023-10-26 10:19:16', '2023-10-26 11:59:16'),
('1b8beee28b0b24323f6cedd4c14b85ce6062f03a1db0d5b1bf6ecdcf6fbdaabd5f3d3d45e0a4c939', 4, 3, 'AgentAuthToken', '[]', 0, '2023-10-24 06:43:11', '2023-10-24 06:43:11', '2023-10-24 08:23:11'),
('3184b85288051a55e459a430fda5c19def53fc26e0e3efaad4c6f4754af94bdcc73c234e5acb8639', 3, 3, 'AgentAuthToken', '[]', 0, '2023-10-24 06:19:41', '2023-10-24 06:19:41', '2023-10-24 07:59:41'),
('42f7f02531635dbf68b2e5b0124a774f8bc5521111d7153ffcea25a93a409fdeb0b5b9ef2695551b', 12, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 10:26:32', '2023-11-02 10:26:32', '2023-11-02 11:06:32'),
('4579f5b9b79a516f9b0d9d7982b9ebd6e555f8ab841a3269f6f048289616dc729a15dc2e0f6dd202', 8, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 06:53:44', '2023-11-02 06:53:44', '2023-11-02 07:33:44'),
('45ce064952bc750f2ecdf987634f003156c2e14c1d85387714d53a6917e219b3d9a05c18b9d2feff', 14, 3, 'AgentAuthToken', '[]', 0, '2023-12-01 11:08:29', '2023-12-01 11:08:29', '2023-12-01 11:48:29'),
('587405fbb72bfa6930d67b039a49047b79d5054db5c9d3dd3fb99c23fd599bb6a89249027be5bb7a', 11, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 10:34:58', '2023-11-02 10:34:58', '2023-11-02 11:14:58'),
('7443d5e9532483ec8af863e90461512d54d329aed4ca44e6d0005baaa1bb2145e6e01204742f16e7', 5, 3, 'AgentAuthToken', '[]', 0, '2023-10-25 13:20:52', '2023-10-25 13:20:52', '2023-10-25 15:00:52'),
('b62bc9c728d82d9f31da191b50b3b1d4dce2debe5d670d916cb6c24230242e42d94e5698b25ab0f0', 10, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 07:22:28', '2023-11-02 07:22:28', '2023-11-02 08:02:28'),
('ba6e92586907afc794bec8203dc2936e3cd424e92029df6640cbbaeab2a634307de24700be865c1b', 16, 3, 'AgentAuthToken', '[]', 0, '2023-12-08 11:12:20', '2023-12-08 11:12:20', '2023-12-08 11:52:20'),
('c8986c9a4e772f6923fb516081e32c3fa363bcd24841489937f9a8cdc9c028c7388ec8a8b0cb5e78', 6, 3, 'AgentAuthToken', '[]', 0, '2023-10-31 11:50:44', '2023-10-31 11:50:44', '2023-10-31 12:30:44'),
('e658d910349935c81f91cbe671da54a08cf615607533c9c88ef5ebcb4f538c9820044bba150bbbe4', 9, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 07:06:26', '2023-11-02 07:06:26', '2023-11-02 07:46:26'),
('ed1bda370d11967116eef75248d05eea1022c17abff2f0ac2478024aea689461997f9b959f60f0a9', 13, 3, 'AgentAuthToken', '[]', 0, '2023-11-02 10:39:36', '2023-11-02 10:39:36', '2023-11-02 11:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'bR3oc2ab81JlHVMcsIXdFcAhBuXQTipmcIcd7Fno', NULL, 'http://localhost', 1, 0, 0, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(2, NULL, 'Laravel Password Grant Client', 'UhJDtulOAo0vdfuaHCtQ4KT4QOiYDdmImM9x48Ax', 'users', 'http://localhost', 0, 1, 0, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(3, NULL, 'Laravel Personal Access Client', 'HpFYsWP7yU6grzZOwpsWP1oACFUuOwMHHMGuqYgl', NULL, 'http://localhost', 1, 0, 0, '2022-04-07 08:03:56', '2022-04-07 08:03:56'),
(4, NULL, 'Laravel Password Grant Client', 'P03bw333WuSbG3KcWW0VYBBOOTAhcCZaCB3rTt3V', 'users', 'http://localhost', 0, 1, 0, '2022-04-07 08:03:56', '2022-04-07 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(2, 3, '2022-04-07 08:03:56', '2022-04-07 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `phone` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`phone`, `token`, `created_at`, `otp_hit_count`, `is_temp_blocked`, `temp_block_time`) VALUES
('+923167936302', '3576', '2023-10-23 18:20:38', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_records`
--

CREATE TABLE `payment_records` (
  `id` char(36) NOT NULL,
  `merchant_user_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` double(14,2) NOT NULL DEFAULT 0.00,
  `callback` varchar(255) DEFAULT NULL,
  `is_paid` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=unpaid, 1=paid',
  `expired_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

CREATE TABLE `phone_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_verifications`
--

INSERT INTO `phone_verifications` (`id`, `phone`, `otp`, `created_at`, `updated_at`, `otp_hit_count`, `is_temp_blocked`, `temp_block_time`) VALUES
(50, '+923454770150', '6654', '2023-12-06 08:37:15', '2023-12-06 08:37:15', 0, 0, NULL),
(51, '+923454770150', '6161', '2023-12-06 08:37:15', '2023-12-06 08:37:15', 0, 0, NULL),
(52, '+923434343434', '6399', '2023-12-08 07:42:11', '2023-12-08 07:42:11', 0, 0, NULL),
(54, '+923427386726', '2606', '2023-12-14 07:40:41', '2023-12-14 07:40:41', 0, 0, NULL),
(57, '+9203107755665', '9737', '2023-12-14 11:50:26', '2023-12-14 11:50:26', 0, 0, NULL),
(58, '+9203109988776', '4956', '2023-12-14 12:26:25', '2023-12-14 12:26:25', 0, 0, NULL),
(59, '+9203041122334', '3277', '2023-12-14 12:35:45', '2023-12-14 12:35:45', 0, 0, NULL),
(61, '+447394096900', '7625', '2023-12-18 13:11:48', '2023-12-18 13:11:48', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_money`
--

CREATE TABLE `request_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` bigint(20) NOT NULL,
  `to_user_id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_money`
--

INSERT INTO `request_money` (`id`, `from_user_id`, `to_user_id`, `type`, `amount`, `note`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'pending', 200.00, 'ABCDEFGH', '2023-10-26 07:28:15', '2023-10-26 07:28:15'),
(2, 7, 1, 'pending', 20000.00, 'this note for testing purpose', '2023-10-26 07:29:17', '2023-10-26 07:29:17'),
(3, 8, 1, 'denied', 200.00, NULL, '2023-10-26 12:27:38', '2023-10-26 13:23:56'),
(4, 8, 1, 'denied', 200.00, NULL, '2023-10-26 12:29:50', '2023-10-26 13:23:52'),
(5, 8, 1, 'denied', 100.00, NULL, '2023-10-26 13:22:44', '2023-10-26 13:23:48'),
(6, 8, 1, 'pending', 5.00, 'ee', '2023-10-27 07:20:53', '2023-10-27 07:20:53'),
(7, 8, 1, 'pending', 12345678.00, NULL, '2023-10-30 11:44:21', '2023-10-30 11:44:21'),
(8, 8, 1, 'pending', 1234567890.00, NULL, '2023-10-30 11:45:12', '2023-10-30 11:45:12'),
(9, 8, 1, 'pending', 1234567890.00, NULL, '2023-10-30 11:48:24', '2023-10-30 11:48:24'),
(10, 8, 1, 'pending', 123456789024.00, NULL, '2023-10-30 11:49:56', '2023-10-30 11:49:56'),
(11, 8, 1, 'pending', 123456789123.00, NULL, '2023-10-30 11:51:26', '2023-10-30 11:51:26'),
(12, 8, 1, 'pending', 10.00, NULL, '2023-10-30 13:51:03', '2023-10-30 13:51:03'),
(13, 8, 1, 'pending', 12.00, NULL, '2023-10-30 13:51:47', '2023-10-30 13:51:47'),
(14, 8, 1, 'pending', 1.00, NULL, '2023-10-30 13:51:58', '2023-10-30 13:51:58'),
(15, 8, 1, 'pending', 2.00, NULL, '2023-10-30 13:52:04', '2023-10-30 13:52:04'),
(16, 8, 1, 'pending', 3.00, NULL, '2023-10-30 13:52:10', '2023-10-30 13:52:10'),
(17, 8, 1, 'pending', 4.00, NULL, '2023-10-30 13:52:22', '2023-10-30 13:52:22'),
(18, 8, 1, 'Denied', 5.00, NULL, '2023-10-30 13:52:29', '2023-10-30 13:52:29'),
(19, 8, 1, 'pending', 20000.00, 'ok', '2023-10-30 13:52:55', '2023-10-30 13:52:55'),
(20, 8, 1, 'approved', 50000.00, NULL, '2023-10-30 13:53:18', '2023-10-30 13:53:18'),
(21, 8, 1, 'pending', 4999.00, NULL, '2023-10-30 13:56:15', '2023-10-30 13:56:15'),
(22, 8, 1, 'approved', 49994.00, NULL, '2023-10-30 13:56:17', '2023-10-30 13:56:17'),
(23, 8, 1, 'pending', 49994.00, NULL, '2023-10-30 13:56:18', '2023-10-30 13:56:18'),
(24, 8, 1, 'pending', 87567.00, 'ok', '2023-10-31 08:42:32', '2023-10-31 08:42:32'),
(25, 8, 1, 'denied', 5000.00, NULL, '2023-10-31 08:43:46', '2023-10-31 08:43:46'),
(26, 8, 1, 'pending', 8990907.00, 'approved', '2023-10-31 08:46:08', '2023-10-31 08:46:08'),
(27, 8, 1, 'pending', 8990907.00, 'approved', '2023-10-31 08:46:10', '2023-10-31 08:46:10'),
(28, 8, 1, 'pending', 2000.00, NULL, '2023-10-31 08:47:29', '2023-10-31 08:47:29'),
(29, 8, 1, 'pending', 5000.00, NULL, '2023-10-31 08:47:35', '2023-10-31 08:47:35'),
(30, 8, 1, 'pending', 5000.00, NULL, '2023-10-31 08:48:12', '2023-10-31 08:48:12'),
(31, 8, 1, 'pending', 2000.00, NULL, '2023-10-31 08:48:19', '2023-10-31 08:48:19'),
(32, 8, 1, 'pending', 1000.00, NULL, '2023-10-31 08:48:25', '2023-10-31 08:48:25'),
(33, 8, 1, 'pending', 500.00, NULL, '2023-10-31 08:48:31', '2023-10-31 08:48:31'),
(34, 8, 1, 'approved', 536387.00, 'looooo', '2023-10-31 08:48:44', '2023-10-31 08:48:44'),
(35, 8, 1, 'approved', 11221.00, 'new case', '2023-10-31 11:32:41', '2023-10-31 11:32:41'),
(36, 12, 1, 'approved', 43245.00, '1 test', '2023-11-02 08:43:21', '2023-11-02 08:43:21'),
(37, 12, 1, 'pending', 43.00, '2 t', '2023-11-02 08:43:31', '2023-11-02 08:43:31'),
(38, 12, 1, 'approved', 2000.00, NULL, '2023-11-02 08:43:38', '2023-11-02 08:43:38'),
(39, 12, 1, 'pending', 5000.00, '4t', '2023-11-02 08:43:45', '2023-11-02 08:43:45'),
(40, 12, 1, 'denied', 500.00, '4t', '2023-11-02 08:43:53', '2023-11-02 08:43:53'),
(41, 12, 1, 'denied', 23457.00, 'ussaaa', '2023-11-02 08:57:38', '2023-11-02 08:57:38'),
(42, 12, 1, 'pending', 32122.00, 'rt', '2023-11-02 10:18:30', '2023-11-02 10:18:30'),
(43, 11, 1, 'pending', 400.00, 'Note from agent', '2023-11-02 10:23:49', '2023-11-02 10:23:49'),
(44, 14, 1, 'denied', 500.00, NULL, '2023-11-06 12:42:24', '2023-11-06 13:11:28'),
(45, 14, 1, 'approved', 500.00, NULL, '2023-11-06 13:10:03', '2023-11-06 13:11:25'),
(46, 14, 1, 'pending', 9.00, 'ok', '2023-11-10 10:21:02', '2023-11-10 10:21:02'),
(47, 14, 1, 'pending', 9.00, 'ok', '2023-11-10 10:21:03', '2023-11-10 10:21:03'),
(48, 14, 1, 'pending', 9.00, 'ok', '2023-11-10 10:21:05', '2023-11-10 10:21:05'),
(49, 16, 1, 'pending', 500.00, 'Requesting 500, may it be $ dollars or any other currency', '2023-12-06 10:54:50', '2023-12-06 10:54:50'),
(50, 16, 1, 'pending', 8906.00, NULL, '2023-12-07 10:40:16', '2023-12-07 10:40:16'),
(51, 16, 1, 'pending', 7.00, NULL, '2023-12-07 10:40:24', '2023-12-07 10:40:24'),
(52, 16, 1, 'pending', 500.00, NULL, '2023-12-07 10:40:32', '2023-12-07 10:40:32'),
(53, 16, 1, 'pending', 1000.00, NULL, '2023-12-07 10:40:40', '2023-12-07 10:40:40'),
(54, 16, 1, 'pending', 1000.00, NULL, '2023-12-07 10:40:58', '2023-12-07 10:40:58'),
(55, 16, 1, 'pending', 8.00, NULL, '2023-12-07 10:41:07', '2023-12-07 10:41:07'),
(56, 16, 1, 'pending', 2.00, NULL, '2023-12-07 10:41:18', '2023-12-07 10:41:18'),
(57, 16, 1, 'pending', 1000.00, NULL, '2023-12-07 10:41:26', '2023-12-07 10:41:26'),
(58, 16, 1, 'approved', 2000.00, NULL, '2023-12-07 10:41:33', '2023-12-14 07:41:30'),
(59, 16, 1, 'denied', 5000.00, NULL, '2023-12-07 10:41:39', '2023-12-14 07:41:29'),
(60, 16, 1, 'approved', 1000.00, NULL, '2023-12-08 07:59:36', '2023-12-14 07:41:24'),
(61, 16, 1, 'approved', 500.00, NULL, '2023-12-12 07:46:21', '2023-12-14 07:41:12'),
(62, 16, 1, 'denied', 2.00, NULL, '2023-12-12 07:46:30', '2023-12-14 07:41:19'),
(63, 16, 1, 'approved', 12.00, NULL, '2023-12-12 07:46:38', '2023-12-14 07:41:16'),
(64, 16, 1, 'denied', 5000.00, NULL, '2023-12-12 07:47:14', '2023-12-14 07:41:07'),
(65, 18, 1, 'pending', 5000.00, 'test', '2023-12-17 11:13:54', '2023-12-17 11:13:54'),
(66, 19, 1, 'approved', 55000.00, 'testing', '2023-12-20 08:10:23', '2023-12-20 08:10:23'),
(67, 25, 1, 'approved', 5903.00, 'test', '2023-12-20 12:24:39', '2023-12-20 12:24:39'),
(68, 19, 1, 'denied', 67.00, 'test', '2023-12-20 12:29:57', '2023-12-20 12:29:57'),
(69, 19, 1, 'approved', 68023.00, NULL, '2023-12-20 12:30:08', '2023-12-20 12:30:08'),
(70, 19, 1, 'denied', 432.00, NULL, '2023-12-20 12:30:19', '2023-12-20 12:30:19'),
(71, 19, 1, 'denied', -8234.00, NULL, '2023-12-20 12:30:34', '2023-12-20 12:30:34'),
(72, 19, 1, 'approved', 890.00, NULL, '2023-12-20 12:30:58', '2023-12-20 12:30:58'),
(73, 25, 1, 'approved', 4532.00, NULL, '2023-12-20 12:41:24', '2023-12-20 12:41:24'),
(74, 25, 1, 'denied', -987.00, NULL, '2023-12-20 12:41:39', '2023-12-20 12:41:39'),
(75, 19, 1, 'denied', 500.00, NULL, '2023-12-20 12:50:09', '2023-12-20 12:50:09'),
(76, 19, 1, 'approved', 1000.00, NULL, '2023-12-20 12:50:15', '2023-12-20 12:50:15'),
(77, 19, 1, 'approved', 2000.00, NULL, '2023-12-20 12:50:19', '2023-12-20 12:50:19'),
(78, 19, 1, 'approved', 5000.00, NULL, '2023-12-20 12:50:26', '2023-12-20 12:50:26'),
(79, 19, 1, 'pending', 786.00, NULL, '2023-12-20 13:18:03', '2023-12-20 13:18:03'),
(80, 19, 1, 'pending', 1000.00, NULL, '2023-12-22 12:42:00', '2023-12-22 12:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `soft_credentials`
--

CREATE TABLE `soft_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ref_trans_id` varchar(255) DEFAULT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `debit` double(14,2) NOT NULL DEFAULT 0.00,
  `credit` double(14,2) NOT NULL DEFAULT 0.00,
  `balance` double(14,2) NOT NULL DEFAULT 0.00,
  `from_user_id` bigint(20) DEFAULT NULL,
  `to_user_id` bigint(20) DEFAULT NULL,
  `bonus_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `is_commission` tinyint(4) DEFAULT NULL,
  `transaction_detail_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `ref_trans_id`, `transaction_type`, `debit`, `credit`, `balance`, `from_user_id`, `to_user_id`, `bonus_id`, `created_at`, `updated_at`, `note`, `transaction_id`, `is_commission`, `transaction_detail_id`) VALUES
(1, 1, NULL, 'cash_in', 0.00, 5000000.00, 15051535.14, 1, 1, NULL, '2023-12-22 12:43:25', '2023-12-22 12:43:25', NULL, 'S1dqs1703249005', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plateform_fee` decimal(8,2) DEFAULT NULL,
  `sender_fee` decimal(8,2) DEFAULT NULL,
  `receiver_fee` decimal(8,2) DEFAULT NULL,
  `admin_fee` decimal(8,2) DEFAULT NULL,
  `receiver_amount` decimal(8,2) DEFAULT NULL,
  `receiver_amount_exchange` decimal(8,2) DEFAULT NULL,
  `secret_pin` varchar(255) NOT NULL,
  `status` enum('pending','cancel','complete','reject') NOT NULL DEFAULT 'pending',
  `base_currency_code` varchar(255) DEFAULT NULL,
  `destination_currency_code` varchar(255) DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `sender_customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_customer_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_limits`
--

CREATE TABLE `transaction_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `todays_count` int(11) NOT NULL DEFAULT 0,
  `todays_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `this_months_count` int(11) NOT NULL DEFAULT 0,
  `this_months_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) DEFAULT NULL COMMENT 'add_money, send_money, cash_out, send_money_request, withdraw_request',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_limits`
--

INSERT INTO `transaction_limits` (`id`, `user_id`, `todays_count`, `todays_amount`, `this_months_count`, `this_months_amount`, `type`, `created_at`, `updated_at`) VALUES
(1, 8, 0, '0.00', 0, '0.00', 'add_money', '2023-10-27 10:50:29', '2023-10-27 10:50:29'),
(2, 10, 0, '0.00', 0, '0.00', 'add_money', '2023-11-02 07:18:52', '2023-11-02 07:18:52'),
(3, 11, 0, '0.00', 0, '0.00', 'add_money', '2023-11-02 07:32:13', '2023-11-02 07:32:13'),
(4, 12, 0, '0.00', 0, '0.00', 'add_money', '2023-11-02 08:41:12', '2023-11-02 08:41:12'),
(5, 14, 0, '0.00', 0, '0.00', 'add_money', '2023-11-03 14:36:18', '2023-11-03 14:36:18'),
(6, 16, 0, '0.00', 0, '0.00', 'add_money', '2023-12-05 11:06:33', '2023-12-05 11:06:33'),
(7, 22, 0, '0.00', 0, '0.00', 'add_money', '2023-12-15 10:58:01', '2023-12-15 10:58:01'),
(8, 18, 0, '0.00', 0, '0.00', 'add_money', '2023-12-17 10:58:28', '2023-12-17 10:58:28'),
(9, 24, 0, '0.00', 0, '0.00', 'add_money', '2023-12-18 14:50:28', '2023-12-18 14:50:28'),
(10, 19, 0, '0.00', 0, '0.00', 'add_money', '2023-12-20 08:23:26', '2023-12-20 08:23:26'),
(11, 25, 0, '0.00', 0, '0.00', 'add_money', '2023-12-20 12:25:17', '2023-12-20 12:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `sender` bigint(20) NOT NULL,
  `receiver` bigint(20) NOT NULL,
  `receiver_type` varchar(255) NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `unique_id`, `sender`, `receiver`, `receiver_type`, `amount`, `created_at`, `updated_at`) VALUES
(1, '18901783537', 1, 16, '1', 600.00, '2023-12-13 08:34:35', '2023-12-13 08:34:35'),
(2, '21806458398', 1, 16, '1', 700.00, '2023-12-13 08:35:12', '2023-12-13 08:35:12'),
(3, '36397755513', 1, 17, '1', 500.00, '2023-12-15 07:07:57', '2023-12-15 07:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `f_name` varchar(255) DEFAULT NULL,
  `l_name` varchar(255) DEFAULT NULL,
  `dial_country_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '[''Admin''=>0, ''Agent''=>1, ''Customer''=>2]',
  `role` tinyint(1) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `referral_id` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `two_factor` tinyint(1) NOT NULL DEFAULT 0,
  `fcm_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `identification_type` varchar(255) DEFAULT NULL,
  `identification_number` varchar(255) DEFAULT NULL,
  `identification_image` text NOT NULL DEFAULT '[]',
  `is_kyc_verified` tinyint(1) NOT NULL DEFAULT 3 COMMENT '[''Pending''=>0, ''Approved''=>1, ''denied''=>2, ''YetToApply''=>3]',
  `login_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  `commission` int(11) NOT NULL DEFAULT 0,
  `fee` int(11) NOT NULL DEFAULT 0,
  `is_commission_verified` tinyint(4) DEFAULT 0,
  `is_fee_verified` tinyint(4) DEFAULT 0,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `dial_country_code`, `phone`, `email`, `image`, `type`, `role`, `password`, `is_phone_verified`, `is_email_verified`, `remember_token`, `created_at`, `updated_at`, `last_active_at`, `unique_id`, `referral_id`, `gender`, `occupation`, `two_factor`, `fcm_token`, `is_active`, `identification_type`, `identification_number`, `identification_image`, `is_kyc_verified`, `login_hit_count`, `is_temp_blocked`, `temp_block_time`, `commission`, `fee`, `is_commission_verified`, `is_fee_verified`, `country`, `city`, `city_id`, `country_id`, `deleted_at`) VALUES
(1, 'Bitsclan', 'IT Solutions', '+92', '+923156668052', 'bitsclansolutions@gmail.com', NULL, 0, NULL, '$2y$10$zoO3rKbn2P11BkrM.pEZzu0jZlT2UCb1MpRHfn00bDYF8HciPtY0W', 1, 1, NULL, '2023-09-21 11:59:46', '2023-09-21 11:59:46', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(16, 'Wajid', 'Ali', '+92', '+923034766424', 'wajid@gmail.com', NULL, 1, NULL, '$2y$10$A75MNomCyyM9rFn14VMypeqZHsYiYTe20bBVj/xpDN.bc3hBMsb9C', 0, 0, NULL, '2023-12-05 11:02:52', '2023-12-22 11:21:40', '2023-12-22 11:21:40', '1627318', NULL, 'Male', 'Software Engineer', 0, NULL, 1, NULL, NULL, '[]', 1, 0, 0, NULL, 5, 5, 0, 0, 'Pakistan', 'Karachi', 3, 165, NULL),
(17, 'Wajiha', 'Sharif', '+92', '+923564488067', 'Wajiha.Bitsclan@gmail.com', '2023-12-14-657ab01d4a3f6.png', 1, NULL, '$2y$10$wuzqaWYzuWhGBL3/90ERfei.ymotWKJZ2zWCCODuOh4wYqlhtOege', 0, 0, NULL, '2023-12-14 07:34:53', '2023-12-14 07:34:53', NULL, '1746725', NULL, 'female', 'Engineer', 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(18, 'Haider', 'Ali', '+92', '+923001213145', 'haider@gmail.com', NULL, 1, NULL, '$2y$10$bbu2cH1VIIyK2mWLq8SzV.3cj8YSLKf/vRupuLQ0kdIKodIIfMehK', 0, 0, NULL, '2023-12-14 07:50:54', '2023-12-18 12:58:38', '2023-12-18 11:12:04', '1866004', NULL, 'Male', 'Software Developer', 0, NULL, 1, NULL, NULL, '[]', 1, 0, 0, NULL, 5, 5, 1, 1, 'Pakistan', 'Islamabad', 1, 165, '2023-12-18 12:58:38'),
(19, 'Aryan', 'Khan', '+92', '+923008976543', 'aryan@gmail.com', NULL, 1, NULL, '$2y$10$uOlTt0m1lT9PWZ/rYYDZvuy/kwKQtUMY5CwqZCUjkijViGhiRHuGG', 0, 0, NULL, '2023-12-14 07:54:54', '2023-12-22 13:16:48', '2023-12-22 13:16:48', '1934022', NULL, 'Male', 'QA Engineer', 0, NULL, 1, 'passport', '3330385640423', '[\"2023-12-20-65829d83dc4b1.png\"]', 1, 0, 0, NULL, 5, 5, 1, 1, 'Pakistan', 'Lahore', 1, 165, NULL),
(20, 'Mehwish', 'Sharif', '+47', '+4765684899876', NULL, '2023-12-14-657ab5ebb6b0b.png', 1, NULL, '$2y$10$RaqCfszsa/6844akxfpyq.2Fdm5/xdFOvTaS80qQA55gRyps/8O4.', 0, 0, NULL, '2023-12-14 07:59:39', '2023-12-14 07:59:39', NULL, '2097719', NULL, 'female', 'Engineer', 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(21, 'ABCD', 'EFGH', '+687', '+687BBNNXBNBXNSBSSBS', NULL, '2023-12-14-657ab64cb38ec.png', 1, NULL, '$2y$10$eT42Gol2/1P4YcOkrYrmM.QgELdy7A3396SnLUty55NgC/i7mp9Am', 0, 0, NULL, '2023-12-14 08:01:16', '2023-12-14 08:01:16', NULL, '2143333', NULL, 'female', 'Buisnessman', 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(22, 'Rahit', 'Ali', '+92', '+923456767690', 'rahit@gmail.com', '2023-12-15-657c2da1e7ed3.png', 1, NULL, '$2y$10$Cf/qeFKWhuvGgHUSK/TFMemNdfTu./z94Ycu1yUlr1O4JFFU6TMOO', 0, 0, NULL, '2023-12-15 10:39:44', '2023-12-18 13:15:19', '2023-12-15 10:40:25', '2248098', NULL, 'Male', 'Software Engineer', 0, NULL, 1, NULL, NULL, '[]', 1, 0, 0, NULL, 5, 7, 0, 0, 'Pakistan', 'Lahore', 2, 165, NULL),
(23, 'Jake', 'Adam', '+92', '+923121233211', 'jack@gmail.com', NULL, 1, NULL, '$2y$10$CazULWHGNU69SD6rvmhxrOCSO0RrJPMJYz.9ql6vTrAF85CGkk19q', 0, 0, NULL, '2023-12-18 13:59:43', '2023-12-18 14:00:23', '2023-12-18 14:00:23', '2359573', NULL, 'Male', 'Software Engineer', 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, 'Pakistan', 'Lahore', 2, 165, NULL),
(24, 'James', 'Jack', '+92', '+923456776543', 'james@gmail.com', NULL, 1, NULL, '$2y$10$.OJT4qbyvtmpvZ7ZB6rH.uSGKmDlq7rsaK884it8rm7vSfhAnj6D6', 0, 0, NULL, '2023-12-18 14:40:44', '2023-12-18 14:47:59', '2023-12-18 14:44:31', '2491805', NULL, 'Male', 'Software Engineer', 0, NULL, 1, 'passport', '12345678', '[\"2023-12-18-65805b736abf2.png\"]', 1, 0, 0, NULL, 0, 0, 0, 0, 'Pakistan', 'Lahore', 2, 165, NULL),
(25, 'Testing', 'agent', '+92', '+923001920287', NULL, NULL, 1, NULL, '$2y$10$fATeWWrNYqTzWB6k.Ew.QOALOu3/fPySnUNp.hJn0PmQLc74M4/sC', 0, 0, NULL, '2023-12-20 08:05:00', '2023-12-22 06:46:21', '2023-12-22 06:46:21', '2538862', NULL, 'Male', 'QA', 0, NULL, 1, 'passport', '3330385640423', '[\"2023-12-20-6582a06b6be34.png\"]', 1, 0, 0, NULL, 10, 10, 0, 0, 'United Arab Emirates', 'Dubai', 9, 2, NULL),
(26, 'Cody', 'Gakpo', '+92', '+923453453453', NULL, '2023-12-21-6583eeb45b39b.png', 2, NULL, '$2y$10$xLnGT8OdAofGzJU5oNiTSeS9aBt0naTj.zaIky1JvRyBssXkzCWaS', 0, 0, NULL, '2023-12-21 07:52:20', '2023-12-21 07:52:20', NULL, '2634284', NULL, 'male', 'Caby', 0, NULL, 1, NULL, NULL, '[]', 3, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_log_histories`
--

CREATE TABLE `user_log_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `device_model` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_methods`
--

CREATE TABLE `withdrawal_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(255) NOT NULL,
  `method_fields` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawal_methods`
--

INSERT INTO `withdrawal_methods` (`id`, `method_name`, `method_fields`, `created_at`, `updated_at`) VALUES
(3, 'Online Payment', '[{\"input_type\":\"email\",\"input_name\":\"email\",\"placeholder\":\"Enter Email\"},{\"input_type\":\"phone\",\"input_name\":\"phone_number\",\"placeholder\":\"Enter Phone Number\"}]', '2023-10-31 07:43:54', '2023-10-31 07:43:54'),
(4, 'ID Card', '[{\"input_type\":\"phone\",\"input_name\":\"phone_number\",\"placeholder\":\"Phone Number\"}]', '2023-11-02 14:13:37', '2023-11-02 14:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_requests`
--

CREATE TABLE `withdraw_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `amount` double(14,2) NOT NULL DEFAULT 0.00,
  `admin_charge` double(14,2) NOT NULL DEFAULT 0.00,
  `request_status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `sender_note` varchar(255) DEFAULT NULL,
  `admin_note` varchar(255) DEFAULT NULL,
  `withdrawal_method_id` bigint(20) DEFAULT NULL,
  `withdrawal_method_fields` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_requests`
--

INSERT INTO `withdraw_requests` (`id`, `user_id`, `amount`, `admin_charge`, `request_status`, `is_paid`, `sender_note`, `admin_note`, `withdrawal_method_id`, `withdrawal_method_fields`, `created_at`, `updated_at`) VALUES
(1, 8, 1.00, 0.05, 'pending', 0, NULL, NULL, 3, '{\"_token\":\"VJ8tNpJCNJVY9rrGX4vbzPG4DiJNpImcZ4cuHlPA\",\"method_id\":\"3\",\"id\":\"8\",\"email\":\"usamaafzal.bitsclan@gmail.com\",\"phone_number\":\"123456778\",\"amount\":\"1\",\"note\":null,\"pin\":\"1234\"}', '2023-10-31 08:18:57', '2023-10-31 08:18:57'),
(2, 8, 1123.00, 56.15, 'ACCEPTED', 0, NULL, NULL, 3, '{\"_token\":\"VJ8tNpJCNJVY9rrGX4vbzPG4DiJNpImcZ4cuHlPA\",\"method_id\":\"3\",\"id\":\"8\",\"email\":\"usamaafzal.bitsclan@gmail.com\",\"phone_number\":\"ASD\",\"amount\":\"01123\",\"note\":null,\"pin\":\"1123\"}', '2023-10-31 08:22:46', '2023-10-31 08:22:46'),
(3, 8, 999.00, 49.95, 'pending', 0, 'OK', NULL, 3, '{\"_token\":\"VJ8tNpJCNJVY9rrGX4vbzPG4DiJNpImcZ4cuHlPA\",\"method_id\":\"3\",\"id\":\"8\",\"email\":\"usamaafzal.bitsclan@gmail.com\",\"phone_number\":\"ASD\",\"amount\":\"999\",\"note\":\"OK\",\"pin\":\"1234\"}', '2023-10-31 08:23:34', '2023-10-31 08:23:34'),
(4, 8, 7890.00, 394.50, 'Denied ', 0, 'OK', NULL, 2, '{\"_token\":\"VJ8tNpJCNJVY9rrGX4vbzPG4DiJNpImcZ4cuHlPA\",\"method_id\":\"2\",\"id\":\"8\",\"card_number\":\"1244\",\"expiry_date\":\"213\",\"cvv\":\"12455\",\"amount\":\"7890\",\"note\":\"OK\",\"pin\":\"1234\"}', '2023-10-31 08:26:55', '2023-10-31 08:26:55'),
(5, 8, 500.00, 25.00, 'pending', 0, NULL, NULL, 3, '{\"_token\":\"PRmOmgsgI50UjWO3lIi5ZqFzxdm4DyUGAgAQKKsE\",\"method_id\":\"3\",\"id\":\"8\",\"email\":\"jia123.sharif@gmail.com\",\"phone_number\":\"ggggggggg\",\"amount\":\"500\",\"note\":null,\"pin\":\"1234\"}', '2023-11-02 06:28:45', '2023-11-02 06:28:45'),
(6, 8, 500.00, 25.00, 'pending', 0, NULL, NULL, 2, '{\"_token\":\"PRmOmgsgI50UjWO3lIi5ZqFzxdm4DyUGAgAQKKsE\",\"method_id\":\"2\",\"id\":\"8\",\"card_number\":\"fgfg\",\"expiry_date\":\"200\",\"cvv\":\"1\",\"amount\":\"500\",\"note\":null,\"pin\":\"1234\"}', '2023-11-02 06:34:17', '2023-11-02 06:34:17'),
(7, 11, 1000.00, 50.00, 'pending', 0, NULL, NULL, 2, '{\"_token\":\"PRmOmgsgI50UjWO3lIi5ZqFzxdm4DyUGAgAQKKsE\",\"method_id\":\"2\",\"id\":\"11\",\"card_number\":\"BHH\",\"expiry_date\":\"FFCCF\",\"cvv\":\"GGG\",\"amount\":\"1000\",\"note\":null,\"pin\":\"1234\"}', '2023-11-02 07:41:00', '2023-11-02 07:41:00'),
(8, 12, 1.00, 0.05, 'pending', 0, '6 t', NULL, 3, '{\"_token\":\"Wm09giI93RRYhtdakvzXpu2O7LvW16Xu4Njycl3S\",\"method_id\":\"3\",\"id\":\"12\",\"email\":\"usamafzal@gmail.com\",\"phone_number\":\"3034766424\",\"amount\":\"1\",\"note\":\"6 t\",\"pin\":\"1234\"}', '2023-11-02 08:44:47', '2023-11-02 08:44:47'),
(9, 11, 300.00, 15.00, 'pending', 0, 'Noted message', NULL, 3, '{\"_token\":\"69JdvrkyZUORImbbjfghKSCz5Br8tYFtydDRscWW\",\"method_id\":\"3\",\"id\":\"11\",\"email\":\"wajid@gmail.com\",\"phone_number\":\"03034766424\",\"amount\":\"300\",\"note\":\"Noted message\",\"pin\":\"1234\"}', '2023-11-02 10:22:33', '2023-11-02 10:22:33'),
(10, 14, 40.00, 2.00, 'pending', 0, 'Message of the day', NULL, 3, '{\"_token\":\"sxKvP4rkhTGwzvCSkFLd0g1F3zvhNIfvk6E33ZBa\",\"method_id\":\"3\",\"id\":\"14\",\"email\":\"wajid@gmail.com\",\"phone_number\":\"03034766424\",\"amount\":\"40\",\"note\":\"Message of the day\",\"pin\":\"1234\"}', '2023-11-02 14:04:25', '2023-11-02 14:04:25'),
(11, 14, 5000.00, 250.00, 'pending', 0, NULL, NULL, 4, '{\"_token\":\"7NVvvS3b1f6JisyWDpF0i8r1YyRDH4yeS2PKnXjD\",\"method_id\":\"4\",\"id\":\"14\",\"phone_number\":\"ggggggggg\",\"amount\":\"5000\",\"note\":null,\"pin\":\"9876\"}', '2023-11-03 13:11:14', '2023-11-03 13:11:14'),
(12, 14, 86.00, 4.30, 'pending', 0, 'ok', NULL, 3, '{\"_token\":\"8Rgu8qIcEh6rmFP2lzKXqcEO7YLZRIgsv697TDT7\",\"method_id\":\"3\",\"id\":\"14\",\"email\":\"usamaafzal.bitsclan@gmail.com\",\"phone_number\":\"ASD\",\"amount\":\"86\",\"note\":\"ok\",\"pin\":\"1234\"}', '2023-11-10 10:21:39', '2023-11-10 10:21:39'),
(13, 14, 23.00, 1.15, 'pending', 0, 'ok', NULL, 4, '{\"_token\":\"8Rgu8qIcEh6rmFP2lzKXqcEO7YLZRIgsv697TDT7\",\"method_id\":\"4\",\"id\":\"14\",\"phone_number\":\"ASD\",\"amount\":\"23\",\"note\":\"ok\",\"pin\":\"1234\"}', '2023-11-10 10:22:22', '2023-11-10 10:22:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_money`
--
ALTER TABLE `add_money`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_money_user_id_foreign` (`user_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonuses`
--
ALTER TABLE `bonuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_country_id_foreign` (`country_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `e_money`
--
ALTER TABLE `e_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funds`
--
ALTER TABLE `funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_topics`
--
ALTER TABLE `help_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `linked_websites`
--
ALTER TABLE `linked_websites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_phone_index` (`phone`);

--
-- Indexes for table `payment_records`
--
ALTER TABLE `payment_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_money`
--
ALTER TABLE `request_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_limits`
--
ALTER TABLE `transaction_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_log_histories`
--
ALTER TABLE `user_log_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_money`
--
ALTER TABLE `add_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bonuses`
--
ALTER TABLE `bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `e_money`
--
ALTER TABLE `e_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `help_topics`
--
ALTER TABLE `help_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `linked_websites`
--
ALTER TABLE `linked_websites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request_money`
--
ALTER TABLE `request_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_limits`
--
ALTER TABLE `transaction_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_log_histories`
--
ALTER TABLE `user_log_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
