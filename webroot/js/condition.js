/**
 * HTMLコードを読み込み終了処理
 */
$(function() {

    // 初期化
    ConditionService.initialize();

    // 余った材料追加ボタン
    $('#btnAddSurplusMaterial').click(function() {
        ConditionService.addSurplusMaterial();
        return false;
    });

    // 使用しない材料追加ボタン
    $('#btnAddNotUseMaterial').click(function() {
        ConditionService.addNotUseMaterial();
        return false;
    });

    // コスパクリックイベント
    $('#cospa1').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#cospa2').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#cospa3').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });

    // 作りやすさクリックイベント
    $('#ease1').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#ease2').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#ease3').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });

    // 味クリックイベント
    $('#taste1').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#taste2').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });
    $('#taste3').click(function(e) {
        ConditionService.evaluation(e);
        return false;
    });

    // 保存ボタン
    $('#btnSave').click(function() {
        ConditionService.save();
        return false;
    });

    // 前へボタン
    $('#btnBack').click(function() {
        ConditionService.back();
        return false;
    });

    // 次へボタン
    $('#btnNext').click(function() {
        ConditionService.next();
        return false;
    });
});

/**
 * 料理を選ぶ条件画面サービス
 */
const ConditionService = new class {

    /**
     * 初期化
     */
    initialize() {

        // 余った材料削除ボタン
        $("div[name ='surplusMaterial']").children('div').children('button').on('click', (e) => {
            this.deleteSurplusMaterial(e);
            return false;
        });

        // 使用しない材料削除ボタン
        $("div[name ='notUseMaterial']").children('div').children('button').on('click', (e) => {
            this.deleteNotUseMaterial(e);
            return false;
        });

        // 評価（コスパ）
        Common.setEvaluation('cospa', $('#evaluationCospa').val());

        // 評価（作りやすさ）
        Common.setEvaluation('ease', $('#evaluationEase').val());

        // 評価（味）
        Common.setEvaluation('taste', $('#evaluationTaste').val());
    }

    /**
     * 余った材料追加
     */
    addSurplusMaterial() {

        this.addMaterial('surplusMaterial', this.deleteSurplusMaterial);
    }

    /**
     * 使用しない材料追加
     */
    addNotUseMaterial() {

        this.addMaterial('notUseMaterial', this.deleteNotUseMaterial);
    }

    /**
     * 材料追加
     * @param {*} name 要素名
     * @param {*} deleteEvenet 削除ボタンクリックイベント
     */
    addMaterial(name, deleteEvenet) {

        let count = $(`div[name="${name}"]`).length;
        let input = MATERIAL_INPUT.replace('divName', name);
        input = input.replace('divName[count]', `${name}[${count}]`);
        let $input = $(input);

        // 番号設定
        $input.children('div').children('div').eq(0).children('span').text(count + 1);

        // 削除ボタンクリックイベント設定
        $input.children('div').children('div').eq(2).children('button').on('click', (e) => {
            // this(ConditionService)の値と、個々にあたえた引数をわたして、関数を呼び出す
            deleteEvenet.call(this, e);
            return false;
        });

        // 追加
        $(`#add${Common.convertInitialIntoUpperCase(name)}`).before($input);
    }

    /**
     * 余った材料削除
     * @param {*} e イベントオブジェクト
     */
    deleteSurplusMaterial(e) {

        this.deleteMaterial(e, 'surplusMaterial');
    }

    /**
     * 使用しない材料削除
     * @param {*} e イベントオブジェクト
     */
    deleteNotUseMaterial(e) {

        this.deleteMaterial(e, 'notUseMaterial');
    }

    /**
     * 材料削除
     * @param {*} e イベントオブジェクト
     * @param {*} name 要素名
     */
    deleteMaterial(e, name) {

        // 削除
        $(e.target).parent().parent().remove();

        // 再設定
        let $elements = $(`div[name="${name}"]`);
        let count = $elements.length;
        for (let i = 0; i < count; i++) {
            $elements.eq(i).children('div').eq(1).children('input').attr('name', `${name}[${(i + 1)}]`);
        }

        // 番号再設定
        for (let i = 0; i < count; i++) {
            $elements.eq(i).children('div').eq(0).children('span').text(i + 1);
        }
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

        ConfirmationModalService.show();
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