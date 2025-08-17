<?php
require_once 'BaseController.php';

class DashboardController extends BaseController {

    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Dashboard',
            'stats' => $this->getDashboardStats(),
            'recent_orders' => $this->getRecentOrders(),
            'table_status' => $this->getTableStatus()
        ];

        $this->view('dashboard/index', $data);
    }

    private function getDashboardStats() {
        $stats = [];

        try {
            // Today's sales
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) as order_count, COALESCE(SUM(total), 0) as total_sales 
                 FROM orders 
                 WHERE DATE(created_at) = CURDATE() AND status = 'paid'"
            );
            $stmt->execute();
            $sales = $stmt->fetch();
            
            $stats['today_orders'] = $sales['order_count'];
            $stats['today_sales'] = $sales['total_sales'];

            // Active orders
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM orders 
                 WHERE status IN ('pending', 'preparing', 'ready')"
            );
            $stmt->execute();
            $stats['active_orders'] = $stmt->fetchColumn();

            // Available tables
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM restaurant_tables 
                 WHERE status = 'available' AND is_active = 1"
            );
            $stmt->execute();
            $stats['available_tables'] = $stmt->fetchColumn();

            // Low stock items
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM inventory_items 
                 WHERE current_stock <= min_stock AND is_active = 1"
            );
            $stmt->execute();
            $stats['low_stock_items'] = $stmt->fetchColumn();

            // This week's sales
            $stmt = $this->db->prepare(
                "SELECT COALESCE(SUM(total), 0) as total_sales 
                 FROM orders 
                 WHERE WEEK(created_at) = WEEK(NOW()) 
                 AND YEAR(created_at) = YEAR(NOW()) 
                 AND status = 'paid'"
            );
            $stmt->execute();
            $stats['week_sales'] = $stmt->fetchColumn();

            // This month's sales
            $stmt = $this->db->prepare(
                "SELECT COALESCE(SUM(total), 0) as total_sales 
                 FROM orders 
                 WHERE MONTH(created_at) = MONTH(NOW()) 
                 AND YEAR(created_at) = YEAR(NOW()) 
                 AND status = 'paid'"
            );
            $stmt->execute();
            $stats['month_sales'] = $stmt->fetchColumn();

        } catch (Exception $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            $stats = [
                'today_orders' => 0,
                'today_sales' => 0,
                'active_orders' => 0,
                'available_tables' => 0,
                'low_stock_items' => 0,
                'week_sales' => 0,
                'month_sales' => 0
            ];
        }

        return $stats;
    }

    private function getRecentOrders() {
        try {
            $stmt = $this->db->prepare(
                "SELECT o.id, o.order_type, o.status, o.total, o.created_at,
                        t.table_number,
                        CONCAT(u.first_name, ' ', u.last_name) as waiter_name
                 FROM orders o
                 LEFT JOIN restaurant_tables t ON o.table_id = t.id
                 LEFT JOIN users u ON o.waiter_id = u.id
                 ORDER BY o.created_at DESC
                 LIMIT 10"
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Recent orders error: " . $e->getMessage());
            return [];
        }
    }

    private function getTableStatus() {
        try {
            $stmt = $this->db->prepare(
                "SELECT status, COUNT(*) as count
                 FROM restaurant_tables 
                 WHERE is_active = 1
                 GROUP BY status"
            );
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $status = [
                'available' => 0,
                'occupied' => 0,
                'reserved' => 0,
                'cleaning' => 0
            ];
            
            foreach ($results as $row) {
                $status[$row['status']] = $row['count'];
            }
            
            return $status;
        } catch (Exception $e) {
            error_log("Table status error: " . $e->getMessage());
            return [
                'available' => 0,
                'occupied' => 0,
                'reserved' => 0,
                'cleaning' => 0
            ];
        }
    }
}
?>