<?php namespace App\Models;

use CodeIgniter\Model;

class SearchModel extends Model
{
    protected $table = 'ts_article_zuowen';
    protected $primaryKey = 'article_id';

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getSearchAll($keyword, $page, $pageSize)
    {

        $limit = ($page - 1) * $pageSize . ',' . $pageSize;

        $where = '';
        if ($keyword) {
            $where = " title like '%$keyword%'";
        }

        $sql1 = "select book_id as id,0 as b_id,cid1,cid2,title,`addtime`,count_view as num,1 as `oid` from ts_book where $where";
        $sql5 = "select chapter_id as id,book_id as b_id,0 as `cid1`,0 as `cid2`,title,`addtime`,count_view as num,5 as `oid` from ts_book_chapter where $where";
        $sql2 = "select article_id as id,0 as b_id,cid1,cid2,title,`addtime`,count_view as num,2 as `oid` from ts_article_meiwen where is_audit = 1 and $where";
        $sql3 = "select article_id as id,0 as b_id,cid1,cid2,title,`addtime`,count_view as num,3 as `oid` from ts_article_gushi where is_audit = 1 and $where";
        $sql4 = "select article_id as id,0 as b_id,cid1,cid2,title,`addtime`,count_view as num,4 as `oid` from ts_article_zuowen where is_audit = 1 and $where";

        $sql = $sql1 . ' union ' . $sql2 . ' union ' . $sql3 . ' union ' . $sql4 . ' union ' . $sql5 . "  order by addtime desc limit $limit";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();

        $sqlCount1 = "select book_id as id from ts_book where $where";
        $sqlCount5 = "select chapter_id as id from ts_book_chapter where $where";
        $sqlCount2 = "select article_id as id from ts_article_meiwen where is_audit = 1 and $where";
        $sqlCount3 = "select article_id as id from ts_article_gushi where is_audit = 1 and $where";
        $sqlCount4 = "select article_id as id from ts_article_zuowen where is_audit = 1 and $where";

        $sqlCount = 'select count(*) as num from (' . $sqlCount1 . ' union ' . $sqlCount2 . ' union ' . $sqlCount3 . ' union ' . $sqlCount4 . ' union ' . $sqlCount5 . ') as a';
        $query = $this->db->query($sqlCount);
        $totalArr = $query->getRowArray();

        return ['list' => $data, 'total' => $totalArr['num']];
    }

    public function getSearchBook($keyword, $page, $pageSize)
    {
        $limit = ($page - 1) * $pageSize . ',' . $pageSize;

        $where = '';
        if ($keyword) {
            $where = " title like '%$keyword%'";
        }

        $sql1 = "select book_id as id,0 as b_id,cid1,cid2,title,`addtime`,count_view as num,1 as `oid` from ts_book where $where";
        $sql2 = "select chapter_id as id,book_id as b_id,0 as `cid1`,0 as `cid2`,title,`addtime`,count_view as num,5 as `oid` from ts_book_chapter where $where";

        $sql = $sql1 . ' union ' . $sql2 . "  order by oid asc,id desc limit $limit";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();

        $sqlCount1 = "select book_id as id from ts_book where $where";
        $sqlCount2 = "select chapter_id as id from ts_book_chapter where $where";

        $sqlCount = 'select count(*) as num from (' . $sqlCount1 . ' union ' . $sqlCount2 . ') as a';
        $query = $this->db->query($sqlCount);
        $totalArr = $query->getRowArray();

        return ['list' => $data, 'total' => $totalArr['num']];
    }

    public function getSearchMeiwen($keyword, $page, $pageSize)
    {

        $limit = ($page - 1) * $pageSize . ',' . $pageSize;

        $where = '';
        if ($keyword) {
            $where = " title like '%$keyword%'";
        }

        $sql = "select article_id as id,cid1,cid2,title,`addtime`,count_view as num,2 as `oid` from ts_article_meiwen where is_audit = 1 and $where order by id desc limit $limit";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();

        $sqlCount = "select count(*) as num from ts_article_meiwen where is_audit = 1 and $where";
        $query = $this->db->query($sqlCount);
        $totalArr = $query->getRowArray();

        return ['list' => $data, 'total' => $totalArr['num']];
    }

    public function getSearchGushi($keyword, $page, $pageSize)
    {

        $limit = ($page - 1) * $pageSize . ',' . $pageSize;

        $where = '';
        if ($keyword) {
            $where = " title like '%$keyword%'";
        }

        $sql = "select article_id as id,cid1,cid2,title,`addtime`,count_view as num,3 as `oid` from ts_article_gushi where is_audit = 1 and $where order by id desc limit $limit";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();

        $sqlCount = "select count(*) as num from ts_article_gushi where is_audit = 1 and $where";
        $query = $this->db->query($sqlCount);
        $totalArr = $query->getRowArray();

        return ['list' => $data, 'total' => $totalArr['num']];
    }

    public function getSearchZuowen($keyword, $page, $pageSize)
    {

        $limit = ($page - 1) * $pageSize . ',' . $pageSize;

        $where = '';
        if ($keyword) {
            $where = " title like '%$keyword%'";
        }

        $sql = "select article_id as id,cid1,cid2,title,`addtime`,count_view as num,4 as `oid` from ts_article_zuowen where is_audit = 1 and $where order by id desc limit $limit";
        $query = $this->db->query($sql);
        $data = $query->getResultArray();

        $sqlCount = "select count(*) as num from ts_article_zuowen where is_audit = 1 and $where";
        $query = $this->db->query($sqlCount);
        $totalArr = $query->getRowArray();

        return ['list' => $data, 'total' => $totalArr['num']];
    }
}
