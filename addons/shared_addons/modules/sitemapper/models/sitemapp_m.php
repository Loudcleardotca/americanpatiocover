<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Navigation model for the navigation module.
 *
 * @package         PyroCMS
 * @subpackage      Sitemapper Module
 * @category        Third-party Modules
 * @author          Ramon Leenders - www.ramon-leenders.nl
 *
 */
class Sitemapp_m extends CI_Model
{
    protected $_table = 'sitemapper';

    public function get_all()
    {
        return $this->db
            ->select($this->_table . '.*, p.uri, p.title, m.slug, m.name')
            ->order_by('link_type', 'ASC')
            ->join('pages p', 'p.id = ' . $this->_table . '.page_id', 'left')
            ->join('modules m', 'm.id = ' . $this->_table . '.module_id', 'left')
            ->get($this->_table)
            ->result();
    }

    public function get_modules($slugs = array())
    {
        return $this->db
            ->select('id, name')
            ->where_not_in('slug', $slugs)
            ->where('is_frontend', 1)
            ->where('installed', 1)
            ->get('modules')
            ->result();
    }

    public function get_excluded_modules()
    {
        return $this->db
            ->select('slug')
            ->join('sitemapper se', 'se.module_id = modules.id')
            ->where('link_type', 'module')
            ->get('modules')
            ->result_array();
    }

    public function insert_exclude($input = array())
    {
        // Only do something when either both of the id's are set,
        // as they have to choose after the radio 'link_type' is set
        if ($input['module_id'] > 0 || $input['page_id'] > 0) {
            $this->db->insert($this->_table, $input);
            return $this->db->insert_id();
        }
        else {
            return false;
        }
    }

    public function delete($ids = array())
    {
        // First grab all available deletes
        $deletes = $this->db
            ->where_in('id', $ids)
            ->get($this->_table)
            ->result_array();
        if ($deletes) {
            $where_in = array();
            foreach ($deletes as $delete) {
                $where_in[] = $delete['id'];
            }
            // Delete the ones we really have!
            $this->db
                ->where_in('id', $where_in)
                ->delete($this->_table);
            return true;
        }
        else {
            return false;
        }
    }

    public function get_all_modules($additional_excludes = array())
    {
        $where            = 'id NOT IN
                 (SELECT module_id
                 FROM ' . $this->db->dbprefix($this->_table) . '
                 WHERE link_type = \'module\')';
        $query            = $this->db
            ->select('slug, name, is_core')
            ->where_not_in('slug', $additional_excludes)
            ->where($where)
            ->where('is_frontend', 1)
            ->where('installed', 1)
            ->get('modules')
            ->result();
        return (!$query) ? (FALSE) : ($query);
    }


    public function get_page_tree()
    {
        $where     = 'id NOT IN
                  (SELECT page_id
                  FROM ' . $this->db->dbprefix($this->_table) . '
                  WHERE link_type = \'page\')';
        $all_pages = $this->db
            ->select('id, parent_id, title, uri, updated_on')
            ->order_by('order')
            ->where('status', 'live')
            ->where($where)
            ->get('pages')
            ->result_array();
        if ($all_pages) {
            // we must reindex the array first
            foreach ($all_pages as $row) {
                $pages[$row['id']] = $row;
            }
            unset($all_pages);
            // build a multidimensional array of parent > children
            foreach ($pages as $row) {
                if (array_key_exists($row['parent_id'], $pages)) {
                    // add this page to the children array of the parent page
                    $pages[$row['parent_id']]['children'][] =& $pages[$row['id']];
                }
                // this is a root page
                if ($row['parent_id'] == 0) {
                    $page_array[] =& $pages[$row['id']];
                }
            }
        }
        return (isset($page_array)) ? ($page_array) : (null);
    }
}