<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Production_order_model extends CI_Model
{
	/**
	 * FUNGSI DIPERBARUI: Menerima parameter limit dan offset untuk paginasi.
	 */
	public function get_wip_data($line = null, $limit = null, $offset = null)
	{
		$required_processes = 3;

		$this->db->select('
            h.prod_header_id, 
            h.prod_order_no,
            i.item_code,
            i.item_name,
            h.quantity_to_produce,
            h.production_line,
            h.status,
            h.qty_smt_ok,
            h.qty_bpr_ok,
            h.qty_dms_ok,
            COALESCE(completed.total_completed, 0) as completed_units
        ');
		$this->db->from('t_production_order_header h');
		$this->db->join('m_items i', 'h.item_id = i.item_id', 'left');
		$this->db->join(
			"(SELECT prod_header_id, COUNT(*) as total_completed 
              FROM (
                  SELECT prod_header_id
                  FROM t_sfc_logs
                  WHERE scan_status = 'OK'
                  GROUP BY prod_header_id, serial_number
                  HAVING COUNT(DISTINCT process_name) = {$required_processes}
              ) AS sub
              GROUP BY prod_header_id) AS completed",
			'h.prod_header_id = completed.prod_header_id',
			'left'
		);

		$this->db->where_in('h.status', ['Open', 'In Progress', 'Materials Issued', 'BPR Validated', 'DMS Validated', 'Setup Validated', 'Ready for Production', 'Awaiting FQA']);
		if ($line && $line !== 'all') {
			$this->db->where('h.production_line', $line);
		}

		if ($limit !== null && $offset !== null) {
			$this->db->limit($limit, $offset);
		}

		$this->db->order_by('h.prod_order_date', 'DESC');
		$this->db->order_by('h.production_line', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * FUNGSI BARU: Menghitung total data WIP untuk paginasi.
	 */
	public function count_wip_data($line = null)
	{
		$this->db->from('t_production_order_header h');
		$this->db->where_in('h.status', ['Open', 'In Progress', 'Materials Issued', 'BPR Validated', 'DMS Validated', 'Setup Validated', 'Ready for Production', 'Awaiting FQA']);
		if ($line && $line !== 'all') {
			$this->db->where('h.production_line', $line);
		}
		return $this->db->count_all_results();
	}

	public function get_active_lines()
	{
		$this->db->select('production_line');
		$this->db->from('t_production_order_header');
		$this->db->where_in('status', ['In Progress', 'Materials Issued', 'Ready for Production', 'Awaiting FQA']);
		$this->db->where('production_line IS NOT NULL');
		$this->db->distinct();
		$this->db->order_by('production_line', 'ASC');
		$query = $this->db->get();
		return array_column($query->result_array(), 'production_line');
	}

	public function get_pivoted_serials_by_wo($prod_header_id, $limit, $offset, $search = null)
	{
		$search_sql = $search ? "AND serial_number LIKE '%" . $this->db->escape_like_str($search) . "%'" : "";

		$sql = "
            SELECT
                serial_number,
                MAX(CASE WHEN process_name = 'SMT' AND scan_status = 'OK' THEN scanned_at ELSE NULL END) AS smt_time,
                MAX(CASE WHEN process_name = 'BPR' AND scan_status = 'OK' THEN scanned_at ELSE NULL END) AS bpr_time,
                MAX(CASE WHEN process_name = 'DMS' AND scan_status = 'OK' THEN scanned_at ELSE NULL END) AS dms_time
            FROM t_sfc_logs
            WHERE prod_header_id = ? {$search_sql}
            GROUP BY serial_number
            ORDER BY serial_number ASC
            LIMIT ? OFFSET ?
        ";

		return $this->db->query($sql, [$prod_header_id, (int)$limit, (int)$offset])->result_array();
	}

	public function count_unique_serials_by_wo($prod_header_id, $search = null)
	{
		$this->db->select('COUNT(DISTINCT serial_number) as total');
		$this->db->from('t_sfc_logs');
		$this->db->where('prod_header_id', $prod_header_id);
		if ($search) {
			$this->db->like('serial_number', $search);
		}
		return $this->db->get()->row()->total;
	}

	public function get_wo_by_number($prod_order_no)
	{
		return $this->db->get_where('t_production_order_header', ['prod_order_no' => $prod_order_no])->row();
	}

	public function count_completed_units_by_wo($prod_header_id)
	{
		$required_processes = 3;

		$sql = "
			SELECT COUNT(*) as total
			FROM (
				SELECT 1
				FROM t_sfc_logs
				WHERE prod_header_id = ? AND scan_status = 'OK'
				GROUP BY serial_number
				HAVING COUNT(DISTINCT process_name) = ?
			) AS completed_serials
		";

		$query = $this->db->query($sql, [$prod_header_id, $required_processes]);
		$result = $query->row();

		return $result ? (int)$result->total : 0;
	}
}
