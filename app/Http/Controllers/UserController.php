<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\User;
use App\Services\CSV;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '>', '5')->get();
        return ['users' => $users];
    }

    public function agree(Request $request)
    {
        $user = $request->user();
        $user->role = 10;
        $user->save();
        return redirect('home');

    }

    public function download()
    {
        $users = User::where('id', '>', '5')->get(['loginid', 'name', 'role', 'entry_date'])->toArray();
        $header = ['loginid', 'name', 'role', 'entry_date'];
        $csv = new CSV;
        return $csv->download($users, $header, 'user.csv');
    }

    public function upload(Request $request)
    {
        // CSV操作準備
        $csv = new CSV;

        // アップロードファイルに対してのバリデート
        $validator = $csv->validate($request);
        if ($validator->fails() === true){
            return ['errors' => $validator->errors()];
        }

        // CSVをパース
        $rows = $csv->parse($request);

        // １行ずつ処理
        foreach ($rows as $line => $value) {

            // 行データに対してのバリデート（必須・内容の確認）
            $v = $this->upload_row_validate($value);

            // ＣＳＶに問題があればバリデートエラーを記録 => 処理は継続
            if ($v->fails()) {
                foreach ($v->errors()->all() as $message) {
                    Log::Debug(__CLASS__.':'.__FUNCTION__.'import data error line :'. $line .' message: '. $message);
                    $ret['errors'][] = ['line' => $line, 'error' => $message];
                }
                continue;
            }

            // ＣＳＶに問題がなければ 更新 or 挿入
            $user = User::where('loginid', $value['loginid'])->first();

            // 存在したら、更新
            if( $user ) { 
                Log::Debug(__CLASS__.':'.__FUNCTION__.'import update line :'. $line .' name: '. $value['name']);
                $user->fill($value)->save();
                $ret['update'][] = ['line' => $line, 'name' => $value['name']];
            }

            // ＤＢ未登録なら新規登録
            else { 
                Log::Debug(__CLASS__.':'.__FUNCTION__.'import insert line :'. $line .' name: '. $value['name']);
                $value['password'] = Hash::make($value['loginid']); // とりあえず初期パスワードは loginID にしとく
                User::create($value);
                $ret['insert'][] = ['line' => $line, 'name' => $value['name']];
            }
        }
        return ['import' => $ret];
    }

    private function upload_row_validate($row) 
    {
        return \Validator::make($row, [
            'loginid' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'role' => [
                'required',
                'numeric',
                Rule::in([5, 10]),   // role値は 5 か 10 であること
            ],
        ]);
    }
}
 
