<h2>{function} Page</h2>

<form method="post">
<table>
	<tr>
		<td>Page Name:</td>
		<td><input type="text" name="name" value="{name}"></td>
	</tr>
	<tr>
		<td>Page Title:</td>
		<td><input type="text" name="title" value="{title}"></td>
	</tr>
	<tr>
		<td>Link Name:</td>
		<td><input type="text" name="link_name" value="{link_name}"></td>
	</tr>
	<tr>
		<td valign=top>Page Description:</td>
		<td><textarea name="description">{description}</textarea></td>
	</tr>
	<tr>
		<td>Parent Page:</td>
		<td>
			<select name="parent">
				{parentSelectOptions}
			</select>
		</td>
	</tr>
	<tr>
		<td>Page Module:</td>
		<td>
			<select name="module">				
				{moduleSelectOptions}
			</select>
		</td>
	</tr>
	<tr>
		<td>Page Status:</td>
		<td>
			<select name="active">
				{activeSelectOptions}
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="Save Page" name="savePage"> <input type="submit" name="cancel" value="Cancel"></td>
	</tr>
</table>
</form>
