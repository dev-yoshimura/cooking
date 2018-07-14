/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // チェックボックス変更イベント
    $("input[type = 'checkbox']").change(function(e) {
        MaterialService.changeCheckbox(e);
        return false;
    });

    // 前へボタン
    $('#btnBack').click(function() {
        MaterialService.back();
        return false;
    });

    // 次へボタン
    $('#btnNext').click(function() {
        MaterialService.next();
        return false;
    });
});

/**
 * 材料画面サービス
 */
const MaterialService = new class {

    /**
     * チェックボックス変更
     */
    changeCheckbox(e) {

        let $target = $(e.target);
        if ($target.prop('checked') === true) {
            $target.parent().prev().children('img').eq(0).show();
        } else {
            $target.parent().prev().children('img').eq(0).hide();
        }
    }

    /**
     * 前へ
     */
    back() {

        window.location.href = BACK_URL;
    }

    /**
     * 次へ
     */
    next() {

        window.location.href = NEXT_URL;
    }
}