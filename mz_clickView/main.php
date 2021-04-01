<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('mz_clickView')) {
  $zbp->ShowError(48);
  die();
}

$act = GetVars('act', 'GET');
$suc = GetVars('suc', 'GET');
if ($act == 'save') {
  CheckIsRefererValid();
  foreach ($_POST as $key => $val) {
    $zbp->Config('mz_clickView')->$key = trim($val);
  }
  $zbp->SaveConfig('mz_clickView');
  $zbp->BuildTemplate();
  $zbp->SetHint('good');
  Redirect('./main.php' . ($suc == null ? '' : '?act=$suc'));
}

$blogtitle = '点击查看';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?><small><a title="刷新" href="main.php" style="font-size: 16px;display: inline-block;margin-left: 5px;">刷新</a></small></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require "about.php"; ?>
  </div>
  <div id="divMain2">
    <form action="<?php echo BuildSafeURL("main.php?act=save"); ?>" method="post">
      <table width="100%" class="tableBorder">
        <tr>
          <th width="10%">项目</th>
          <th>内容</th>
          <th width="45%">说明</th>
        </tr>
        <tr>
          <td>自定义匹配</td>
          <td><?php echo zbpform::text("pattern", FormatString($zbp->Config("mz_clickView")->pattern, "[html-format]"), "90%"); ?></td>
          <td><?php echo FormatString("<p>(?<text2>[^<]*关键词[^<]*)<\/p>", "[html-format]"); ?></td>
        </tr>
        <tr>
          <td>提示信息</td>
          <td><?php echo zbpform::text("tipInfo", FormatString($zbp->Config("mz_clickView")->tipInfo, "[html-format]"), "90%"); ?></td>
          <td></td>
        </tr>
        <tr>
          <td>附加信息</td>
          <td><?php echo zbpform::text("tipMore", FormatString($zbp->Config("mz_clickView")->tipMore, "[html-format]"), "90%"); ?></td>
          <td></td>
        </tr>
        <tr>
          <td>保存</td>
          <td colspan="2"><input type="submit" value="提交" /><small><a title="刷新" href="main.php" style="font-size: 16px;display: inline-block;margin-left: 5px;">刷新</a></small></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
