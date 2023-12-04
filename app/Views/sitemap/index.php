<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container ts-other-map">
    <?php foreach ($newCategorys as $type => $v) {?>
    <h2><?=getFirstName($type)?>分类</h2>

    <?php foreach ($v as $val) {?>
    <div class="title">
        <h3 class="nav-title"><a href="<?=my_site_url($val['route_name'])?>" title="<?=$val['title']?>"><?=$val['title']?></a></h3>
    </div>
    <?php if(isset($val['child'])) {?>
    <div class="row nav-list" style="padding:5px;">
        <?php foreach ($val['child'] as $key => $child) {?>
        <div class="col-4 col-md-2 mb-3"><a href="<?=my_site_url($child['route_name'])?>" title="<?=$child['title']?>"><?=$child['title']?></a></div>
        <?php }?>
    </div>
    <?php }?>
    <?php }?>
    <?php }?>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>