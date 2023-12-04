<?php namespace App\Models;

use CodeIgniter\Model;

class ChapterModel extends Model
{
    protected $table = 'ts_book_chapter';
    protected $primaryKey = 'chapter_id';

    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected $db;
    protected $db_book;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db_book = db_connect('book');
    }

    protected function beforeInsert(array $data)
    {
        $data['data']['addtime'] = time();
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }

    /**
     * [getArticles description] 单个文章
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getChapter($condition = [], $field = "*")
    {
        $builder = $this->db->table('ts_book_chapter');
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
    public function getChapters($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_book_chapter');
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

        if (isset($condition['group']) && $condition['group']) {
            $builder->groupBy($condition['group']);
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

    /**
     * [getArticles description]  多个文章-带分页
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getChaptersList($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_book_chapter');
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

        $builderTotal = clone $builder;

        $total = $builderTotal->countAllResults();

        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $list = $query->getResultArray();
        return ['total' => $total, 'list' => $list];
    }

    public function getChaptersQuery($condition = [], $field = "*")
    {
        $sql = "select chapter_id,book_id,title,addtime,count_comment,count_view from ts_book_chapter where link_id in(select link_id from (select link_id from (select max(link_id) as link_id from ts_book_chapter group by book_id) as a order by link_id desc limit 8) as b);";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();
        return $data;
    }

    /**
     * [addArticle description] 新增书籍
     * @param [type] $data [description]
     */
    public function addChapter($data)
    {
        $builder = $this->db->table('ts_book_chapter');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editChapter($data, $where)
    {
        $builder = $this->db->table('ts_book_chapter');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    /**
     * [addArticle description] 新增文章 -内容
     * @param [type] $data [description]
     */
    public function addChapterContent($link_id, $data)
    {
        $index = intval($link_id / 4000);
        $builder = $this->db_book->table('ts_content_' . $index);
        $builder->insert($data);
        return $this->db_book->affectedRows();
    }

    /**
     * [getArticleContent description] - 文章 - 内容
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getChapterContent($link_id)
    {
        $index = intval($link_id / 4000);
        $builder = $this->db_book->table('ts_content_' . $index);
        $builder->select('*');
        $builder->where('link_id', $link_id);
        $query = $builder->get();
        $data = $query->getRowArray();
        return $data;
    }

}
