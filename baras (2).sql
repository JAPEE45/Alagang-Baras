-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 06:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baras`
--

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) NOT NULL,
  `breed` varchar(50) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `owner` int(11) NOT NULL,
  `createdAt` varchar(100) NOT NULL DEFAULT current_timestamp(),
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `name`, `species`, `breed`, `sex`, `owner`, `createdAt`, `age`) VALUES
(7, 'juan', 'cat', 'chichi', 'Female', 12, '2025-09-12 14:30:14', 12);

-- --------------------------------------------------------

--
-- Table structure for table `coordinators`
--

CREATE TABLE `coordinators` (
  `coordinator_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position_role` varchar(100) DEFAULT NULL,
  `barangay_assigned` varchar(100) NOT NULL,
  `fullname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordinators`
--

INSERT INTO `coordinators` (`coordinator_id`, `user_id`, `position_role`, `barangay_assigned`, `fullname`) VALUES
(1, 2, 'sas', 'silangan', 'Juan Dela Cruz'),
(2, 2, 'sas', 'silangan', 'Juan Dela Cruz');

-- --------------------------------------------------------

--
-- Table structure for table `healthmonitoring`
--

CREATE TABLE `healthmonitoring` (
  `id` int(11) NOT NULL,
  `livestock_id` int(11) NOT NULL,
  `diagnosis` varchar(500) NOT NULL,
  `treatment` varchar(500) NOT NULL,
  `vaccine_given` int(100) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `livestock_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock`
--

CREATE TABLE `livestock` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_name` varchar(150) NOT NULL,
  `species` varchar(50) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `dob` date NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `livestock`
--

INSERT INTO `livestock` (`id`, `owner_id`, `owner_name`, `species`, `breed`, `sex`, `dob`, `qr_code`, `created_at`, `updated_at`) VALUES
(4, 4, 'jasper j j j', 'Swine', 's', 'Female', '2025-10-19', 'uploads/qrcodes/livestock_4.png', '2025-10-09 17:56:56', '2025-10-09 17:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `municipal_agriculture_officers`
--

CREATE TABLE `municipal_agriculture_officers` (
  `mao_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position_role` varchar(100) DEFAULT 'Municipal Agriculture Officer',
  `office_department` varchar(150) DEFAULT 'Municipal Agriculture Office',
  `assigned_municipality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(100) DEFAULT NULL,
  `extName` varchar(50) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobileNum` varchar(20) DEFAULT NULL,
  `landlineNum` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `birthPlace` varchar(150) DEFAULT NULL,
  `highestFormalEducation` varchar(100) DEFAULT NULL,
  `isPwd` enum('Yes','No') DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `civilStatus` enum('Single','Married','Separated','Widowed') DEFAULT NULL,
  `spouse` varchar(150) DEFAULT NULL,
  `isBeneficiary4ps` enum('Yes','No') DEFAULT NULL,
  `isIndigenous` enum('Yes','No') DEFAULT NULL,
  `indigenousGroup` varchar(100) DEFAULT NULL,
  `motherMaiden` varchar(150) DEFAULT NULL,
  `hasGovernmentId` enum('Yes','No') DEFAULT NULL,
  `governmentIdType` varchar(100) DEFAULT NULL,
  `governmentIdNum` varchar(100) DEFAULT NULL,
  `isHouseholdHead` enum('Yes','No') DEFAULT NULL,
  `householdHeadName` varchar(150) DEFAULT NULL,
  `householdHeadRelationship` varchar(100) DEFAULT NULL,
  `noOfHouseholdMembers` int(11) DEFAULT NULL,
  `noOfMale` int(11) DEFAULT NULL,
  `noOfFemale` int(11) DEFAULT NULL,
  `isMemberOfFarmersAssociation` enum('Yes','No') DEFAULT NULL,
  `farmerAssociation` varchar(150) DEFAULT NULL,
  `emergencyContactPerson` varchar(150) DEFAULT NULL,
  `emergencyContactNumber` varchar(20) DEFAULT NULL,
  `mainLivelihood` enum('Farmer','Farmworker/Laborer','Fisherfolk','Agri Youth') DEFAULT NULL,
  `typeOfFarmingActivity` varchar(100) DEFAULT NULL,
  `specifyTypeFarm` varchar(100) DEFAULT NULL,
  `kindOfWork` varchar(100) DEFAULT NULL,
  `specifyKindOfWork` varchar(100) DEFAULT NULL,
  `typeOfFishingActivity` varchar(100) DEFAULT NULL,
  `specifyFishingActivity` varchar(100) DEFAULT NULL,
  `typeOfInvolment` varchar(150) DEFAULT NULL,
  `specifyTypeOfInvolment` varchar(150) DEFAULT NULL,
  `farmingIncome` decimal(15,2) DEFAULT NULL,
  `nonFarmingIncome` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `surname`, `firstName`, `middleName`, `extName`, `sex`, `address`, `mobileNum`, `landlineNum`, `birthday`, `birthPlace`, `highestFormalEducation`, `isPwd`, `religion`, `civilStatus`, `spouse`, `isBeneficiary4ps`, `isIndigenous`, `indigenousGroup`, `motherMaiden`, `hasGovernmentId`, `governmentIdType`, `governmentIdNum`, `isHouseholdHead`, `householdHeadName`, `householdHeadRelationship`, `noOfHouseholdMembers`, `noOfMale`, `noOfFemale`, `isMemberOfFarmersAssociation`, `farmerAssociation`, `emergencyContactPerson`, `emergencyContactNumber`, `mainLivelihood`, `typeOfFarmingActivity`, `specifyTypeFarm`, `kindOfWork`, `specifyKindOfWork`, `typeOfFishingActivity`, `specifyFishingActivity`, `typeOfInvolment`, `specifyTypeOfInvolment`, `farmingIncome`, `nonFarmingIncome`, `created_at`) VALUES
(4, 'Dela', 'Juan', 'Cruz', 'j', 'Female', 'k', 'k', 'k', '2025-10-17', 'k', 'Elementary', 'No', 'k', 'Married', 'l', 'No', '', 'l,', 'l', 'No', 'l', 'l', 'No', 'l', 'l', 99, 0, 0, 'No', 'ii', '', '', 'Agri Youth', '', '', '', '', '', '', 'participated in any agricultural activity/program', '', 200.00, 0.00, '2025-10-09 15:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_` varchar(255) NOT NULL,
  `role` enum('Vet','MAO','Coordinator','Admin') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_`, `role`, `status`, `created_at`) VALUES
(1, 'japee', 'japee', 'MAO', '', '2025-09-12 03:26:03'),
(2, 'coordinator', 'coordinator', 'Coordinator', 'Active', '2025-10-09 14:41:53'),
(3, 'vet123', 'vet4545', 'Vet', 'Active', '2025-10-10 08:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `veterinarians`
--

CREATE TABLE `veterinarians` (
  `vet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prc_number` varchar(50) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `fullname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinarians`
--

INSERT INTO `veterinarians` (`vet_id`, `user_id`, `prc_number`, `specialization`, `fullname`) VALUES
(1, 3, '12345', 'i dont know', 'Janna Cabiles');

-- --------------------------------------------------------

--
-- Table structure for table `vet_barangay_assignments`
--

CREATE TABLE `vet_barangay_assignments` (
  `id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coordinators`
--
ALTER TABLE `coordinators`
  ADD PRIMARY KEY (`coordinator_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `healthmonitoring`
--
ALTER TABLE `healthmonitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livestock`
--
ALTER TABLE `livestock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_owner` (`owner_id`);

--
-- Indexes for table `municipal_agriculture_officers`
--
ALTER TABLE `municipal_agriculture_officers`
  ADD PRIMARY KEY (`mao_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `veterinarians`
--
ALTER TABLE `veterinarians`
  ADD PRIMARY KEY (`vet_id`),
  ADD UNIQUE KEY `prc_number` (`prc_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vet_barangay_assignments`
--
ALTER TABLE `vet_barangay_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `coordinators`
--
ALTER TABLE `coordinators`
  MODIFY `coordinator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `healthmonitoring`
--
ALTER TABLE `healthmonitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock`
--
ALTER TABLE `livestock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `municipal_agriculture_officers`
--
ALTER TABLE `municipal_agriculture_officers`
  MODIFY `mao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `veterinarians`
--
ALTER TABLE `veterinarians`
  MODIFY `vet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vet_barangay_assignments`
--
ALTER TABLE `vet_barangay_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coordinators`
--
ALTER TABLE `coordinators`
  ADD CONSTRAINT `coordinators_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `livestock`
--
ALTER TABLE `livestock`
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `municipal_agriculture_officers`
--
ALTER TABLE `municipal_agriculture_officers`
  ADD CONSTRAINT `municipal_agriculture_officers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `veterinarians`
--
ALTER TABLE `veterinarians`
  ADD CONSTRAINT `veterinarians_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `vet_barangay_assignments`
--
ALTER TABLE `vet_barangay_assignments`
  ADD CONSTRAINT `vet_barangay_assignments_ibfk_1` FOREIGN KEY (`vet_id`) REFERENCES `veterinarians` (`vet_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
