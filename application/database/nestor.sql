
/*
 * Cleaning tables.
 */
DROP TABLE IF EXISTS `test_suites`;
DROP TABLE IF EXISTS `projects`;

/*
 * Projects table.
 */
CREATE TABLE IF NOT EXISTS `projects`(
  `id` NUMERIC(11) NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255),
  `is_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*
 * Test suites table.
 */
CREATE TABLE IF NOT EXISTS `test_suites`(
  `id` NUMERIC(11) NOT NULL AUTO_INCREMENT,
  `project_id` NUMERIC(11) NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `description` VARCHAR(255),
  PRIMARY KEY(`id`),
  KEY(`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `test_suites` ADD CONSTRAINT `test_suite_fk_project_id`
 FOREIGN KEY(`project_id`) REFERENCES `projects`(`id`);