/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // コスパクリックイベント
    $('#cospa1').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#cospa2').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#cospa3').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });

    // 作りやすさクリックイベント
    $('#ease1').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#ease2').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#ease3').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });

    // 味クリックイベント
    $('#taste1').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#taste2').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });
    $('#taste3').click(function(e) {
        evaluationModalService.evaluation(e);
        return false;
    });

    // OKボタン押下
    $('#btnOK').click(function() {
        evaluationModalService.save();
        return false;
    });
});

/**
 * 評価設定モーダルサービス
 */
const evaluationModalService = new class {

    /**
     * 表示
     * @param {*} id 履歴ID
     * @param {*} evaluationCospa コスパ評価
     * @param {*} evaluationEase 作りやすさ評価
     * @param {*} evaluationTaste 味評価
     */
    show(id, evaluationCospa, evaluationEase, evaluationTaste) {

        $('#id').val(id);
        $('#evaluationCospa').val(evaluationCospa);
        $('#evaluationEase').val(evaluationEase);
        $('#evaluationTaste').val(evaluationTaste);

        Common.setEvaluation('cospa', evaluationCospa);
        Common.setEvaluation('ease', evaluationEase);
        Common.setEvaluation('taste', evaluationTaste);

        let options = {
            backdrop: "static",
            keyboard: false,
            focus: true,
            show: true
        };
        $("#evaluationModal").modal(options);
    }

    /**
     * 評価
     * @param {*} e イベントオブジェクト
     */
    evaluation(e) {

        let $target = $(e.target);
        let targetID = $target.attr('id');
        targetID = targetID.substring(0, targetID.length - 1);
        let $evaluation = $(`#evaluation${Common.convertInitialIntoUpperCase(targetID)}`);
        if ($target.css('color') == Common.NO_EVALUATION_COLOR) {
            $evaluation.val(Number($evaluation.val()) + 1);
            $target.css('color', Common.HAS_EVALUATION_COLOR);
        } else {
            $evaluation.val(Number($evaluation.val()) - 1);
            $target.css('color', Common.NO_EVALUATION_COLOR);
        }
    }

    /**
     * 保存
     */
    save() {

        LoadingService.show();
        $('form').submit();
    }
}