/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // 星クリックイベント
    $("a[name='evaluation']").click(function(e) {
        HistoryService.evaluation(e);
        return false;
    });
});

/**
 * 作った料理一覧画面サービス
 */
const HistoryService = new class {

    /**
     * 評価
     * @param {*} e イベントオブジェクト
     */
    evaluation(e) {

        let $target = $(e.target);
        let $row = $target.parent().parent().children();

        evaluationModalService.show($row.eq(0).children().val(),
            $row.eq(1).children().text(),
            $row.eq(2).children().text(),
            $row.eq(3).children().text());
    }
}