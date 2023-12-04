<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="<?=site_url()?>static/bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
function search()
{
	location.href = "<?=my_site_url('search');?>?keyword="+$("#searchContent").val()
}
</script>
<?=$tongji?>
