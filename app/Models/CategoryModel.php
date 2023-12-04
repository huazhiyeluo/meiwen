<?php namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{

    /**
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getCategory($condition = [], $field = "*")
    {
        $builder = $this->db->table('ts_category');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    /**
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getCategorys($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_category');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }
        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $data = $query->getResultArray();

        return $data;
    }

    public function addCategory($data)
    {
        $builder = $this->db->table('ts_category');

        if ($data['title']) {
            $pinyin = new \App\ThirdParty\Pinyin();
            $data['route_name'] = $pinyin->getPinyin($data['title']);
            $data['spider_route_name'] = $data['route_name'];
        }
        $data['spider_title'] = $data['title'];
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editCategory($data, $where)
    {
        $builder = $this->db->table('ts_category');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delCategory($where)
    {
        $builder = $this->db->table('ts_category');
        $builder->delete($where);
        return $this->db->affectedRows();
    }

    public function loadCategory($cidName1, $cidName2, $type = 1)
    {
        $cid1 = 0;
        $cid2 = 0;

        if ($cidName1) {
            $catData = $this->getCategory(['where' => ['pcid' => 0, 'type' => $type, 'spider_title' => $cidName1]], 'cid');

            if ($catData) {
                $cid1 = $catData['cid'];
            } else {
                $cid1 = $this->addCategory(['title' => $cidName1, 'type' => $type, 'pcid' => 0]);
            }
            if ($cidName2) {
                $catData = $this->getCategory(['where' => ['pcid' => $cid1, 'type' => $type, 'spider_title' => $cidName2]], 'cid');
                if ($catData) {
                    $cid2 = $catData['cid'];
                } else {
                    $cid2 = $this->addCategory(['title' => $cidName2, 'type' => $type, 'pcid' => $cid1]);
                }

            }
        }
        return array('cid1' => $cid1, 'cid2' => $cid2);
    }

    public function getCategorysBySql($sql)
    {
        $query = $this->db->query($sql);
        $data = $query->getResultArray();
        return $data;
    }

}
