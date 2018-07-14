/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // 前へボタン
    $('#btnBack').click(function() {
        RecipeService.back();
        return false;
    });

    // 次へボタン
    $('#btnNext').click(function() {
        RecipeService.next();
        return false;
    });
});

/**
 * 作り方画面サービス
 */
const RecipeService = new class {

    /**
     * 前へ
     */
    back() {

        window.location.href = BACK_URL;
    }

    /**
     * 次へ（履歴に登録）
     */
    next() {

        let $form = $("form").get()[0];
        let formData = new FormData($form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            url: SAVE_URL,
            data: formData
        }).done(function(data) {

            if (data) {
                data = JSON.parse(data);

                if (data['isLogin'] === false) {
                    window.location.href = data['loginURL'];
                } else if (data['isSuccess'] === true) {
                    window.location.href = NEXT_URL;
                } else {
                    alert('履歴の登録に失敗しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
                }
            }
        }).fail(function(data) {
            window.location.href = ERROR_URL;
        });
    }
}