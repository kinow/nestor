
/*
 * Cleaning tables.
 */
DROP TABLE IF EXISTS `test_plans_test_cases`;
DROP TABLE IF EXISTS `test_cases`;
DROP TABLE IF EXISTS `test_plans`;
DROP TABLE IF EXISTS `execution_types`;
DROP TABLE IF EXISTS `test_suites`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `project_statuses`;

/*
 * Statuses for projects.
 */
CREATE TABLE IF NOT EXISTS `project_statuses` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- default values -- */
INSERT INTO `project_statuses`(`id`, `name`, `description`) VALUES 
(1, 'active', 'Active project'),
(2, 'inactive', 'Inactive project');

/*
 * Projects table.
 */
CREATE TABLE IF NOT EXISTS `projects`(
  `id` INT(11) NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255),
  `project_status_id` INT(11) DEFAULT 1,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- constraints -- */
ALTER TABLE `projects` ADD CONSTRAINT `project_fk_project_status`
 FOREIGN KEY(`project_status_id`) REFERENCES `project_statuses`(`id`);
 
ALTER TABLE `projects` ADD CONSTRAINT `project_name_is_unique` UNIQUE(`name`);

/*
 * Test suites table.
 */
CREATE TABLE IF NOT EXISTS `test_suites`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `description` VARCHAR(255),
  PRIMARY KEY(`id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- constraints -- */
ALTER TABLE `test_suites` ADD CONSTRAINT `test_suite_fk_project`
 FOREIGN KEY(`project_id`) REFERENCES `projects`(`id`);
 
/*
 * Execution types for test cases.
 */ 
CREATE TABLE IF NOT EXISTS `execution_types`(
  `id` INT(11) NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `description` VARCHAR(255),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- default values -- */
INSERT INTO `execution_types`(`id`, `name`, `description`) VALUES 
(1, 'manual', 'Manual test'),
(2, 'automated', 'Automated test');

 /*
  * Test cases table. 
  */
CREATE TABLE IF NOT EXISTS `test_cases`(
  `id` INT(11) NOT NULL AUTO_INCREMENT, 
  `test_suite_id` INT(11) NOT NULL, 
  `project_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL, 
  `description` VARCHAR(255),
  `execution_type_id` INT(11) NOT NULL DEFAULT 1, /* 1, for manual test*/
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- constraints -- */
ALTER TABLE `test_cases` ADD CONSTRAINT `test_case_fk_execution_type` 
  FOREIGN KEY(`execution_type_id`) REFERENCES `execution_types`(`id`);

ALTER TABLE `test_cases` ADD CONSTRAINT `test_case_fk_project` 
  FOREIGN KEY(`project_id`) REFERENCES `projects`(`id`);
  
  ALTER TABLE `test_cases` ADD CONSTRAINT `test_case_fk_test_suite` 
  FOREIGN KEY(`test_suite_id`) REFERENCES `test_suites`(`id`);

 /*
  * Test plan table. 
  */
CREATE TABLE IF NOT EXISTS `test_plans`(
  `id` INT(11) NOT NULL AUTO_INCREMENT, 
  `project_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL, 
  `description` VARCHAR(255),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- constraints -- */
ALTER TABLE `test_plans` ADD CONSTRAINT `test_plan_fk_project` 
  FOREIGN KEY(`project_id`) REFERENCES `projects`(`id`);


 /*
  * Test plan x Test cases table. 
  */
CREATE TABLE IF NOT EXISTS `test_plans_test_cases`(
  `test_plan_id` INT(11) NOT NULL, 
  `test_case_id` INT(11) NOT NULL, 
  PRIMARY KEY(`test_case_id`, `test_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* -- constraints -- */
ALTER TABLE `test_plans_test_cases` ADD CONSTRAINT `test_plans_test_cases_fk_test_plan` 
  FOREIGN KEY(`test_plan_id`) REFERENCES `test_plans`(`id`);

ALTER TABLE `test_plans_test_cases` ADD CONSTRAINT `test_plans_test_cases_fk_test_case` 
  FOREIGN KEY(`test_case_id`) REFERENCES `test_cases`(`id`);