<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Class Menu_model
 * Model untuk mengelola dan membangun struktur menu.
 *
 * @property CI_DB_query_builder $db
 */
class Menu_model extends CI_Model
{

	/**
	 * Mengambil semua menu yang bisa diakses oleh user berdasarkan perannya.
	 * @param array $permissions Daftar permission yang dimiliki user.
	 * @param int $role_id ID peran user.
	 * @return array Struktur menu dalam bentuk hirarki.
	 */
	public function get_accessible_menus($permissions, $role_id)
	{
		// Menggunakan tabel 'portal_menus'
		$this->db->from('portal_menus');
		$this->db->where('is_active', 1);
		$this->db->order_by('parent_id', 'ASC');
		$this->db->order_by('sort_order', 'ASC');
		$all_menus = $this->db->get()->result_array();

		$accessible_menus = [];
		// Jika role adalah Administrator (ID 1), tampilkan semua menu
		if ($role_id == 1) {
			$accessible_menus = $all_menus;
		} else {
			// Jika bukan admin, filter berdasarkan hak akses
			foreach ($all_menus as $menu) {
				if (empty($menu['permission_key']) || in_array($menu['permission_key'], $permissions)) {
					$accessible_menus[] = $menu;
				}
			}
		}

		return $this->build_menu_tree($accessible_menus);
	}

	/**
	 * Fungsi rekursif untuk membangun menu menjadi struktur tree (parent-child).
	 * @param array &$elements Daftar menu flat.
	 * @param int $parentId ID dari parent menu.
	 * @return array
	 */
	private function build_menu_tree(array &$elements, $parentId = 0)
	{
		$branch = [];
		foreach ($elements as &$element) {
			if ($element['parent_id'] == $parentId) {
				$children = $this->build_menu_tree($elements, $element['menu_id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
				unset($element);
			}
		}
		return $branch;
	}
}
