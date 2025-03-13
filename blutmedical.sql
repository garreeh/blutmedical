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

 Date: 13/03/2025 18:11:37
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
  `variation_color_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`cart_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 320 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cart
-- ----------------------------
INSERT INTO `cart` VALUES (302, NULL, 28, 5, 1, 'Processing', NULL, 3.00, 'Paypal', 'Unpad', NULL, '2025-02-11 12:20:50', '2025-02-11 12:20:50', NULL, 'Garry', 'qwe', 'qwe', 'q@gmail.com', '5EJ59692FR1890104', 'CCSRW7G4Z8NBS', 'Garry Sandbox', 'sb-hjaz836856231@personal.example.com', '', 'undefined, undefined, US', '5EJ59692FR1890104', NULL);
INSERT INTO `cart` VALUES (303, NULL, 28, 5, 1, 'Processing', NULL, 3.00, 'Paypal', 'Unpad', NULL, '2025-02-11 12:20:50', '2025-02-11 12:20:50', NULL, 'Garry', 'qwe', 'qwe', 'q@gmail.com', '5EJ59692FR1890104', 'CCSRW7G4Z8NBS', 'Garry Sandbox', 'sb-hjaz836856231@personal.example.com', '', 'undefined, undefined, US', '5EJ59692FR1890104', NULL);
INSERT INTO `cart` VALUES (308, NULL, 24, 0, 1, 'Processing', NULL, 257.00, 'Cash on Delivery', 'Unpaid', NULL, '2025-03-06 14:16:32', '2025-03-06 14:16:32', NULL, 'q', 'q', 'q', 'q', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (309, 1, 28, 5, 1, 'Processing', NULL, 0.00, 'Cash on Delivery', 'Unpaid', '711438B67C', '2025-03-06 14:19:05', '2025-03-13 13:35:00', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 11);
INSERT INTO `cart` VALUES (310, 1, 22, 9, 1, 'Processing', NULL, 2222.00, 'Cash on Delivery', 'Unpaid', '711438B67C', '2025-03-13 13:19:18', '2025-03-13 13:35:00', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 0);
INSERT INTO `cart` VALUES (311, NULL, 23, 7, 1, 'Processing', NULL, 1.00, 'Cash on Delivery', 'Unpaid', 'ORD-4E7E987B', '2025-03-13 13:38:34', '2025-03-13 13:38:34', NULL, 'qwe', 'Garry', 'qwe', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (312, NULL, 25, 0, 1, 'Processing', NULL, 13333.30, 'Cash on Delivery', 'Unpaid', 'ORD-4E7E987B', '2025-03-13 13:38:34', '2025-03-13 13:38:34', NULL, 'qwe', 'Garry', 'qwe', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (313, NULL, 23, 7, 2, 'Processing', NULL, 2.00, 'Cash on Delivery', 'Unpaid', 'ORD-ABCF1839', '2025-03-13 13:39:38', '2025-03-13 13:39:38', NULL, 'qq', 'qwe', '2323', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (314, NULL, 25, 0, 1, 'Processing', NULL, 13333.30, 'Cash on Delivery', 'Unpaid', 'ORD-ABCF1839', '2025-03-13 13:39:38', '2025-03-13 13:39:38', NULL, 'qq', 'qwe', '2323', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (315, NULL, 25, 0, 6, 'Processing', NULL, 79999.79, 'Cash on Delivery', 'Unpaid', 'ORD-2881520B', '2025-03-13 13:40:57', '2025-03-13 13:40:57', NULL, '2qwe', 'Garry', '34', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (316, NULL, 22, 8, 1, 'Processing', NULL, 111.00, 'Cash on Delivery', 'Unpaid', 'ORD-2881520B', '2025-03-13 13:40:57', '2025-03-13 13:40:57', NULL, '2qwe', 'Garry', '34', 'tanginathis213012@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (317, NULL, 22, 8, 1, 'Cart', NULL, 6327.00, 'GCash', 'Unpaid', 'order-id-67d273ca207a7', '2025-03-13 13:57:30', '2025-03-13 13:57:30', NULL, 'qwe', 'qwe', 'qwe', 'gajultos.garry123@gmail.com', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);
INSERT INTO `cart` VALUES (318, 1, 22, 8, 2, 'Cart', NULL, 222.00, NULL, '', NULL, '2025-03-13 16:01:24', '2025-03-13 16:04:46', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 0);
INSERT INTO `cart` VALUES (319, 1, 24, 0, 4, 'Cart', NULL, 1028.00, NULL, '', NULL, '2025-03-13 16:08:14', '2025-03-13 16:08:34', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 0);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `subcategory_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (1, 'Scissors', '2024-05-14 17:48:46', '2025-03-10 17:06:08', 12);
INSERT INTO `category` VALUES (2, 'Machines', '2024-05-14 17:48:30', '2025-03-10 16:58:11', 12);
INSERT INTO `category` VALUES (5, 'SampeCategory1', '2024-09-04 17:23:29', '2025-03-10 17:06:06', 12);
INSERT INTO `category` VALUES (6, 'SampeCategory2', '2024-09-06 21:20:13', '2025-03-10 17:06:07', 12);
INSERT INTO `category` VALUES (10, 'qq', '2025-03-10 16:54:49', '2025-03-10 16:54:49', 11);

-- ----------------------------
-- Table structure for currency
-- ----------------------------
DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency`  (
  `dollar_id` int NOT NULL,
  `dollar_currency` int NULL DEFAULT NULL,
  PRIMARY KEY (`dollar_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of currency
-- ----------------------------
INSERT INTO `currency` VALUES (1, 57);

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
  `subcategory_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (22, 26, 5, 'Scissor 3', 'Test Description', '../../uploads/sampleqwe.png', 'Scissor 3', 0, '177.00', '188.00', '2024-11-08 22:11:48', '2025-03-10 16:57:31', 12);
INSERT INTO `product` VALUES (23, 26, 6, 'Scissor 4', 'Test Description', '../../uploads/dreamy-watercolor-delicate-dusty-blue-heart-clipart-white-background_983420-312343.png', 'Noodles', 0, '77.00', '99.00', '2024-11-08 22:12:38', '2025-01-09 13:03:43', NULL);
INSERT INTO `product` VALUES (24, 26, 6, 'Test Product', 'Test Test Description', '../../uploads/wilcon.jpg', 'Test Product', 0, '244.00', '257.00', '2025-01-06 11:55:33', '2025-03-10 17:19:16', NULL);
INSERT INTO `product` VALUES (25, 28, 2, 'TestWFH', 'Test', '../../uploads/blutfront.png', 'TestWFH', 0, NULL, '13333.2991', '2025-01-14 10:18:47', '2025-01-14 10:18:47', NULL);
INSERT INTO `product` VALUES (26, 32, 5, '123', '1', '../../uploads/IMG_20241213_111820_00_merged.jpg', 'Test', 0, NULL, '0', '2025-01-24 11:00:00', '2025-02-12 09:58:15', NULL);
INSERT INTO `product` VALUES (28, 32, 2, 'TEST LAST', '11', '../../uploads/Front ID Garry.jpeg', 'TEST LAST', 0, NULL, '123123', '2025-02-10 15:25:49', '2025-02-10 15:25:49', NULL);
INSERT INTO `product` VALUES (29, 32, 2, '1', '1', '', '1', 0, NULL, '0', '2025-02-11 09:57:01', '2025-02-12 09:58:27', NULL);
INSERT INTO `product` VALUES (30, 33, 2, '1', '1', '', '1', 0, NULL, '1', '2025-02-11 09:57:48', '2025-02-11 09:57:48', NULL);

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
-- Table structure for subcategory
-- ----------------------------
DROP TABLE IF EXISTS `subcategory`;
CREATE TABLE `subcategory`  (
  `subcategory_id` int NOT NULL AUTO_INCREMENT,
  `subcategory_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subcategory_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subcategory
-- ----------------------------
INSERT INTO `subcategory` VALUES (11, 'q', '2025-02-17 15:35:55', '2025-02-17 15:35:55');
INSERT INTO `subcategory` VALUES (12, 'w', '2025-02-17 15:35:57', '2025-02-17 15:35:57');

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
  `user_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Garry Gajultos', 'garry', 'tanginathis213012@gmail.com', '123123123', '$2y$10$RTgvXGAH1Z10iRnE5bHnW.U1VwX2xCp/2vsobgHyqUg9ugM1stSUi', '123123', '', '2024-04-07 16:08:00', '2025-03-13 13:34:42', 1, '1', 'Active', '1');
INSERT INTO `users` VALUES (2, 'Test Account', 'Ron', '123123@gmail.comm', NULL, '$2y$10$Wtj4pYEWKXHYe4DUwLPTveZdPJUNrXwfkfeZRWXO4bnmbNd9NOA9y', 'test1005', NULL, '2024-05-13 18:18:17', '2024-11-08 22:57:38', 1, '1', 'Active', 'qweqwe');
INSERT INTO `users` VALUES (39, '1', 'test', 'gajultos.garry@gmail.com', '1', '$2y$10$XX19Ar6P.ig1stK9lZ0N2eP89FY5FughUlK0xhgDfLj1P60tMMPva', '1', NULL, '2024-09-13 23:58:14', '2025-02-26 17:24:07', 4, '1', 'Active', '123123123');
INSERT INTO `users` VALUES (40, 'LCC WQE', 'testacc', 'Test@gmail.com', '123123', '$2y$10$9KeTSQ5PmtdiiqdqmsiUSuQs7OujRChozbhCai948a1DGo8Xq.mSe', 'test1005', NULL, '2024-09-13 23:58:14', '2024-11-08 22:55:25', 0, '0', 'Active', '123123123');
INSERT INTO `users` VALUES (43, 'Ronnel Cruz', 'gar', 'gajultos.garryde@gmail.com', '09611560419', '$2y$10$hv1pitl12GqTbs.f2iRd1.m559WPYnIl7/M88ZhYAXIUjFxaoqc9u', '123123', NULL, '2025-02-26 17:24:40', '2025-02-26 17:31:13', NULL, '0', 'Inactive', 'Test Address');
INSERT INTO `users` VALUES (44, 'Ronnel Cruz', 'EMP-6601', 'gajultos.garrydev@gmail.com', '1', '$2y$10$SGqi92A1btRijwHYbLzhbuSPFa16lsMeakx5u/TeKvlx1x7KINQeC', '11', NULL, '2025-02-26 17:31:36', '2025-02-26 17:31:36', NULL, '0', 'Inactive', '1');

-- ----------------------------
-- Table structure for usertype
-- ----------------------------
DROP TABLE IF EXISTS `usertype`;
CREATE TABLE `usertype`  (
  `user_type_id` int NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `ship_order` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `view_order` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `client_order_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `complete_order` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `view_shipped_order` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `shipped_order_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `view_transaction_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sales_report_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_setup_module` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_setup` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`user_type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of usertype
-- ----------------------------
INSERT INTO `usertype` VALUES (1, 'Admin', '2024-09-04 10:46:35', '2025-02-03 16:07:14', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `usertype` VALUES (3, 'Staff', '2024-09-04 10:46:46', '2025-02-03 15:56:34', '1', '1', '1', '1', '0', '1', '1', '1', '1', '1');
INSERT INTO `usertype` VALUES (4, 'Delivery Rider', '2024-10-12 11:21:06', '2025-02-03 15:56:35', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

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
  `product_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`variation_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of variations
-- ----------------------------
INSERT INTO `variations` VALUES (1, 17, 'Size', 'Value13', '2025-01-06 15:08:02', '2025-01-08 15:30:46', '2', NULL);
INSERT INTO `variations` VALUES (2, 17, 'Color', 'Value233', '2025-01-06 15:21:07', '2025-01-08 16:17:33', '23', NULL);
INSERT INTO `variations` VALUES (3, 27, NULL, '1', '2025-02-10 15:24:18', '2025-02-10 15:24:18', '3', '2');
INSERT INTO `variations` VALUES (5, 28, NULL, '1', '2025-02-10 15:25:49', '2025-02-10 15:25:49', '3', '2');
INSERT INTO `variations` VALUES (7, 23, NULL, 'q', '2025-02-10 15:53:55', '2025-02-10 15:53:55', '1', NULL);
INSERT INTO `variations` VALUES (8, 22, NULL, '1', '2025-02-10 15:54:09', '2025-02-10 15:54:21', '111', '111');
INSERT INTO `variations` VALUES (9, 22, NULL, '22', '2025-02-10 15:54:35', '2025-02-17 15:40:55', '2222', '1');

-- ----------------------------
-- Table structure for variations_colors
-- ----------------------------
DROP TABLE IF EXISTS `variations_colors`;
CREATE TABLE `variations_colors`  (
  `variation_color_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp,
  `updated_at` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`variation_color_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of variations_colors
-- ----------------------------
INSERT INTO `variations_colors` VALUES (1, 17, 'Size', '2025-01-06 15:08:02', '2025-01-08 15:30:46', '2', NULL);
INSERT INTO `variations_colors` VALUES (2, 17, 'Color', '2025-01-06 15:21:07', '2025-01-08 16:17:33', '23', NULL);
INSERT INTO `variations_colors` VALUES (11, 28, NULL, '2025-02-10 17:39:43', '2025-02-11 08:16:33', NULL, 'Onyx');
INSERT INTO `variations_colors` VALUES (12, 28, NULL, '2025-02-10 17:39:47', '2025-02-11 08:17:12', NULL, 'Sapphire');

SET FOREIGN_KEY_CHECKS = 1;
