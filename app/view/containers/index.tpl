{extends file="base.tpl"}
{block name="body"}
	<h1 class="page-header-left ng-binding">Containers <a href="/docker/api/containers/json?all=1" target="_blank"></a></h1>

	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Id</th>
			<th>Names</th>
			<th>Image</th>
			{**<th>Command</th>**}
			<th>Created</th>
			<th>Status</th>
			<th>Operation</th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$containers item=item}
			<tr row-id="{$item.Id}">
				<td><a href="/docker/api/containers/{$item.Id}/json" target="_blank">{substr($item.Id, 0, 12)}</a></td>
				<td>{$item.Names.0}</td>
				<td>{$item.Image}</td>
				{**<td>{$item.Command}</td>**}
				<td>{date('Y-m-d H:i:s', $item.Created)}</td>
				<td>{$item.Status}</td>
				<td>
					{$status = strstr($item.Status, " ", true)}
					{if 'Up'==$status}<button class="btn btn-warning" data-click="stop">Stop</button>{/if}
					{if 'Up'!=$status}
						<button class="btn btn-success" data-click="start">Start</button>
						<button class="btn btn-danger" data-click="delete">Delete</button>
					{/if}
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
{/block}

{block name="page_scripts"}
	<script src="/static/docker/containers.js"></script>
{/block}