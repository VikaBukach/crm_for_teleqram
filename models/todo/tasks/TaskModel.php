<?php

namespace models\todo\tasks;
use models\Database;

class TaskModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        try {
            $result = $this->db->query("SELECT 1 FROM `todo_list` LIMIT 1");
        } catch (\PDOException $e) {
            $this->createTable();
        }
    }

    public function createTable()
    {
            $query = "CREATE TABLE IF NOT EXISTS `todo_list`(
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `category_id` INT,
            `status` ENUM('new', 'in_progress', 'completed', 'on_hold', 'cancelled') NOT NULL,
            `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
            `assigned_to` INT, 
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `finish_date` DATETIME,
            `completed_at` DATETIME,
            `reminder_at` DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES todo_category(id) ON DELETE SET NULL,
            FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
        )";

        try {
            $this->db->exec($query);
            return true;
        } catch (\PDOException $e) {
            error_log('Database error:' . $e->getMessage());
            echo 'Database error:' . $e->getMessage();
            return false;
        }
    }


    public function getAllTasks()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM todo_list");
            $todo_list = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $todo_list[] = $row;
            }

            return $todo_list ?: [];
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function createTask($data)
    {

        $query = "INSERT INTO todo_list (user_id, title, category_id, status, priority, finish_date) VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$data['user_id'], $data['title'], $data['category_id'], $data['status'], $data['priority'], $data['finish_date']]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getTaskById($id)
    {

        $query = "SELECT * FROM todo_list WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $todo_task = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $todo_task ? $todo_task : false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function updateCategory($id, $title, $description, $usability)
    {
        $query = "UPDATE todo_category SET title = ?, description = ?, usability = ? WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $description, $usability, $id]);

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM todo_category WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
        }
        return false;
    }
}


