<div class="wrap">
	<div class="icon32 icon32-tsp" id="icon-options-general"></div>
	<h2>{$title}</h2>
	{if $pro_total > 0}
		<h3 style="font-size:1.5em;">Professional Plugins</h3>
		{if $pro_active_count > 0}
			<div>
				<h4 class="icon-ok" style="font-size:1.25em; color:#28CA00;">&nbsp;Activated Plugins</h4>
				{foreach $pro_active_plugins as $pro_active}
				<dl style="padding-bottom:10px;">
					<dt><strong>{$pro_active.title}</strong> - <em>{$pro_active.desc}</em></dt> 
					<dd>
						<a href="{$pro_active.more_url}" target="_blank">Read More</a>
						<a href="{$pro_active.settings}">Settings</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		{if $pro_installed_count > 0}
			<div>
				<h4 class="icon-off" style="font-size:1.25em; color:#CA0000;">&nbsp;Installed Plugins</h4>
				{foreach $pro_installed_plugins as $pro_installed}
				<dl style="padding-bottom:10px;">
					<dt><strong>{$pro_installed.title}</strong> - <em>{$pro_installed.desc}</em></dt>
					<dd>
						<a href="{$pro_installed.more_url}" target="_blank">Read More</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		{if  $pro_recommend_count > 0 }
			<div>
				<h4 class="icon-thumbs-up-alt" style="font-size:1.25em; color:#0020CA;">&nbsp;Recommended Plugins</h4>
				{foreach $pro_recommend_plugins as $pro_recommend }
				<dl style="padding-bottom:10px;">
					<dt><strong>{$pro_recommend.title}</strong> - <em>{$pro_recommend.desc}</em></dt> 
					<dd>
						<a href="{$pro_recommend.more_url}" target="_blank">Read More</a>
						<a href="{$pro_recommend.store_url}" target="_blank">Purchase</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		<br />
	{/if}
	{if $free_total > 0}
		<h3 style="font-size:1.5em;">Free Plugins</h3>
		{if $free_active_count > 0}
			<div>
				<h4 class="icon-ok" style="font-size:1.25em; color:#28CA00;">&nbsp;Activated Plugins</h4>
				{foreach $free_active_plugins as $free_active}
				<dl style="padding-bottom:10px;">
					<dt><strong>{$free_active.title}</strong> - <em>{$free_active.desc}</em></dt> 
					<dd>
						<a href="{$free_active.more_url}" target="_blank">Read More</a>
						<a href="{$free_active.settings}">Settings</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		{if $free_installed_count > 0}
			<div>
				<h4 class="icon-off" style="font-size:1.25em; color:#CA0000;">&nbsp;Installed Plugins</h4>
				{foreach $free_installed_plugins as $free_installed}
				<dl style="padding-bottom:10px;">
					<dt><strong>{$free_installed.title}</strong> - <em>{$free_installed.desc}</em></dt>
					<dd>
						<a href="{$free_installed.more_url}" target="_blank">Read More</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		{if  $free_recommend_count > 0 }
			<div>
				<h4 class="icon-thumbs-up-alt" style="font-size:1.25em; color:#0020CA;">&nbsp;Recommended Plugins</h4>
				{foreach $free_recommend_plugins as $free_recommend }
				<dl style="padding-bottom:10px;">
					<dt><strong>{$free_recommend.title}</strong> - <em>{$free_recommend.desc}</em></dt> 
					<dd>
						<a href="{$free_recommend.more_url}" target="_blank">Read More</a>
						<a href="{$free_recommend.settings}">Download</a>
					</dd>
				</dl>
				{/foreach}
			</div>
		{/if}
		<br />
	{/if}
	<span style="color: rgb(136, 136, 136); font-size: 10px;">If you have any questions, please contact us via <a target="_blank" href="{$contact_url}">{$contact_url}</a></span>
</div>
