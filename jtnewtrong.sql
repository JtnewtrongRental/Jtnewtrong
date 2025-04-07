-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 09:06 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jtnewtrong`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `established` varchar(50) DEFAULT NULL,
  `founder` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `overview` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `target_market` text DEFAULT NULL,
  `company_values` text DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `social_responsibility` text DEFAULT NULL,
  `future_goals` text DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `activity_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `company_name`, `established`, `founder`, `address`, `phone`, `email`, `website`, `overview`, `mission`, `vision`, `target_market`, `company_values`, `objectives`, `social_responsibility`, `future_goals`, `company_logo`, `activity_picture`) VALUES
(1, 'JTNEWTRONG, UNIPESSOAL, LDA', '2024', 'Jerry Tou', 'Moris Foun, Bairo Pite, Dom Aleixo, Dili, Timor-Leste', '+670 7843 9095', 'jtnewtrong.rental@gmail.com', 'https://jtnewtronguniplda.blogspot.com/', 'Jerry Tou JTNEWTRONG, UNIPESSOAL, LDA is a premier enterprise dedicated to \r\nproviding a wide array of comprehensive services, including exceptional printing solutions, \r\nprofessional binding services, convenient laptop rentals, and expert document translation. \r\nEstablished in 2024, we proudly serve a diverse clientele, encompassing businesses, \r\neducational institutions, non-profit organizations, government agencies, and individuals in \r\nDili, Timor-Leste, and surrounding areas. Our unwavering commitment to quality, innovation, \r\nand customer satisfaction has positioned us as a trusted partner for all your printing, \r\ntechnology, and language needs.', 'At JTNEWTRONG, UNIPESSOAL, LDA, our mission is to empower our clients to \r\ncommunicate effectively and achieve their goals through exceptional printing, technology, and \r\nlanguage solutions. We are dedicated to delivering superior quality prints, reliable technology \r\nservices, precise translations, and personalized customer care, ensuring the success and \r\nsatisfaction of every client we serve.', 'Our vision is to become the leading destination for printing, technology, and language \r\nsolutions, recognized for our excellence, innovation, and customer-centric approach. We aim \r\nto continuously expand our services, enhance our capabilities, and uphold the highest standards \r\nof quality and professionalism, establishing ourselves as the preferred partner for individuals \r\nand businesses alike.', 'Our primary target market includes businesses, educational institutions, non-profit \r\norganizations, government agencies, and individuals in Dili, Timor-Leste, and nearby regions. \r\nWe specifically cater to discerning clients seeking top-tier printing services, expert document \r\nbinding, reliable laptop rentals, and precise document translations. ', 'At JTNEWTRONG, UNIPESSOAL, LDA, our values underpin our corporate culture and \r\nguide our actions: \r\n1. Excellence: We pursue excellence in every aspect of our operations, from print quality to \r\ncustomer service. \r\n2. Integrity: We conduct our business with honesty and transparency, fostering trust and respect \r\nwith clients and partners. \r\n3. Innovation: We embrace new ideas and continuously seek ways to improve our services and \r\nprocesses. \r\n4. Collaboration: We value teamwork, believing that collective efforts yield superior outcomes. \r\n5. Customer Focus: We are committed to understanding and addressing the unique needs of our \r\nclients, ensuring their satisfaction.', 'In addition to our core printing and technology services, JTNEWTRONG, UNIPESSOAL, LDA aims \r\nto expand our business operations into various sectors, including: \r\n1. Real estate activities on behalf of others. \r\n2. Travel agencies and tour operators. \r\n3. Wholesale trade of agricultural machinery and equipment. \r\n4. Wholesale trade of office supplies and equipment. \r\n5. Wholesale and retail trade of furniture materials and equipment (e.g., tables, chairs). \r\n6. Trade in motor vehicles and related services: \r\n1. Maintenance and repair of motor \r\nvehicles. \r\n3. Wholesale and retail trade of \r\nmotorcycles and their parts. \r\n2. Trade in parts and accessories for motor \r\nvehicles. \r\n7. Wholesale trade of food, beverages, and tobacco. \r\n8. Wholesale trade of textiles, clothing, and footwear. \r\n4. Maintenance and repair of motorcycles \r\nand their parts. \r\n9. Wholesale trade of computers, peripheral equipment, and software. \r\n10. Retail trade of specialized food products, beverages, and tobacco. \r\n11. Wholesale trade of local products (e.g., coffee, potatoes, corn, rice, cassava, bananas). \r\n12. Retail sale of records, CDs, DVDs, sports equipment, games, and toys. \r\n13. Retail sales of clothing, footwear, and leather articles. \r\n14. Retail sale of pharmaceutical and medical products. \r\n15. Retail sale of cosmetic and hygiene products. \r\n16. Hotel establishments and restaurant services: \r\n1. Traditional restaurants and mobile food \r\nservices. \r\n17. Preparation and preservation of meat and meat products. \r\n18. Mass production and custom-made clothing manufacturing. \r\n19. Service activities related to printing. \r\n2. Event catering and meal services. \r\n3. Computer programming activities. \r\n20. Repair and maintenance of electronic and optical equipment. \r\n21. Trade (import, sale, wholesale, and retail) of hospital equipment (e.g., ventilators, syringes, beds). \r\n22. Motorcycle and car washing activities. \r\n23. Repair and maintenance of metal products (except machinery). \r\n24. Repair and maintenance of machinery and equipment.', 'We strive to operate in a socially responsible and sustainable manner by: \r\n1. Utilizing eco-friendly printing materials and processes whenever possible. \r\n2. Implementing energy-efficient practices and waste reduction measures. \r\n3. Supporting local communities through charitable initiatives and partnerships. \r\n4. Promoting diversity and inclusivity within our workforce and supply chain.', 'Looking ahead, JTNEWTRONG, UNIPESSOAL, LDA is focused on growth and expansion \r\nthrough: \r\n1. Investing in cutting-edge printing technologies to enhance service quality and efficiency. \r\n2. Diversifying our service offerings, including digital marketing solutions and online ordering \r\nplatforms. \r\n3. Strengthening our market presence in emerging sectors through strategic partnerships. \r\n4. Fostering an environment of innovation and continuous improvement within our organization. \r\n5. Enhancing our sustainability efforts, focusing on reducing our ecological impact and benefiting \r\nour communities.', 'uploads/logo_67ee441ee02320.13546232_JTNEWTRONG YOUTUBE channel.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `schedule` text DEFAULT NULL,
  `payroll` decimal(10,2) DEFAULT 0.00,
  `performance` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `total_price`, `created_at`) VALUES
(1, '', '4.00', '2025-04-03 09:56:42'),
(2, '', '6.00', '2025-04-03 10:13:49'),
(3, '', '4.00', '2025-04-03 11:58:23'),
(4, '', '25.00', '2025-04-05 06:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 17, 1, '4.00'),
(2, 2, 18, 1, '4.00'),
(3, 2, 5, 1, '2.00'),
(4, 3, 17, 1, '4.00'),
(5, 4, 17, 1, '4.00'),
(6, 4, 18, 2, '4.00'),
(7, 4, 9, 2, '2.50'),
(8, 4, 21, 2, '4.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cost` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `unit`, `category`, `description`, `price`, `stock`, `created_at`, `cost`, `image`) VALUES
(1, 'Print Certificate A4', 'Certificate', 'Pagina (Page)', 'Print and Design', '0', '1.00', 2000, '2025-04-03 08:22:19', '0.00', 'uploads/prod_67ee7631393e78.33734721_Print and Design Certificate A4.jpeg'),
(2, 'Print and Design Certificate A4', 'Design Certificate', 'Pagina (Page)', 'Print and Design', '0', '2.50', 2000, '2025-04-03 08:23:20', '0.00', 'uploads/prod_67ee7625a438f5.78470484_Print and Design Certificate A4.jpeg'),
(3, 'Print Convite (Invitation) A4', 'Convite (Invitation)', 'Pagina (Page)', 'Convite (Invitation) A4', '0', '1.00', 2000, '2025-04-03 08:24:23', '0.00', 'uploads/prod_67ee75f714fa56.61307500_Print Convite (Invitation) A4.jpeg'),
(4, 'Binding (Jilit lakban) A4', 'Binding (Jilit lakban)', 'Unit', 'Binding (Jilit lakban) A4', '0', '0.75', 2000, '2025-04-03 08:28:02', '0.00', 'uploads/prod_67ee75c79f9744.11951880_Binding (Jilit lakban) A3.jpeg'),
(5, 'Binding (Jilit lakban) A3', 'Binding (Jilit lakban)', 'Unit', 'Binding (Jilit lakban) A3', '0', '2.00', 1999, '2025-04-03 08:30:01', '0.00', 'uploads/prod_67ee71c98f09e1.55666881_Binding (Jilit lakban) A3.jpeg'),
(6, 'Binding Spiral (Jilit Spiral) A4', 'Binding Spiral (Jilit Spiral)', 'Unit', 'Binding Spiral (Jilit Spiral) A4', '0', '3.50', 2000, '2025-04-03 09:01:58', '0.00', 'uploads/prod_67ee75b012ad41.78260259_Binding Spiral (Jilit Spiral) A3.jpeg'),
(8, 'Binding Spiral (Jilit Spiral) A3', 'Binding Spiral (Jilit Spiral)', 'Unit', 'Binding Spiral', '0', '4.00', 2000, '2025-04-03 09:31:24', '0.00', 'uploads/prod_67ee758c7d5f97.65362575_Binding Spiral (Jilit Spiral) A3.jpeg'),
(9, 'Booklet A4', 'Booklet', 'Unit', 'Booklet A4', '0', '2.50', 1998, '2025-04-03 09:32:18', '0.00', 'uploads/prod_67ee7566f0e810.00810079_Booklet A4.jpeg'),
(10, 'Bulletin A3', 'Bulletin', 'Unit', 'Bulletin', '0', '4.99', 2000, '2025-04-03 09:33:08', '0.00', 'uploads/prod_67ee75370b8517.30074904_Bulletin A4.jpeg'),
(11, 'Bulletin A4', 'Bulletin', 'Unit', 'Bulletin', '0', '4.99', 2000, '2025-04-03 09:33:23', '0.00', 'uploads/prod_67ee752a6799e4.25126765_Bulletin A4.jpeg'),
(12, 'Book Separator  A4', 'Book Separator', 'Paper', 'Book Separator  A4', '0', '0.50', 2000, '2025-04-03 09:36:17', '0.00', 'uploads/prod_67ee74e43ab2e6.78290726_Book Separator A4.jpeg'),
(13, 'Lapijeira Picolo ( Piccolo Pen)', 'Picolo ( Piccolo Pen)', 'Pieces', 'Lapijeira Picolo', '0', '0.25', 2000, '2025-04-03 09:37:19', '0.00', 'uploads/prod_67ee74ba669769.65186294_Lapijeira Picolo ( Piccolo Pen) pieces.jpeg'),
(14, 'Lapijeira Picolo ( Piccolo Pen)', 'Picolo ( Piccolo Pen)', 'Box', 'Lapijeira Picolo', '0', '6.50', 2000, '2025-04-03 09:37:55', '0.00', 'uploads/prod_67ee745042e095.43091730_Lapijeira Picolo ( Piccolo Pen).jpeg'),
(15, 'Lapis (Pencil Staedtler Marks Logograph 100)', 'Pencil Staedtler Marks Logograph 100', 'Pieces', 'Lapis (Pencil Staedtler Marks Logograph 100)', '0', '0.25', 2000, '2025-04-03 09:38:37', '0.00', 'uploads/prod_67ee740f08c613.72693406_Lapis (Pencil Staedtler Marks Logograph 100).jpeg'),
(16, 'Lapizeira ben Bot (Pen K-35 0.5MM)', 'Pen K-35 0.5MM', 'Box', 'Lapis (Pencil Staedtler Marks Logograph 100)', '0', '6.50', 2000, '2025-04-03 09:39:04', '0.00', 'uploads/prod_67ee73019deb11.99132165_Lapizeira ben Bot (Pen K-35 0.5MM).jpeg'),
(17, 'Binder Clips No.155', 'Clips', 'Box', 'Binder Clips', '0', '4.00', 1997, '2025-04-03 09:39:54', '0.00', 'uploads/prod_67ee715f06afa0.41806044_Binder Clips No.155.jpeg'),
(18, 'Binder Clips No.200', 'Clips', 'Box', 'Binder Clips', '0', '4.00', 1997, '2025-04-03 09:40:34', '0.00', 'uploads/prod_67ee7154a7c384.87299618_Binder Clips No.200.jpeg'),
(19, 'Install Window 10pro', 'Window 10pro', 'Unit', 'Install Window', '', '10.00', 2147483647, '2025-04-04 11:29:23', '0.00', 'uploads/prod_67efc2938e9884.45474503_Install Window 10pro.jpeg'),
(20, 'Install Window 11pro', 'Window 11pro', 'Unit', 'Install Window', '', '10.00', 2147483647, '2025-04-04 11:32:00', '0.00', 'uploads/prod_67efc3302491a4.91617595_Install Window 11pro.jpeg'),
(21, 'Binder Clips No.200', 'Binder Clips', 'Box', 'Binder Clips', '', '4.00', 2147483645, '2025-04-05 06:07:18', '0.00', 'uploads/prod_67f0c896bca340.03355482_Binder Clips No.200.jpeg'),
(22, 'Install Offices 2016', 'Offices 2016', 'Unit', 'Install Offices', '', '15.00', 2147483647, '2025-04-05 06:09:01', '0.00', 'uploads/prod_67f0c8fdc9a367.92584347_Install Offices 2016.jpeg'),
(23, 'Install Offices 2019', 'Offices 2019', 'Unit', 'Install Offices', '', '15.00', 2147483647, '2025-04-05 06:10:30', '0.00', 'uploads/prod_67f0c9562ee992.77784050_Install Offices 2019.jpeg'),
(24, 'Install Photoshop', 'Photoshop', 'Unit', 'Install Programs', '', '50.00', 2147483647, '2025-04-05 06:15:11', '0.00', 'uploads/prod_67f0ca6fa78369.81763827_Install Photoshop.jpeg'),
(25, 'Install Antivirus', 'Antivirus', 'Unit', 'Install Programs', '', '50.00', 2147483647, '2025-04-05 06:18:48', '0.00', 'uploads/prod_67f0cf4e637b31.40851215_SMADAV PRO.jpeg'),
(26, 'Copia Metan, Mutin (Copy black and white) A4', 'Copy black and white A4', 'Paper', 'Copy black and white', '', '0.10', 2147483647, '2025-04-05 06:49:36', '0.00', 'uploads/prod_67f0d28099c165.83978295_Copy black and white A4.jpeg'),
(27, 'Copia Metan, Mutin (Copy black and white)  A3', 'Copy black and white A3', 'Paper', 'Copy black and white', '', '0.25', 2147483647, '2025-04-05 06:50:33', '0.00', 'uploads/prod_67f0d2b9abc064.68693646_Copy black and white A4.jpeg'),
(28, 'Copia Koloridu (Copy color) A4', 'Copia Koloridu (Copy color) A4', 'Paper', 'Copy color', '', '0.50', 2147483647, '2025-04-05 06:54:11', '0.00', 'uploads/prod_67f0d393ec96b6.51334141_Copia Koloridu Copy color  A4.jpeg'),
(29, 'Copia Coloridu (Copy Color) A3', 'Copia Coloridu (Copy Color) A3', 'Paper', 'Copy color', '', '1.00', 2147483647, '2025-04-05 06:55:16', '0.00', 'uploads/prod_67f0d3d4e5f9d0.70426063_Copia Koloridu Copy color  A4.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `rental_item` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Approved','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Open','In Progress','Closed') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `customer_name`, `subject`, `description`, `status`, `created_at`) VALUES
(1, 'ELIGIO MANUEL DE OLIVEIRA', 'INSTAL LAPTOP', 'WINDWOS ERROR', 'Closed', '2025-04-05 06:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','boss','other') NOT NULL DEFAULT 'other',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Jtnewtong', '$2y$10$saHNl91j.wJJqOu9hwDeoexzYHyMT1MWXwa7YhqCLI1/bBR8rGIUa', 'admin', '2025-04-03 08:02:00'),
(2, 'Jerry Tou', '$2y$10$MHYOZpam8G87HypMgr2aq.gP4ZWlixcOEvTPum0spUIlmveVdinrq', 'boss', '2025-04-03 08:02:24'),
(3, 'Eligio Manuel de Oliveira', '$2y$10$CU3VVnzRldcpVwByq6054.ii5v0wUBw2AUcS.K3CEhc3N539TIjg6', 'staff', '2025-04-03 09:54:10'),
(4, 'Jemelia Tou', '$2y$10$ksA3K6gb38SAcBxiLYFzZuT9CplkFGR8CGHSZHtOd/hr8ZDxl2vTy', 'admin', '2025-04-03 09:59:51'),
(5, 'Favila Cardoso Moniz', '$2y$10$fiLyNFXmh5h4gAb.yXo20eX4cl.W15UQJq9SkkukBJfui/40UCiLi', 'boss', '2025-04-03 10:01:28'),
(6, 'mario antonia', '$2y$10$5ui0ByP3s6A0xbQpNxJCa.J3kiP5nWdCaIqURPJBCslCBrM47E9pq', 'other', '2025-04-05 05:34:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`);

--
-- Constraints for table `orders`
--
UPDATE `orders` o
JOIN `customers` c ON o.customer_name = c.name
SET o.customer_id = c.id;

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
