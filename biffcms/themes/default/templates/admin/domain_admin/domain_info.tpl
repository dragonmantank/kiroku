<h2>{do} Domain</h2>

<p>
<font color="red">{message}</font>

<p>
<form method="post">
<table align=center>
	<tr>
		<td class="columnHeading" colspan=2>Current Domain Information</td>
	</tr>
	<tr class="oddRow">
		<td class="rowHeading">Site Name:</td>
		<td><input type="text" name="site_name" value="{site_name}" size=70></td>
	</tr>
	<tr class="evenRow">
		<td class="rowHeading">Domain:</td>
		<td><input type="text" name="url" value="{url}" size=70></td>
	</tr>
	<tr class="oddRow">
		<td class="rowHeading">Server Path:</td>
		<td><input type="text" name="server_path" value="{server_path}" size=70></td>
	</tr>
	<tr class="evenRow">
		<td class="rowHeading">Site Tagline:</td>
		<td><input type="text" name="site_tagline" value="{site_tagline}" size=70></td>
	</tr>
	<tr class="oddRow">
		<td class="rowHeading">Admin Name:</td>
		<td><input type="text" name="admin_name" value="{admin_name}" size=70></td>
	</tr>
	<tr class="evenRow">
		<td class="rowHeading">Admin E-mail:</td>
		<td><input type="text" name="admin_email" value="{admin_email}" size=70></td>
	</tr>
	<tr class="oddRow">
		<td class="rowHeading">Current Theme:</td>
		<td>
			<select name="current_theme">
				{theme_list}
			</select>
		</td>
	</tr>
	<tr class="evenRow">
		<td class="rowHeading">Default Domain:</td>
		<td>
			<select name="default">
				{defaultSelect}
			</select>
		</td>
	</tr>
	<tr class="oddRow">
		<td class="rowHeading">Active:</td>
		<td>
			<select name="active">
				{activeSelect}
			</select>
		</td>
	</tr>
	<tr class="evenRow">
		<td colspan=2 align=right><input type="submit" name="saveSiteInfo" value="Save Info"> <input type="submit" name="cancel" value="Cancel"></td>
	</tr>
</table>
</form>
