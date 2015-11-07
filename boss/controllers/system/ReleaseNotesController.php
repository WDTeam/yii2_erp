<?php
namespace boss\controllers\system;

use boss\components\BaseAuthController;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarkdownController
 *
 * @author weibeinan
 */
class ReleaseNotesController extends BaseAuthController{
    //put your code here
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }
}
