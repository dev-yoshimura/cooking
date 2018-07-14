/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // はいボタンクリック
    $('#btnYes').click(function() {
        ConfirmationModalService.yes();
    });
});

/**
 * 確認モーダルグサービス
 */
const ConfirmationModalService = new class {

    /**
     * 表示
     */
    show() {

        // backdrop: 'static' でダイアログの外側をクリックでダイアログが閉じないようにしています
        // keyboard: falseで[ESC]ボタンクリックでダイアログが閉じないようしています。
        $("#confirmationModal").modal({
            backdrop: "static",
            keyboard: false,
            show: true
        });
    }

    /**
     * はいボタンクリック
     */
    yes() {

        LoadingService.show();
        $('form').submit();
    }
}