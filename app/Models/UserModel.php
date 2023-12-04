<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'ts_user_info';
    protected $primaryKey = 'uid';

    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    protected function beforeInsert(array $data)
    {
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }

//--------------------------------------ts_user_info-----------------------------------------
    /**
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getUserInfo($uid, $isUpdate = 0)
    {
        $cache_user_key = 'CACHE_USER_IFNO_' . $uid;
        $cache = \Config\Services::cache();
        $userInfo = $cache->get($cache_user_key);
        if (!$userInfo || $isUpdate) {
            $builder = $this->db->table('ts_user_info');
            $builder->select('*');
            $builder->where('uid', $uid);
            $query = $builder->get();

            $userInfo = $query->getRowArray();
            if (!$userInfo['photo']) {
                $userInfo['photo'] = site_url() . 'static/image/user_large.png';
            }
            $cache->save($cache_user_key, $userInfo);
        }
        return $userInfo;
    }

    public function addUserInfo($data)
    {
        $builder = $this->db->table('ts_user_info');
        $builder->insert($data);
        return $this->db->affectedRows();
    }

    public function editUserInfo($data, $where)
    {
        $builder = $this->db->table('ts_user_info');
        $builder->where($where);
        $builder->update($data);
        $this->getUserInfo($where['uid'],1);
        return $this->db->affectedRows();
    }

//--------------------------------------ts_user_open-----------------------------------------
    /**
     * [getSpiderOne description]
     * @param  [type] $t_id  [description]
     * @param  array  $where [description]
     * @param  array  $order [description]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function getUserOpenOne($where = [], $field = "*")
    {
        $builder = $this->db->table('ts_user_open');
        $builder->select($field);
        if ($where) {
            foreach ($where as $k => $v) {
                $builder->where($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    public function addUserOpen($data)
    {
        $builder = $this->db->table('ts_user_open');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editUserOpen($data, $where)
    {
        $builder = $this->db->table('ts_user_open');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

//--------------------------------------ts_user-----------------------------------------
    /**
     * [getSpiderOne description]
     * @param  [type] $t_id  [description]
     * @param  array  $where [description]
     * @param  array  $order [description]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function getUserOne($where = [], $field = "*")
    {
        $builder = $this->db->table('ts_user');
        $builder->select($field);
        if ($where) {
            foreach ($where as $k => $v) {
                $builder->where($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    public function addUser($data)
    {
        $builder = $this->db->table('ts_user');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editUser($data, $where)
    {
        $builder = $this->db->table('ts_user');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delUser($where)
    {
        $builder = $this->db->table('ts_user');
        $builder->delete($where);
        return $this->db->affectedRows();
    }

//--------------------------------------ts_user_follow-----------------------------------------
    /**
     * [getSpiderOne description]
     * @param  [type] $t_id  [description]
     * @param  array  $where [description]
     * @param  array  $order [description]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function getUserFollow($where = [], $field = "*")
    {
        $builder = $this->db->table('ts_user_follow');
        $builder->select($field);
        if ($where) {
            foreach ($where as $k => $v) {
                $builder->where($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    /**
     * [getArticles description]  多个留言
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getUserFollows($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_user_follow');
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

    public function addUserFollow($data)
    {
        $builder = $this->db->table('ts_user_follow');
        $builder->insert($data);
        return $this->db->affectedRows();
    }

    public function editUserFollow($data, $where)
    {
        $builder = $this->db->table('ts_user_follow');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delUserFollow($where)
    {
        $builder = $this->db->table('ts_user_follow');
        $builder->delete($where);
        return $this->db->affectedRows();
    }

//--------------------------------------ts_user_gb-----------------------------------------
    /**
     * [getArticles description]  多个留言
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getUserGbs($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_user_gb');
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

    public function addUserGb($data)
    {
        $builder = $this->db->table('ts_user_gb');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editUserGb($data, $where)
    {
        $builder = $this->db->table('ts_user_gb');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delUserGb($where)
    {
        $builder = $this->db->table('ts_user_gb');
        $builder->delete($where);
        return $this->db->affectedRows();
    }
}
