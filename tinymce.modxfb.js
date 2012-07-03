function getQuery()
{
	if(location.search.length > 1)
	{
		var get = new Object();
		var ret = location.search.substr(1).split("&");
		for(var i = 0; i < ret.length; i++)
		{
			var r = ret[i].split("=");
			get[r[0]] = r[1];
		}
		return get;
	}
	else
	{
		return false;
	}
}

var queryParam = getQuery();
var CKEditorFuncNum = queryParam['CKEditorFuncNum'];

function SetUrl( fileUrl )
{
	window.top.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, fileUrl);
	window.top.close();
}
