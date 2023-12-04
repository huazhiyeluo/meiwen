<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <div class="card">
    <div class="card-header"><a class="text-secondary" target="_blank" href="<?=site_url() ?>"><?=$baseConfig['sitename']?></a>网提示<i class="fa fa-warning text-warning"></i></div>
    <div class="card-body main">
        <p><?=$msg?></p>
        <p><a class="btn btn-sm btn-outline-secondary" href="<?=$url?>"><?=$button?></a></p>
    </div>
    </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>