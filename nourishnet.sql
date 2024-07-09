-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2024 at 02:22 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nourishnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `rating_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating_comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `user_id`, `recipe_id`, `rating`, `rating_date`, `rating_comment`) VALUES
(1, 2, 1, 1, '2024-06-18 18:37:11', ''),
(2, 4, 1, 5, '2024-06-18 18:49:30', 'w eats'),
(3, 4, 2, 5, '2024-07-03 09:21:03', 'very descriptive'),
(4, 4, 3, 3, '2024-07-03 10:54:09', 'good and basic');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe` varchar(255) NOT NULL,
  `recipe_description` text DEFAULT NULL,
  `recipe_ingredients` text DEFAULT NULL,
  `recipe_instructions` text DEFAULT NULL,
  `recipe_image` varchar(255) DEFAULT NULL,
  `recipe_tag` varchar(255) NOT NULL,
  `recipe_nutrition` varchar(500) NOT NULL,
  `recipe_suitability` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `user_id`, `recipe`, `recipe_description`, `recipe_ingredients`, `recipe_instructions`, `recipe_image`, `recipe_tag`, `recipe_nutrition`, `recipe_suitability`) VALUES
(1, 2, 'ugali', 'a good kenyan meal \"i guess\"', 'flour\r\nwater\r\nsome other stuff', 'simmer some water\r\nadd some flour\r\nmix the flour and water ', 'uploads/Ugali_&_Sukuma_Wiki.jpg', 'Dinner', '', ''),
(2, 2, 'Chocolate Chip Cookies', 'These classic chocolate chip cookies are crispy on the edges and chewy in the center. Perfect for satisfying your sweet tooth!', '1 cup unsalted butter, softened\r\n1 cup granulated sugar\r\n1 cup packed brown sugar\r\n2 large eggs\r\n1 teaspoon vanilla extract\r\n3 cups all-purpose flour\r\n1 teaspoon baking soda\r\n1/2 teaspoon baking powder\r\n1 teaspoon salt\r\n2 cups semisweet chocolate chips', 'Preheat your oven to 350°F (175°C).\r\nIn a large bowl, cream together the butter, granulated sugar, and brown sugar until smooth.\r\nBeat in the eggs one at a time, then stir in the vanilla extract.\r\nIn a separate bowl, combine the flour, baking soda, baking powder, and salt.\r\nGradually blend the dry ingredients into the wet mixture.\r\nStir in the chocolate chips by hand using a wooden spoon.\r\nDrop dough by rounded tablespoons onto ungreased cookie sheets.\r\nBake for about 10 minutes in the preheated oven, or until edges are nicely browned.\r\nAllow cookies to cool on baking sheet for 5 minutes before transferring to a wire rack to cool completely.', 'uploads/images.jpeg', 'Snack', 'Calories: 180\r\nTotal Fat: 9g\r\nSaturated Fat: 5g\r\nTrans Fat: 0g\r\nCholesterol: 25mg\r\nSodium: 130mg\r\nTotal Carbohydrate: 25g\r\nDietary Fiber: 1g\r\nSugars: 16g\r\nProtein: 2g', 'Vegetarians: The recipe is vegetarian-friendly as it does not contain any meat or fish products.\r\nNut Allergies: Depending on the chocolate chips used, there may be a risk of nut cross-contamination. Ensure to use nut-free chocolate chips if allergies are a concern.\r\nLactose Intolerance: The recipe contains butter, which can be substituted with a dairy-free alternative like margarine if needed.'),
(3, 4, 'Simple Plain Rice', 'This simple rice recipe provides a versatile base that can accompany a variety of dishes. It\'s easy to prepare and can be customized with herbs and spices to suit different tastes.', '1 cup white rice (long-grain or jasmine)\r\n2 cups water\r\nSalt (optional)', 'Place the rice in a fine-mesh sieve and rinse it under cold water until the water runs clear. This removes excess starch and helps prevent the rice from becoming sticky.\r\nIn a medium saucepan, bring 2 cups of water to a boil.\r\nAdd a pinch of salt if desired (optional).\r\nStir in the rinsed rice and return to a boil.\r\nOnce boiling, reduce the heat to low and cover the saucepan with a lid.\r\nLet the rice simmer for 15-20 minutes (for white rice) or according to package instructions, until the water is absorbed and the rice is tender.\r\nRemove the saucepan from heat and let it sit, covered, for 5 minutes.\r\nFluff the rice with a fork to separate the grains.', 'uploads/plain-white-rice-f7391e.jpg', 'Lunch', 'Calories: Approximately 200 kcal per serving (1 cup cooked rice)\r\nCarbohydrates: About 45 grams per serving\r\nProtein: Around 4 grams per serving\r\nFat: Approximately 0 grams per serving\r\nFiber: About 1 gram per serving', 'Gluten-free');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag`
--

CREATE TABLE `recipe_tag` (
  `recipe_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `user_type`) VALUES
(1, 'austinrulez', '$2y$10$9WCIGoksQf8QQEQqkahuxeA8nXu2YGe64yOlJ9Dpn1pTKaNP4ezHe', 'austin@gmail.com', 'admin'),
(2, 'tashierulez1', '$2y$10$4jP77QZaGAR2FqcLRGb7DOO4INYh67u3sDc4XAvMNI7Jiu4pXLW3e', 'natashaorwenyo@gmail.com', 'user'),
(3, 'jasonrulez1', '$2y$10$JZrpaVEpyX4yN1v1O5nqYuyj3f9/xAhkQduYJfCSwv.JcKPqWanBK', 'jason@gmail.com', 'admin'),
(4, 'exoexo', '$2y$10$62ehls/EgU5nMihcZi24q.oNjz6twzVCKQObLxg1f4AECQ.mArRhO', 'excellence@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipe_tag`
--
ALTER TABLE `recipe_tag`
  ADD PRIMARY KEY (`recipe_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`);

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `recipe_tag`
--
ALTER TABLE `recipe_tag`
  ADD CONSTRAINT `recipe_tag_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`),
  ADD CONSTRAINT `recipe_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
