<?php
require_once 'config/database.php';

/**
 * Admin class for handling authentication and admin operations
 */
class Admin {
    private $db;
    private $table = 'admins';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Authenticate admin user
     */
    public function authenticate($username, $password) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username AND is_active = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                return $admin;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error authenticating admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
    }

    /**
     * Login admin user
     */
    public function login($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['login_time'] = time();
    }

    /**
     * Logout admin user
     */
    public function logout() {
        session_unset();
        session_destroy();
    }

    /**
     * Get current admin info
     */
    public function getCurrentAdmin() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username'],
                'full_name' => $_SESSION['admin_name'],
                'email' => $_SESSION['admin_email']
            ];
        }
        return null;
    }

    /**
     * Require admin authentication
     */
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    /**
     * Get all admins
     */
    public function getAllAdmins() {
        try {
            $sql = "SELECT id, username, email, full_name, is_active, created_at FROM {$this->table} ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching admins: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update admin status
     */
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET is_active = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating admin status: " . $e->getMessage());
            return false;
        }
    }
}
?>
