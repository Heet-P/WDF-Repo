<?php
require_once 'config/database.php';

/**
 * Event class for handling CRUD operations
 */
class Event {
    private $db;
    private $table = 'events';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Create a new event
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (title, description, event_date, event_time, location, status, max_participants) 
                    VALUES (:title, :description, :event_date, :event_time, :location, :status, :max_participants)";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':event_date', $data['event_date']);
            $stmt->bindParam(':event_time', $data['event_time']);
            $stmt->bindParam(':location', $data['location']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':max_participants', $data['max_participants']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating event: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Read all events
     */
    public function readAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY event_date ASC, event_time ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error reading events: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Read a single event by ID
     */
    public function readById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error reading event: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an event
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET title = :title, description = :description, event_date = :event_date, 
                        event_time = :event_time, location = :location, status = :status, 
                        max_participants = :max_participants 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':event_date', $data['event_date']);
            $stmt->bindParam(':event_time', $data['event_time']);
            $stmt->bindParam(':location', $data['location']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':max_participants', $data['max_participants']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an event
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get events by status
     */
    public function getByStatus($status) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY event_date ASC, event_time ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error reading events by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update event status
     */
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating event status: " . $e->getMessage());
            return false;
        }
    }
}
?>
