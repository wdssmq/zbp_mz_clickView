$(function () {
  if ($(".edt-more").length == 0) {
    $("#msg").after(
      "<a title=\"插入摘要分隔符\" href='javascript:void(0)' onclick='fnEdtMore();' class=\"edt-more\"> [摘要分隔]</a>"
    );
  }
  $("#msg").after(
    "<a href='javascript:void(0)' onclick='fnEdtClickView();'> [点击可见区域]</a>"
  );
});
function fnEdtClickView() {
  editor_api.editor.content.insert("<p>[clickView]要隐藏的内容放在这里[/clickView]</p>");
}
function fnEdtMore() {
  let strMore = "";
  if (typeof window.EDITORMD == "object") {
    strMore = "xn------------xf";
  } else {
    strMore = '<hr class="more" />';
  }
  editor_api.editor.content.insert(strMore);
  let oBody = editor_api.editor.content.get();
  oBody = oBody.replace(
    /[\r\n]*xn------------xf[\r\n]*/,
    "\n\n------------\n\n"
  );
  editor_api.editor.content.put(oBody);
  editor_api.editor.intro.put("");
}
