/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // 作ったことがない料理を選ぶチェックボックスチェンジイベント
    $('#chkNew').change(function() {
        SelectService.changeSelectType(e);
        return false;
    });

    // 作ったことがある料理を選ぶチェックボックスチェンジイベント
    $('#chkOld').change(function() {
        SelectService.changeSelectType(e);
        return false;
    });

    // サラダ選択ボタンクリックイベント
    $("#btnSalad").click(function() {
        SelectService.selectSalad();
        return false;
    });

    // スープ選択ボタンクリックイベント
    $("#btnSoup").click(function() {
        SelectService.selectSoup();
        return false;
    });

    // おかず選択ボタンクリックイベント
    $("#btnSideDish").click(function() {
        SelectService.selectSideDish();
        return false;
    });

    // 前へボタンクリックイベント
    $("#btnBack").click(function() {
        SelectService.back();
        return false;
    });

    // 次へボタンクリックイベント
    $("#btnNext").click(function() {
        SelectService.next();
        return false;
    });
});

/**
 * 料理を選ぶサービス
 */
const SelectService = new class {

    /**
     * メニュー種別　サラダ
     */
    get MENU_TYPE_SALAD() { return 1; }
        /**
         * メニュー種別　スープ
         */
    get MENU_TYPE_SOUP() { return 2 };
    /**
     * メニュー種別　おかず
     */
    get MENU_TYPE_SIDE_DISH() { return 3; }

    /**
     * チェックボックスチェンジイベント
     */
    changeSelectType(e) {

        if ($('#chkNew').prop("checked") === false &&
            $('#chkOld').prop("checked") === false) {

            let $target = $(e.target);
            if ($target.attr('id') === 'chkOld') {
                $('#chkNew').prop("checked", true);
            } else {
                $('#chkOld').prop("checked", true);
            }
        }
    }

    /**
     * サラダ選択
     */
    selectSalad() {
        $("#type").val(this.MENU_TYPE_SALAD);
        this.select();
    }

    /**
     * スープ選択
     */
    selectSoup() {
        $("#type").val(this.MENU_TYPE_SOUP);
        this.select();
    }

    /**
     * おかず選択ボタンクリックイベント
     */
    selectSideDish() {
        $("#type").val(this.MENU_TYPE_SIDE_DISH);
        this.select();
    }

    /**
     * 料理選択
     */
    select() {

        let $form = $("form").get()[0];
        let formData = new FormData($form);
        SelectModalService.initialize();
        LoadingService.show();

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            url: SELECT_URL,
            data: formData
        }).done((data) => {
            if (data) {
                data = JSON.parse(data);

                LoadingService.close();

                if (data['isLogin'] === false) {
                    window.location.href = data['loginURL'];
                } else if (data['menu']['isSuccess'] === true) {

                    let menu = data['menu'];
                    if (menu['id'] === '') {
                        alert("該当する料理がありませんでした。");
                        return;
                    }

                    // 選択した料理を保持
                    let type = Number($("#type").val());
                    if (type === this.MENU_TYPE_SALAD) {
                        $("#salad").text(menu['name']);
                        $('#saladMenuID').val(menu['id']);
                    } else if (type === this.MENU_TYPE_SOUP) {
                        $("#soup").text(menu['name']);
                        $('#soupMenuID').val(menu['id']);
                    } else if (type === this.MENU_TYPE_SIDE_DISH) {
                        $("#sideDish").text(menu['name']);
                        $('#sideDishMenuID').val(menu['id']);
                    }
                    SelectModalService.show(menu['name'], menu['image']);
                } else {
                    alert('料理の選択に失敗しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
                    return;
                }
            }
        }).fail(function(data) {
            LoadingService.close();
            window.location.href = ERROR_URL;
        });
    }

    /**
     * 前へ
     */
    back() {

        window.location.href = BACK_URL;
    }

    /**
     * 次へ（選択した料理を保存）
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
                    alert('選択した料理の登録に失敗しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
                }
            }
        }).fail(function(data) {
            window.location.href = ERROR_URL;
        });
    }
}