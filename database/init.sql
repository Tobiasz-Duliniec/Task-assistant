CREATE TABLE tasks (
  ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  description varchar(1023) NOT NULL,
  task_status ENUM("Not started", "Started", "Finished") DEFAULT "Not started",
  deadline date DEFAULT NULL,
  UNIQUE (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

