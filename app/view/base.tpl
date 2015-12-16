{if !$XPJAX}<!DOCTYPE html>
<html>
<head>
<title>PHP-DOCKER-UI</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="/static/bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/static/common/css/doc.min.css" rel="stylesheet" media="screen">
<link href="/static/common/css/sweet_animate.css" rel="stylesheet" media="screen">
{block name="page_styles"}{/block}
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
	<div class="container">
		<div class="navbar-header">
			<a href="/" class="navbar-brand">Docker UI</a>
		</div>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="nav navbar-nav">
				<li{if $_meta.req_id=='default.containers.index'} class="active"{/if}>
					<a href="/containers">Containers</a>
				</li>
				<li{if $_meta.req_id=='default.images.index'} class="active"{/if}>
					<a href="/images">Images</a>
				</li>
			</ul>
		</nav>
	</div>
</header>
<div class="container bs-docs-container">
{/if}
{if $XPJAX}
<!--[pjax]-->
{capture "__pjax__"}
{/if}
  {block name="body"}
  {/block}
{if $XPJAX}
{/capture}
<script>
INIT({
  container: '{$_pjax}', 
  html: '{$smarty.capture.__pjax__|escape:'javascript'}',
  module: '{$module}'
});
</script>
<!--[/pjax]-->
{/if}
{if !$XPJAX}
</div>

<footer class="bs-docs-footer" role="contentinfo">
	<div class="container">
		<ul class="bs-docs-footer-links muted">
			<li>Currently v0.0.1</li>
			<li>·</li>
			<li><a href="http://www.wukezhan.com">copyright&copy;www.wukezhan.com</a></li>
		</ul>
	</div>
</footer>
<script src="/static/common/js/jquery-1.10.2.min.js"></script>
<script src="/static/bootstrap-3.2.0/js/bootstrap.min.js"></script>
<script src="/static/modal/min.js"></script>
{block name="page_scripts"}{/block}
</body>
</html>{/if}