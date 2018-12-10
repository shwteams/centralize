<script language="javascript">
function cacher()
{
	alert("ok");
}
function montrer(elements)
{
	btn_select = document.getElementById('choice_detail');
	les_elements = new Array('souscription','rate','bonus');
	for(i=0; i<=les_elements.length; i++)
	{
		document.getElementById(les_elements[i]).style.display="none";
	}
	document.getElementById(elements.choice_detail.value).style.display="block";
}
</script>