

--
-- Database: `gillie`
--

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`) VALUES
(1, 'Hunting'),
(2, 'Freshwater Fishing'),
(3, 'Saltwater Fishing'),
(4, 'Camping');

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `email`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `password`, `remember_token`, `role_id`) VALUES
(1, 'Gilliea', 'Network', 'GillieAdmin@yopmail.com', 1, NULL, NULL, NULL, '$2y$10$JsbFc31C3vxmsmYUWYOAYeVk/tG..SbaqkQP40zCoYtvTYrtFEHXW', '8pN1HqsRU7pZUad1F8SBFwERJmIu2MysWGiPfxHXhrZuhb6ubBAK14mkk6FL', 1);

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `name`, `created_at`) VALUES
(1, 'General', '2016-10-02 00:00:00'),
(2, 'Nightlife', '2016-10-11 05:00:00'),
(3, 'Beauty & Spas', '2016-10-02 05:12:21'),
(4, 'Automotive', '2016-09-01 09:17:18');

--
-- Dumping data for table `hunting_lands`
--

INSERT INTO `hunting_lands` (`id`, `name`) VALUES
(1, 'forest and rivers'),
(2, 'safari'),
(3, 'sea'),
(4, 'Mountains');

--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`id`, `description`) VALUES
(1, 'started following you');

--
-- Dumping data for table `profile_rating_algos`
--

INSERT INTO `profile_rating_algos` (`id`, `star`, `points`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, '2016-06-23 06:18:17', NULL, NULL),
(2, 2, 1, '2016-06-23 05:13:14', NULL, NULL),
(3, 3, 3, '2016-06-23 05:15:00', NULL, NULL),
(4, 4, 4, '2016-06-22 07:13:05', NULL, NULL),
(5, 5, 4.5, '2016-06-23 04:09:12', NULL, NULL);

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`) VALUES
(1, 'Own'),
(2, 'Lease'),
(3, 'Public Hunting'),
(4, 'Willing to Host');

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'user');

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`id`, `name`) VALUES
(1, 'Big Game'),
(2, 'Small Game'),
(3, ' Fur Bearers'),
(4, 'Predators'),
(5, 'Upland Game Birds'),
(6, 'Waterfowls');

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `users_id`, `activities_id`) VALUES
(477, 71, 1),
(512, 1, 1),
(524, 3, 4),
(525, 2, 1),
(526, 2, 2),
(527, 2, 3),
(528, 2, 4);

--
-- Dumping data for table `user_weapons`
--

INSERT INTO `user_weapons` (`id`, `favourite`, `users_id`, `weapons_id`) VALUES
(785, 1, 71, 3),
(862, 1, 1, 1),
(863, 0, 1, 6),
(876, 0, 3, 1),
(877, 1, 3, 3),
(878, 1, 2, 1),
(879, 0, 2, 4),
(880, 0, 2, 6);

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`id`, `name`) VALUES
(1, 'Rifle'),
(2, 'Pistol'),
(3, 'Shotgun'),
(4, 'Crossbow'),
(5, 'Compound Bow'),
(6, 'Long Bow'),
(7, 'Recurve Bow');


