/**
 * 共通処理クラス
 */
const Common = new class {

    /** 入力項目のエラー背景色 */
    get ERROR_INPUT_COLOR() { return '#ffcccc'; }

    /** 入力項目の初期背景色 */
    get DEFAULT_INPUT_COLOR() { return '#FFFFFF'; }

    /** 評価なし色 */
    get NO_EVALUATION_COLOR() { return 'rgb(204, 204, 204)'; }

    /** 評価あり色 */
    get HAS_EVALUATION_COLOR() { return 'rgb(255, 204, 51)'; }

    /** システムエラーメッセージ */
    get SYSTEM_ERROR_MESSAGE() { return 'システムエラーが発生しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。'; }

    /**
     * 頭文字を大文字に変換
     * @param {*} target 対象文字
     */
    convertInitialIntoUpperCase(target) {
        return target.substring(0, 1).toUpperCase() + target.substring(1);
    }

    /**
     * 評価設定
     * @param {*} target 対象
     * @param {*} evaluation 評価
     */
    setEvaluation(target, evaluation) {

        for (let i = 1; i <= 3; i++) {
            $(`#${target}${i}`).css('color', this.NO_EVALUATION_COLOR);
        }
        for (let i = 1; i <= evaluation; i++) {
            $(`#${target}${i}`).css('color', this.HAS_EVALUATION_COLOR);
        }
    }
}