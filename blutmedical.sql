/*
 Navicat Premium Data Transfer

 Source Server         : PersonalProjectDB
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : blutmedical

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 15/01/2025 18:34:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for billing
-- ----------------------------
DROP TABLE IF EXISTS `billing`;
CREATE TABLE `billing`  (
  `billing_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `sub_total` int NOT NULL DEFAULT 0,
  `discount` int NOT NULL,
  `total_less_discount` int NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Unpaid',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`billing_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of billing
-- ----------------------------
INSERT INTO `billing` VALUES (20, NULL, 0, 0, 0, 'Unpaid', '', 1, '2024-05-13 18:15:13', '2024-05-13 18:15:13');
INSERT INTO `billing` VALUES (21, NULL, 0, 0, 0, 'Unpaid', '', 2, '2024-05-13 18:18:35', '2024-05-13 18:18:35');
INSERT INTO `billing` VALUES (22, NULL, 0, 0, 0, 'Unpaid', '', 1, '2024-05-14 10:28:00', '2024-05-14 10:28:00');

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart`  (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `product_id` int NULL DEFAULT NULL,
  `variation_id` int NULL DEFAULT NULL,
  `cart_quantity` int NULL DEFAULT NULL,
  `cart_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `proof_of_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_price` decimal(11, 2) NULL DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reference_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `delivery_rider_id` int NULL DEFAULT NULL,
  `delivery_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `delivery_guest_fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `delivery_guest_contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `delivery_guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_payer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `paypal_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paypal_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`cart_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 261 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cart
-- ----------------------------
INSERT INTO `cart` VALUES (251, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 16:30:19', '2025-01-15 16:30:19', NULL, '', '', '', '', '13A38016AN694935W', 'ZX3VYU72AS23A', 'Garry', 'sb-h8enp28879896@personal.example.com', '', NULL, NULL);
INSERT INTO `cart` VALUES (252, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 16:31:20', '2025-01-15 16:31:20', NULL, '', '', '', '', '6JV91527B5696872N', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', NULL, NULL);
INSERT INTO `cart` VALUES (253, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 16:34:58', '2025-01-15 16:34:58', NULL, '', '', '', '', '9DE454854S2630331', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', 'undefined, undefined, US', NULL);
INSERT INTO `cart` VALUES (254, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 16:41:38', '2025-01-15 16:41:38', NULL, '', '', '', '', '6MJ156945D761810S', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', 'undefined, undefined, US', NULL);
INSERT INTO `cart` VALUES (255, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 17:29:32', '2025-01-15 17:29:32', NULL, '', '', '', '', '2VN33157JB1597526', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', '2VN33157JB1597526', 'undefined, undefined, US');
INSERT INTO `cart` VALUES (256, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 18:16:00', '2025-01-15 18:16:00', NULL, '123', '123', '123', '123', '0SM16419A6347334E', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', 'undefined, undefined, US', '0SM16419A6347334E');
INSERT INTO `cart` VALUES (257, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 18:22:49', '2025-01-15 18:22:49', NULL, 'qwe', 'qwe', 'qwe', 'qwe', '0RU179607G342633A', 'GSB7BWJ6F8CX4', 'Gary Gary', 'gajultos.garrydev@gmail.com', '', 'undefined, undefined, PH', '0RU179607G342633A');
INSERT INTO `cart` VALUES (258, NULL, 23, 0, 4, 'Processing', NULL, 396.00, 'Paypal', 'Paid', NULL, '2025-01-15 18:29:06', '2025-01-15 18:29:06', NULL, '123', '123', '123', '123', '5HT25218D40423707', 'ZX3VYU72AS23A', 'Garry Gajultos', 'sb-h8enp28879896@personal.example.com', '', 'undefined, undefined, US', '5HT25218D40423707');
INSERT INTO `cart` VALUES (259, NULL, 17, 1, 1, 'Processing', NULL, 2.00, 'Paypal', 'Paid', NULL, '2025-01-15 18:30:20', '2025-01-15 18:30:20', NULL, 'qwe', 'qwe', 'qwe', 'qwe', '83C35062BP799142V', '8RB9985KB7364', 'Test Test', 'pendragonitteam@gmail.com', '', 'undefined, undefined, PH', '83C35062BP799142V');
INSERT INTO `cart` VALUES (260, NULL, 23, 0, 1, 'Processing', NULL, 99.00, 'Cash on Delivery', 'Unpaid', NULL, '2025-01-15 18:31:52', '2025-01-15 18:31:52', NULL, '2', '1', '3', '4', NULL, NULL, NULL, NULL, '', NULL, NULL);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (1, 'Scissors', '2024-05-14 17:48:46', '2025-01-06 11:55:42');
INSERT INTO `category` VALUES (2, 'Machines', '2024-05-14 17:48:30', '2025-01-06 11:55:49');
INSERT INTO `category` VALUES (5, 'SampeCategory1', '2024-09-04 17:23:29', '2025-01-06 11:56:02');
INSERT INTO `category` VALUES (6, 'SampeCategory2', '2024-09-06 21:20:13', '2025-01-06 11:56:06');

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `product_id` int NOT NULL,
  `payment_category_id` int NULL DEFAULT NULL,
  `order_quantity` int NULL DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_cost` int NULL DEFAULT NULL,
  `proof_of_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for payment
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment`  (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `payment_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `payment_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `payment_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment
-- ----------------------------

-- ----------------------------
-- Table structure for payment_category
-- ----------------------------
DROP TABLE IF EXISTS `payment_category`;
CREATE TABLE `payment_category`  (
  `payment_category_id` int NOT NULL AUTO_INCREMENT,
  `payment_category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_category
-- ----------------------------
INSERT INTO `payment_category` VALUES (1, 'Cash on Delivery', '2024-09-18 09:42:45', '2024-09-18 09:42:56');
INSERT INTO `payment_category` VALUES (2, 'Gcash', '2024-09-18 09:43:01', '2024-09-18 09:43:01');

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NULL DEFAULT NULL,
  `category_id` int NULL DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_stocks` int NULL DEFAULT NULL,
  `product_unitprice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_sellingprice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (17, 26, 1, 'Scissor 12', 'Test Descriptionqweqwe', '../../uploads/showcase2.png', 'Scissor 12', 18, '', '1232312.123123', '2024-09-05 14:39:33', '2025-01-14 11:23:54');
INSERT INTO `product` VALUES (22, 26, 5, 'Scissor 3', 'Test Description', '../../uploads/showcase1.png', 'Scissor 3', 0, '177.00', '188.00', '2024-11-08 22:11:48', '2025-01-09 13:03:42');
INSERT INTO `product` VALUES (23, 26, 6, 'Scissor 4', 'Test Description', '../../uploads/dreamy-watercolor-delicate-dusty-blue-heart-clipart-white-background_983420-312343.png', 'Noodles', 0, '77.00', '99.00', '2024-11-08 22:12:38', '2025-01-09 13:03:43');
INSERT INTO `product` VALUES (24, 26, 5, 'Test Product', 'Test Test Description', '../../uploads/wilcon.jpg', 'Test Product', 0, '244.00', '257.00', '2025-01-06 11:55:33', '2025-01-09 13:03:45');
INSERT INTO `product` VALUES (25, 28, 2, 'TestWFH', 'Test', '../../uploads/blutfront.png', 'TestWFH', 0, NULL, '13333.2991', '2025-01-14 10:18:47', '2025-01-14 10:18:47');

-- ----------------------------
-- Table structure for product_image
-- ----------------------------
DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image`  (
  `product_image_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `product_image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_image_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_image
-- ----------------------------
INSERT INTO `product_image` VALUES (1, 17, '../../uploads/sampleqwe.png', '2025-01-08 16:10:18', '2025-01-08 17:23:41');

-- ----------------------------
-- Table structure for purchase_order
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order`;
CREATE TABLE `purchase_order`  (
  `purchase_order_id` int NOT NULL AUTO_INCREMENT,
  `purchase_number` int NULL DEFAULT NULL,
  `supplier_id` int NULL DEFAULT NULL,
  `product_id` int NULL DEFAULT NULL,
  `quantity` int NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`purchase_order_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of purchase_order
-- ----------------------------
INSERT INTO `purchase_order` VALUES (8, 4, 27, 18, 6, '2024-09-05 16:05:41', '2024-09-06 11:45:40');
INSERT INTO `purchase_order` VALUES (9, 2, 26, 17, 12, '2024-09-05 16:38:36', '2024-10-12 11:11:32');
INSERT INTO `purchase_order` VALUES (10, 1, 26, 19, 10, '2024-09-05 16:55:57', '2024-09-05 16:55:57');
INSERT INTO `purchase_order` VALUES (11, 1, 27, 18, 1, '2024-09-06 11:45:19', '2024-09-06 11:45:19');
INSERT INTO `purchase_order` VALUES (12, 123, 26, 17, 10, '2024-09-06 11:51:02', '2024-09-06 11:51:02');
INSERT INTO `purchase_order` VALUES (13, 123123, 26, 19, 10, '2024-09-06 21:21:51', '2024-09-06 21:21:51');
INSERT INTO `purchase_order` VALUES (14, 123123, 26, 20, 100, '2024-09-06 22:21:19', '2024-09-06 22:21:19');
INSERT INTO `purchase_order` VALUES (15, 12314, 26, 17, 10, '2024-09-18 17:41:02', '2024-09-18 17:41:02');
INSERT INTO `purchase_order` VALUES (16, 23, 26, 19, 23, '2024-10-12 11:10:32', '2024-10-12 11:10:32');
INSERT INTO `purchase_order` VALUES (17, 123123, 26, 17, 20, '2024-11-08 22:43:23', '2024-11-08 22:43:23');

-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier`  (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `landline` int NULL DEFAULT NULL,
  `mobile_number` int NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO `supplier` VALUES (26, 'Pendragon', '1', 1, 123, 'test1@gmail.com', '123', '2024-09-04 14:22:47', '2024-11-08 22:44:29');
INSERT INTO `supplier` VALUES (27, 'Razzon', '2', 2, 2323, 'test1@gmail.com', '23', '2024-09-04 14:22:53', '2024-11-08 22:44:34');
INSERT INTO `supplier` VALUES (28, 'Puregold', '123', 123, 1, 'test1@gmail.com', '123', '2024-09-06 21:19:57', '2024-11-08 22:44:37');
INSERT INTO `supplier` VALUES (29, 'SM SUPERMARKET', 'Supplier2', 0, 123123, 'gajultos.garry123@gmail.com', '123', '2024-09-06 22:19:56', '2024-11-08 21:57:10');
INSERT INTO `supplier` VALUES (30, 'STARMALL', 'Test', 123, 123, 'test1@gmail.com', '23', '2024-10-12 11:05:22', '2024-11-08 22:44:40');
INSERT INTO `supplier` VALUES (31, '1', '2', 3, 4, '5@gmail.com', '4', '2025-01-15 11:39:09', '2025-01-15 11:39:09');
INSERT INTO `supplier` VALUES (32, '1', '2', 3, 4, '5@gmail.com', '6', '2025-01-15 11:39:35', '2025-01-15 11:39:35');
INSERT INTO `supplier` VALUES (33, '1', '2', 3, 1, 'kingaxie31@gmail.com', '3', '2025-01-15 11:40:41', '2025-01-15 11:40:41');
INSERT INTO `supplier` VALUES (34, '1', '2', 3, 6, '5@gmail.com', '4', '2025-01-15 11:41:19', '2025-01-15 11:41:19');
INSERT INTO `supplier` VALUES (35, '1', '2', 3, 6, '5@gmail.com', '4', '2025-01-15 11:42:02', '2025-01-15 11:42:02');
INSERT INTO `supplier` VALUES (36, '1', '2', 3, 4, '5@gmail.com', '6', '2025-01-15 11:43:22', '2025-01-15 11:43:22');
INSERT INTO `supplier` VALUES (37, '1', '2', 3, 4, 'kingaxie31@gmail.com', '1', '2025-01-15 11:43:43', '2025-01-15 11:43:43');
INSERT INTO `supplier` VALUES (38, '1', '2', 3, 4, '5@gmail.com', '6', '2025-01-15 11:44:08', '2025-01-15 11:44:08');
INSERT INTO `supplier` VALUES (39, '1', '2', 3, 4, '5@gmail.com', '6', '2025-01-15 11:44:22', '2025-01-15 11:44:22');
INSERT INTO `supplier` VALUES (40, '12', '2', 1, 1, 'pendragonitteam@gmail.com', '1', '2025-01-15 11:47:02', '2025-01-15 11:47:02');
INSERT INTO `supplier` VALUES (41, '1', '12', 3, 4, 'agbalahadia@scpa.com.ph', '1', '2025-01-15 11:47:26', '2025-01-15 11:47:26');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_contact` int NULL DEFAULT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_confirm_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `remember_me` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `user_type_id` int NULL DEFAULT NULL,
  `is_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `account_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Garry Gajultos', 'garry', '123123@gmail.com', 123123123, '$2y$10$RTgvXGAH1Z10iRnE5bHnW.U1VwX2xCp/2vsobgHyqUg9ugM1stSUi', '123123', '', '2024-04-07 16:08:00', '2025-01-14 09:31:28', 1, '1', 'Active', '1');
INSERT INTO `users` VALUES (2, 'Test Account', 'Ron', '123123@gmail.comm', NULL, '$2y$10$Wtj4pYEWKXHYe4DUwLPTveZdPJUNrXwfkfeZRWXO4bnmbNd9NOA9y', 'test1005', NULL, '2024-05-13 18:18:17', '2024-11-08 22:57:38', 1, '1', 'Active', 'qweqwe');
INSERT INTO `users` VALUES (39, '1', 'test', 'gajultos.garrydev@gmail.com', 1, '$2y$10$XX19Ar6P.ig1stK9lZ0N2eP89FY5FughUlK0xhgDfLj1P60tMMPva', '1', NULL, '2024-09-13 23:58:14', '2024-10-23 22:12:51', 4, '1', 'Active', '123123123');
INSERT INTO `users` VALUES (40, 'LCC WQE', 'testacc', 'Test@gmail.com', 123123, '$2y$10$9KeTSQ5PmtdiiqdqmsiUSuQs7OujRChozbhCai948a1DGo8Xq.mSe', 'test1005', NULL, '2024-09-13 23:58:14', '2024-11-08 22:55:25', 0, '0', 'Active', '123123123');

-- ----------------------------
-- Table structure for usertype
-- ----------------------------
DROP TABLE IF EXISTS `usertype`;
CREATE TABLE `usertype`  (
  `user_type_id` int NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `inventory_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `user_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `reports_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `po_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `transaction_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `orders_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deliveries_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`user_type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of usertype
-- ----------------------------
INSERT INTO `usertype` VALUES (1, 'Admin', '2024-09-04 10:46:35', '2024-11-08 22:59:31', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `usertype` VALUES (3, 'Staff', '2024-09-04 10:46:46', '2024-11-08 22:57:55', '1', '1', '1', '1', '0', '1', '1');
INSERT INTO `usertype` VALUES (4, 'Delivery Rider', '2024-10-12 11:21:06', '2024-11-01 10:50:10', '1', '1', '1', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for variations
-- ----------------------------
DROP TABLE IF EXISTS `variations`;
CREATE TABLE `variations`  (
  `variation_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`variation_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of variations
-- ----------------------------
INSERT INTO `variations` VALUES (1, 17, 'Size', 'Value13', '2025-01-06 15:08:02', '2025-01-08 15:30:46', '2');
INSERT INTO `variations` VALUES (2, 17, 'Color', 'Value233', '2025-01-06 15:21:07', '2025-01-08 16:17:33', '23');

SET FOREIGN_KEY_CHECKS = 1;
