<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?=my_site_url('about')?>"
                   class="list-group-item <?=$tag == 'about' ? 'list-group-item-dark ' : '';?>">关于我们</a>
                <a href="<?=my_site_url('contact')?>"
                   class="list-group-item <?=$tag == 'contact' ? 'list-group-item-dark ' : '';?>">联系我们</a>
                <a href="<?=my_site_url('terms')?>"
                   class="list-group-item <?=$tag == 'terms' ? 'list-group-item-dark ' : '';?>">用户条款</a>
                <a href="<?=my_site_url('publish')?>"
                   class="list-group-item <?=$tag == 'publish' ? 'list-group-item-dark ' : '';?>">投稿指南</a>
                <a href="<?=my_site_url('disclaimer')?>"
                   class="list-group-item <?=$tag == 'disclaimer' ? 'list-group-item-dark ' : '';?>">免责申明</a>
            </div>
        </div>
        <div class="col-md-9">
            <?=view("index/info/" . $tag)?>
        </div>
    </div>
</div>
<?= $this->include('common/footer') ?>
<?= $this->include('common/footer-js') ?>
