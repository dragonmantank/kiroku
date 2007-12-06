<h1>Modules</h1>

<h2>Configure Link_to_page Module</h2>

<font color="red"><b>{message}</b></font>

<form method="post">
<table>
	<tr>
		<td align=center><b>Variable</b></td>
		<td align=center><b>Option</b></td>
	</tr>
	<tr>
		<td>Show Redirect Message?</td>
		<td><select name="show_redirect_message">{redirectOptions}</select></td>
	</tr>
	<tr>
		<td>Redirect Message:</td>
		<td><input type="text" name="redirect_message" value="{redirect_message}"></td>
	</tr>
	<tr>
		<td>Redirect Timeout:</td>
		<td><input type="text" name="redirect_timeout" value="{redirect_timeout}"></td>
	</tr>
	<tr>
		<td colspan=2 align=right><input type="submit" name="saveConfig" value="Save Config"></td>
	</tr>
</table>
</form>
