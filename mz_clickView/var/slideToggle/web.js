$(function () {
  $(".mcv-view").click(function () {
    // 选择器
    let targetID = $(this).data("target");
    let $target = $(`.mcv-encode.target-${targetID}`);
    let decode = $.base64.atob($target.html().trim(),true);
    //
    let $curEl = $(`.mcv-read.current-${targetID}`);
    $curEl.find(".mcv-details").html(decode).slideToggle();
    return false;
  });
});
