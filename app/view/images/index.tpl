{extends file="base.tpl"}
{block name="body"}
<h1 class="page-header-left">Images <a href="/docker/api/containers/json?all=1" target="_blank"></a></h1>

<table class="table table-striped table-hover">
	<thead>
	<tr>
		<th>Id</th>
		<th>RepoTags</th>
		<th>Created</th>
		<th>VirtualSize</th>
		<th>Operation</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$images item=item}
	<tr image-id="{$item.Id}">
		<td><a href="/docker/api/images/{$item.Id}/json" target="_blank">{substr($item.Id, 0, 12)}</a></td>
		<td>{implode('<br>', $item.RepoTags)}</td>
		<td>{date('Y-m-d H:i:s', $item.Created)}</td>
		<td>{round($item.VirtualSize/(1024*1024), 2)} MB</td>
		<td>
			<button class="btn btn-danger" data-click="images-delete">Delete</button>
		</td>
	</tr>
	{/foreach}
	</tbody>
</table>
{/block}

{block name="page_scripts"}
<script src="/static/docker/images.js"></script>
{/block}