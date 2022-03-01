<?php
#注册插件
RegisterPlugin("mz_clickView", "ActivePlugin_mz_clickView");

function ActivePlugin_mz_clickView()
{
  Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'mz_clickView_ViewPost');
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'InstallPlugin_mz_clickView');
  Add_Filter_Plugin('Filter_Plugin_Edit_Response2', 'mz_clickView_EdtJS');
}
function mz_clickView_EdtJS()
{
  echo '<script src="' . mz_clickView_Path("js-admin", "host") . '"></script>';
}


function mz_clickView_ViewPost(&$template)
{
  global $zbp;
  $diy = $zbp->Config("mz_clickView")->pattern;
  if (!empty($diy)) {
    $diy = "|$diy";
  }
  $article = $template->GetTags('article');
  // 正则
  $pattern = "/<p>[^<>]*\[clickView\](?<text>.+?)\[\/clickView\][^<>]*<\/p>{$diy}/uis";
  // 隐藏后的内容模板
  $tplLock = file_get_contents(mz_clickView_Path("u-lock"));
  // preg_replace_callback 竟然不自带自增序号？
  $i = 0;
  // 正则替换
  $article->Content = preg_replace_callback($pattern, function ($match) use ($tplLock, &$i) {
    $i++;
    $text = $match["text"];
    if (empty($text) && isset($match["text2"])) {
      $text = $match["text2"];
    }
    $rltLock = str_replace("-encode-", base64_encode("<p>{$text}</p>"), $tplLock);
    $rltLock = str_replace("-id-", $i, $rltLock);
    return $rltLock;
  }, $article->Content);
  // 样式及 JS 引入
  $zbp->header .= '<link rel="stylesheet" href="' . mz_clickView_Path("css-web", "host") . '?v=20220301103">';
  $zbp->footer .= '<script src="' . mz_clickView_Path("js-base64", "host") . '?v=20220301103"></script>';
  $zbp->footer .= '<script src="' . mz_clickView_Path("js-web", "host") . '?v=20220301103"></script>';
}
function mz_clickView_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/mz_clickView/';
  switch ($file) {
    case 'u-lock':
      return $result . 'usr/lock.html';
      break;
    case 'v-lock':
      return $result . 'var/lock.html';
      break;
    case 'css-web':
      return $result . 'css/style.css';
      break;
    case 'js-base64':
      return $result . 'script/jquery.base64.js';
      break;
    case 'js-web':
      return $result . 'script/web.js';
      break;
    case 'js-admin':
      return $result . 'script/admin.js';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_mz_clickView()
{
  global $zbp;
  if (!$zbp->HasConfig('mz_clickView')) {
    $zbp->Config('mz_clickView')->version = 1;
    $zbp->Config("mz_clickView")->time = 0;
    $zbp->Config('mz_clickView')->tipInfo = "此处内容默认隐藏，请点击右边按钮显示";
    $zbp->Config('mz_clickView')->tipMore = '[<a href="https://feedly.com/i/subscription/feed/-feedurl-" target="_blank" title="订阅到feedly">订阅到feedly</a>] [<a href="https://www.innoreader.com/feed/-feedurl-" target="_blank" title="订阅到inoreader">订阅到inoreader</a>] [<a href="https://feeds.pub/feed/-feedurl-" target="_blank" title="订阅到FeedsPub">订阅到FeedsPub</a>]';
    $zbp->SaveConfig('mz_clickView');
  } else {
    if ($zbp->Config('mz_clickView')->version < 1.1) {
      $zbp->Config('mz_clickView')->version = 1.1;
      $zbp->Config('mz_clickView')->tipInfo = "此处内容默认隐藏，请点击右边按钮显示；";
      $zbp->Config('mz_clickView')->tipMore = $zbp->Config('mz_clickView')->tip;
      $zbp->Config('mz_clickView')->Del("tip");
      $zbp->SaveConfig('mz_clickView');
    }
  }
  $filesList = array("lock");
  foreach ($filesList as $key => $value) {
    $uFile = mz_clickView_Path("u-{$value}");
    $vFile = mz_clickView_Path("v-{$value}");
    if (!is_file($uFile)) {
      @mkdir(dirname($uFile));
      copy($vFile, $uFile);
    }
  }
  mz_clickView_BuildLockHTML();
}
function mz_clickView_BuildLockHTML()
{
  global $zbp;
  $cfgData = $zbp->Config('mz_clickView')->GetData();
  $uFile = mz_clickView_Path("u-lock");
  $vFile = mz_clickView_Path("v-lock");
  $html = file_get_contents($vFile);
  foreach ($cfgData as $key => $value) {
    $html = str_replace("-{$key}-", $value, $html);
  }
  file_put_contents($uFile, str_replace("-feedurl-", urlencode($zbp->feedurl), $html));
}
function UninstallPlugin_mz_clickView()
{
}
