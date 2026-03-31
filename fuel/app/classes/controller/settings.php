<?php
/**
 * 設定画面コントローラ
 */
class Controller_Settings extends Controller_Base
{
    public function action_index()
    {
        return Response::forge(View::forge('settings/index'));
    }
}
