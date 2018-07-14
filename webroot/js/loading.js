/**
 * ローディングサービスクラス
 */
const LoadingService = new class {

    /**
     * 表示
     */
    show() {
        let h = $(window).height();
        $('#loader-bg ,#loader').height(h).css('display', 'block');
    }

    /**
     * 閉じる
     */
    close() {
        $("#loader-bg ,#loader").css("display", "none");
    }
}