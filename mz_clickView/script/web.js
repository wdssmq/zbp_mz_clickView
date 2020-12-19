$(function () {
  $(".mcv-view").click(function () {
    let targetID = $(this).data("target");
    let $target = $(`.mcv-encode.target-${targetID}`);
    let $curEl = $(`.mcv-read.current-${targetID}`);
    $target.removeClass("hidden");
    $curEl.remove();
    let decode = $.base64.atob($target.html().trim(),true);
    $target.html(decode);
    return false;
  });
});
