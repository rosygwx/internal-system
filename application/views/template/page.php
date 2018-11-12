<div style='float:right; clear:none;' class='pager'>
<!--<form action="<?php if(isset($page['action'])){echo $page['action'];}?>" method='get'>-->
			<strong><?=$page['total']?></strong> records in total，
			<strong><?=$page['current']?>/<?=$page['pagecount']?><?php if($page['current']>1){?></strong> 
			<a href='<?=$page['first']?>'>First Page</a> 
			<a href='<?=$page['front']?>'>Pre</a><?php }if($page['current'] < $page['pagecount']){?> 
			<a href='<?=$page['next']?>' >Next</a>
			<a href='<?=$page['last']?>' >Last Page</a><?php }?>
			<input type='text' id="page" name='page' style='text-align:center;width:30px;'>
			<input id="go" type='submit' value='GO!'>
			<!--</form>-->
				</div>
<script>
<?php

$url = $_SERVER['REQUEST_URI'];
if(strpos($url ,'?'))
{

	$url = str_replace("page=".$page['current'],"",$url);
	if(isset($_GET['page']))
	{
		$url = str_replace("&page=".$_GET['page'],"",$url);
	}
	$url = str_replace("&&","&",$url);
	$url = str_replace("&&","&",$url);

	$url = $url."&page=";
	$url = str_replace("&&","&",$url);
}
else
{
	$url = $url."?page=";
}
?>
$("#go").click(function(){
	var page = $("#page").val();
	var total = <?=$page['pagecount']?>;
	if(page > total)page=total;
	//var num = Number(page);
	//alert(num);
	if( page > 0)
	{
		window.location.href = '<?php echo $url;?>' + page;
		
	}
	else
	{
		alert('Have no such page.');
		$("#page").attr('value','');
	}

})
</script>