-- Fix vaccine_given column to accept varchar instead of int
-- Run this SQL in your phpMyAdmin to fix the database schema

ALTER TABLE `healthmonitoring` 
MODIFY COLUMN `vaccine_given` VARCHAR(255) NOT NULL DEFAULT '';

-- Also make diagnosis and treatment allow NULL or empty values
ALTER TABLE `healthmonitoring` 
MODIFY COLUMN `diagnosis` VARCHAR(500) NULL DEFAULT NULL,
MODIFY COLUMN `treatment` VARCHAR(500) NULL DEFAULT NULL;
