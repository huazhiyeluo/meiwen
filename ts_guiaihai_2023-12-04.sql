# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 43.139.14.122 (MySQL 8.0.33)
# Database: ts_guiaihai
# Generation Time: 2023-12-04 08:54:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ts_article_gushi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_article_gushi`;

CREATE TABLE `ts_article_gushi` (
  `article_id` int NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cid1` smallint NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cid2` smallint NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `tags` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `cover` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片路径',
  `count_comment` smallint NOT NULL DEFAULT '0' COMMENT '评论数',
  `count_view` smallint NOT NULL DEFAULT '0' COMMENT '浏览数',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_mul_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否多页',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`article_id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_cid1` (`cid1`),
  KEY `idx_cid2` (`cid2`),
  KEY `idx_title` (`title`),
  KEY `idx_is_recommend` (`is_recommend`),
  KEY `idx_count_comment` (`count_comment`),
  KEY `idx_count_view` (`count_view`),
  KEY `idx_is_audit` (`is_audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章';



# Dump of table ts_article_meiwen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_article_meiwen`;

CREATE TABLE `ts_article_meiwen` (
  `article_id` int NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cid1` smallint NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cid2` smallint NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `tags` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `cover` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片路径',
  `count_comment` smallint NOT NULL DEFAULT '0' COMMENT '评论数',
  `count_view` smallint NOT NULL DEFAULT '0' COMMENT '浏览数',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_mul_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否多页',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`article_id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_cid1` (`cid1`),
  KEY `idx_cid2` (`cid2`),
  KEY `idx_title` (`title`),
  KEY `idx_is_recommend` (`is_recommend`),
  KEY `idx_count_comment` (`count_comment`),
  KEY `idx_count_view` (`count_view`),
  KEY `idx_is_audit` (`is_audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章';



# Dump of table ts_article_zuowen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_article_zuowen`;

CREATE TABLE `ts_article_zuowen` (
  `article_id` int NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cid1` smallint NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cid2` smallint NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `tags` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `cover` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片路径',
  `count_comment` smallint NOT NULL DEFAULT '0' COMMENT '评论数',
  `count_view` smallint NOT NULL DEFAULT '0' COMMENT '浏览数',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_mul_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否多页',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`article_id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_cid1` (`cid1`),
  KEY `idx_cid2` (`cid2`),
  KEY `idx_title` (`title`),
  KEY `idx_is_recommend` (`is_recommend`),
  KEY `idx_count_comment` (`count_comment`),
  KEY `idx_count_view` (`count_view`),
  KEY `idx_is_audit` (`is_audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章';



# Dump of table ts_book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_book`;

CREATE TABLE `ts_book` (
  `book_id` int NOT NULL AUTO_INCREMENT COMMENT '书本ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '创建者uid',
  `cid1` smallint NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cid2` smallint NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '作者',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `desc` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `tags` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `count_comment` smallint NOT NULL DEFAULT '0' COMMENT '评论数',
  `count_view` smallint NOT NULL DEFAULT '0' COMMENT '浏览数',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `meta_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`book_id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_cid1` (`cid1`),
  KEY `idx_cid2` (`cid2`),
  KEY `idx_title` (`title`),
  KEY `idx_count_comment` (`count_comment`),
  KEY `idx_count_view` (`count_view`),
  KEY `idx_is_recommend` (`is_recommend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='书名';



# Dump of table ts_book_chapter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_book_chapter`;

CREATE TABLE `ts_book_chapter` (
  `link_id` int NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `chapter_id` smallint NOT NULL DEFAULT '0' COMMENT '章节ID',
  `book_id` smallint NOT NULL DEFAULT '0' COMMENT '书本ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `tags` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `count_comment` smallint NOT NULL DEFAULT '0' COMMENT '评论数',
  `count_view` smallint NOT NULL DEFAULT '0' COMMENT '浏览数',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`link_id`),
  UNIQUE KEY `chapter_id_book_id` (`chapter_id`,`book_id`),
  KEY `idx_book_id` (`book_id`),
  KEY `idx_title` (`title`),
  KEY `idx_count_comment` (`count_comment`),
  KEY `idx_count_view` (`count_view`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='小说章节表';



# Dump of table ts_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_category`;

CREATE TABLE `ts_category` (
  `cid` smallint NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pcid` smallint NOT NULL DEFAULT '0' COMMENT '上级ID',
  `type` smallint NOT NULL DEFAULT '0' COMMENT '1书本 2美文 3故事 4作文',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `spider_title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称(爬虫)',
  `route_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由名称',
  `spider_route_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由名称(爬虫)',
  `meta_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort` smallint NOT NULL DEFAULT '0' COMMENT '排序',
  `is_delete` smallint NOT NULL DEFAULT '0' COMMENT '0 正常 1 删除',
  PRIMARY KEY (`cid`),
  KEY `idx_pcid` (`pcid`),
  KEY `idx_type` (`type`),
  KEY `idx_sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章分类';



# Dump of table ts_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_comment`;

CREATE TABLE `ts_comment` (
  `comment_id` int NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `article_id` int NOT NULL DEFAULT '0' COMMENT '文章ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 1图书 2美文 3故事 4作文',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '评论内容',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '评论时间',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章评论';



# Dump of table ts_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_links`;

CREATE TABLE `ts_links` (
  `link_id` int NOT NULL AUTO_INCREMENT COMMENT '友链ID',
  `sitename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '站点名',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `keywords` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `start_time` int NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int NOT NULL DEFAULT '0' COMMENT '结束时间',
  `sort` smallint NOT NULL DEFAULT '0' COMMENT '排序',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0 默认 1删除',
  PRIMARY KEY (`link_id`),
  KEY `idx_sort` (`sort`),
  KEY `idx_is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='友链管理';



# Dump of table ts_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag`;

CREATE TABLE `ts_tag` (
  `tag_id` int NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tagname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签名称',
  `count_user` int NOT NULL DEFAULT '0' COMMENT '统计用户标签',
  `count_book` int NOT NULL DEFAULT '0' COMMENT '统计书籍标签',
  `count_book_chapter` int NOT NULL DEFAULT '0' COMMENT '统计书籍章节标签',
  `count_weibo` int NOT NULL DEFAULT '0' COMMENT '统计书籍章节标签',
  `count_article` int NOT NULL DEFAULT '0' COMMENT '统计文章标签',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可用',
  `uptime` int NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `idx_tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='标签表';



# Dump of table ts_tag_article_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag_article_index`;

CREATE TABLE `ts_tag_article_index` (
  `article_id` int NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tag_id` int NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `article_id_tag_id` (`article_id`,`tag_id`),
  KEY `idx_article_id` (`article_id`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章标签关联';



# Dump of table ts_tag_book_chapter_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag_book_chapter_index`;

CREATE TABLE `ts_tag_book_chapter_index` (
  `chapter_id` int NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tag_id` int NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `chapter_id_tag_id` (`chapter_id`,`tag_id`),
  KEY `idx_chapter_id` (`chapter_id`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='书籍章节标签关联';



# Dump of table ts_tag_book_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag_book_index`;

CREATE TABLE `ts_tag_book_index` (
  `book_id` int NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tag_id` int NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `book_id_tag_id` (`book_id`,`tag_id`),
  KEY `idx_book_id` (`book_id`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='书籍标签关联';



# Dump of table ts_tag_user_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag_user_index`;

CREATE TABLE `ts_tag_user_index` (
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `tag_id` int NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `uid_tag_id` (`uid`,`tag_id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户标签关联';



# Dump of table ts_tag_weibo_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_tag_weibo_index`;

CREATE TABLE `ts_tag_weibo_index` (
  `weibo_id` int NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tag_id` int NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `weibo_id_tag_id` (`weibo_id`,`tag_id`),
  KEY `idx_weibo_id` (`weibo_id`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='书籍标签关联';



# Dump of table ts_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_user`;

CREATE TABLE `ts_user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `openid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户email',
  `phone` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '加点盐',
  `code` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱验证码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ts用户';



# Dump of table ts_user_follow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_user_follow`;

CREATE TABLE `ts_user_follow` (
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `uid_follow` int NOT NULL DEFAULT '0' COMMENT '被关注的用户ID',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '添加时间',
  UNIQUE KEY `uid_uid_follow` (`uid`,`uid_follow`),
  KEY `idx_uid` (`uid`),
  KEY `idx_uid_follow` (`uid_follow`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户关注跟随';



# Dump of table ts_user_gb
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_user_gb`;

CREATE TABLE `ts_user_gb` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '自增留言ID',
  `reid` int NOT NULL DEFAULT '0' COMMENT '回复留言ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '留言用户ID',
  `touid` int NOT NULL DEFAULT '0' COMMENT '被留言用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_touid` (`touid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='留言表';



# Dump of table ts_user_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_user_info`;

CREATE TABLE `ts_user_info` (
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `roleid` smallint NOT NULL DEFAULT '1' COMMENT '角色ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1男 2女 0 未知',
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Email邮箱',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电话号码',
  `photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `signed` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '签名',
  `address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址',
  `blog` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '博客',
  `about` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关于我',
  `allscore` int NOT NULL DEFAULT '0' COMMENT '所有获得的总积分',
  `count_score` int NOT NULL DEFAULT '0' COMMENT '统计积分',
  `count_follow` int NOT NULL DEFAULT '0' COMMENT '统计用户跟随的',
  `count_followed` int NOT NULL DEFAULT '0' COMMENT '统计用户被跟随的',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员',
  `is_audit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未验证1验证',
  `is_verifyphone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机号验证0未验证1验证',
  `is_renzheng` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证0未认证1认证',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `reg_time` int NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '注册IP',
  `login_time` int NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `login_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登陆IP',
  UNIQUE KEY `uid` (`uid`),
  KEY `idx_is_recommend` (`is_recommend`),
  KEY `idx_is_renzheng` (`is_renzheng`),
  KEY `idx_is_audit` (`is_audit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户信息';



# Dump of table ts_user_open
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_user_open`;

CREATE TABLE `ts_user_open` (
  `uid` int unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `sid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '连接网站（0.普通注册 1.QQ | 2.微博 |3百度）',
  `openid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `access_token` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'access_token',
  `uptime` int NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `idx_uid_sid` (`uid`,`sid`),
  UNIQUE KEY `idx_sid_openid` (`sid`,`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='连接登录Open设置';



# Dump of table ts_weibo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_weibo`;

CREATE TABLE `ts_weibo` (
  `weibo_id` int NOT NULL AUTO_INCREMENT COMMENT '自增唠叨ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `count_comment` int NOT NULL DEFAULT '0' COMMENT '评论数',
  `photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `addtime` int DEFAULT '0' COMMENT '新增时间',
  `uptime` int NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`weibo_id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='唠叨';



# Dump of table ts_weibo_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ts_weibo_comment`;

CREATE TABLE `ts_weibo_comment` (
  `commentid` int NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `weibo_id` int NOT NULL DEFAULT '0' COMMENT '唠叨ID',
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int NOT NULL DEFAULT '0' COMMENT '回复用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已阅',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `addtime` int NOT NULL DEFAULT '0' COMMENT '新增时间',
  PRIMARY KEY (`commentid`),
  KEY `idx_touid_is_read` (`touid`,`is_read`),
  KEY `idx_weibo_id` (`weibo_id`),
  KEY `idx_is_audit` (`is_audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='唠叨回复';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
