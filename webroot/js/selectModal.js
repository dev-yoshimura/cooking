/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // もう一度選ぶボタンクリックイベント
    $('#btnAgain').click(function() {
        SelectModalService.again();
        return false;
    });
});

/**
 * 料理選択ダイアログサービス
 */
const SelectModalService = new class {

    /**
     * 初期化
     */
    initialize() {

        $("#name").text("");
        $("#image").prop("src", "");
    }

    /**
     * 表示
     * @param {*} name 料理名
     * @param {*} image 料理画像
     */
    show(name, image) {

        $("#name").text(name);
        $("#image").prop("src", "data:image/jpeg;base64," + image);
        let options = {
            backdrop: "static",
            keyboard: false,
            focus: true,
            show: true
        };
        $("#selectModal").modal(options);
    }

    /**
     * もう一度選ぶ
     */
    again() {

        $('#menu').modal('hide');
        SelectService.select();
    }
}