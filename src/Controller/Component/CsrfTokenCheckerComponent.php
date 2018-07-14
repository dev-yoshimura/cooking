<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\ServerRequest;
use Cake\Utility\Security;
use Cake\Log\Log;

/**
 * CSRFトークンチェック
 */
class CsrfTokenCheckerComponent extends Component
{
    /**
     * CSRFトークンチェック
     * @param request
     * @param config
     * @return OK／NG
     */
    public function Check(ServerRequest $request, $config)
    {
        $cookie = $request->getCookie($config['cookieName']);
        $post = $request->getData($config['field']);
        $header = $request->getHeaderLine('X-CSRF-Token');

        if (!$cookie) {
            return false;
        }

        if (!Security::constantEquals($post, $cookie) && !Security::constantEquals($header, $cookie)) {
            return false;
        }
        return true;
    }
}
