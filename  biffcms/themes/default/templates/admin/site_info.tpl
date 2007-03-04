<h1>Site Information</h1>

<b><font color="red">{message}</font><b>

<p>
<form method="post">
<table cellspacing=0 align=center>
	<tr class="oddRow">
		<td class="rowHeading">Site Name:</td>
		<td><input type="text" name="site_name" value="{site_name}" size=70></td>
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
	<tr  class="oddRow">
		<td class="rowHeading">Current Theme:</td>
		<td>
			<select name="current_theme">
				{theme_list}
			</select>
		</td>
	</tr>
	<tr class="evenRow">
		<td colspan=2 align=right><input type="submit" name="saveSiteInfo" value="Save Info"> <input type="submit" value="Cancel" name="Cancel"></td>
	</tr>
</table>
</form>
