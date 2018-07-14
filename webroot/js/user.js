/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    //更新ボタンクリックイベント
    $("#btnSave").click(function() {
        UserService.save();
        return false;
    });
});

/**
 * ユーザ情報画面サービス
 */
const UserService = new class {

    /**
     * 保存
     */
    save() {

        // 更新ボタン無効
        $("#btnSave").prop("disabled", true);

        let $form = $("form").get()[0];
        let formData = new FormData($form);

        // 入力値チェック
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            url: VALIDATE_URL,
            data: formData
        }).done((data) => {
            // アロー関数は関数が定義されたスコープ内のthisを参照
            if (data) {
                data = JSON.parse(data);

                // エラーメッセージ削除
                $(".error-message").remove();

                // 背景色をもとに戻す
                $("input").css("background-color", Common.DEFAULT_INPUT_COLOR);
                $("select").css("background-color", Common.DEFAULT_INPUT_COLOR);

                // 更新ボタン有効
                $("#btnSave").prop("disabled", false);

                if (data['isLogin'] === false) {
                    window.location.href = data['loginURL'];
                } else if (data['isSuccess'] === true) {

                    let errors = data['errors'];
                    if (errors.length === 0) {
                        // モーダルダイアログ表示
                        ConfirmationModalService.show();
                        return;
                    }
                    // エラーメッセージ設定
                    this.setErrorMessage(errors);
                } else {
                    alert('ユーザ情報の更新に失敗しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
                }
            }
        }).fail(function(data) {
            window.location.href = ERROR_URL;
        });
    }

    /**
     * エラーメッセージ設定
     * @param {*} data エラー内容
     */
    setErrorMessage(data) {

        let element = '<div class="error-message">%s</div>';
        for (let column in data) {
            for (let key in data[column]) {
                let message = data[column][key];
                $('input[name = "' + column + '"]').css(
                    "background-color", Common.ERROR_INPUT_COLOR
                );
                $('input[name = "' + column + '"]').after(
                    element.replace("%s", message)
                );
            }
        }
    }
}