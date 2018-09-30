<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\UQ;

class UQmanage
{

    public function __construct()
    {
    }

    /**
     * 有給日数計算
     * @param User $user
     * @return float 有給時間
     */
    public function getUQTime($user)
    {
        // 入社年月取得　(入社"日"は無視し、常に１日として処理）
        $ent = Carbon::parse($user->entry_date)->startOfMonth();
    
        // 経過月数を取得
        $m = $ent->diffInMonths(Carbon::now());

        // 経過月数によって付与日数（時間）を算出
        $h = 7.5; // １日は 7.5時間
        if ($m >= ((6 * 12) + 6)) { return $h * 20; }      // ６年半以上は ２０日
        if ($m >= ((5 * 12) + 6)) { return $h * 18; }      // ５年半以上は １８日
        if ($m >= ((4 * 12) + 6)) { return $h * 16; }      // ４年半以上は １６日
        if ($m >= ((3 * 12) + 6)) { return $h * 14; }      // ３年半以上は １４日
        if ($m >= ((2 * 12) + 6)) { return $h * 12; }      // ２年半以上は １２日
        if ($m >= ((1 * 12) + 6)) { return $h * 11; }      // １年半以上は １１日
        if ($m >= ((0 * 12) + 6)) { return $h * 10; }      // 半年以上は   １０日
        return 0.0;                                        // 半年経ってなければ 有給なし
    }

    /**
     * 基準月取得
     * @param User $user
     * @return Carbon
     */
    public function getBaseMonth($user)
    {
        // 基準月取得　
        // -- 入社年月が 2010/04 なら基準日は 2010/10 ～有給付与
        // -- ---- 処理日が 2018/08 なら有給は 2017/10 と 2016/10 の２レコードが有効
        // -- ---- 処理日が 2018/10 なら有給は 2018/10 と 2017/10 の２レコードが有効
        $ent = Carbon::parse($user->entry_date);
        return $ent->addMonth(6)->format('Ym');
    }

    /**
     * 有効な有給年月を取得
     * @param User $user
     */
    public function getUQMonth($user)
    {
        // 基準月取得　
        $m = $this->getBaseMonth($user);

        // 有効な有給範囲を取得
        $today = Carbon::now();
Log::Debug('USER:' . $user->tostring());
Log::Debug('Now :' . $today);
        if ($m < $today->format('Ym') ) {
            $ret[0] = $today->subYear(1)->format('Ym');
            $ret[1] = $today->subYear(1)->format('Ym');
        }
        else {
            $ret[0] = $today->format('Ym');
            $ret[1] = $today->subYear(1)->format('Ym');
        }
  
        return $ret;
    }

    /**
     * 有効期限が切れている有給を無効化（そんなデータがあれば）
     * @param User $user
     */
    public function delete($user)
    {
        // 有効な有給範囲を取得
        $uqym = $this->getUQMonth($user);

        // 有効範囲外の有給データは消滅
        $uqs = $user->UQs;
        foreach ($uqs as $uq) {
            if ($uq->add_YM != $uqym[0] && $uq->add_YM != $uqym[1]) {
                $uq->delete();
            }
        }
    }

    /**
     * 新しい有給レコードを追加（必要であれば）
     * @param User $user
     */
    public function create($user)
    {
        // 有効な有給範囲を取得
        $uqym = $this->getUQMonth($user);

        // 有効範囲の有給があるか確認
        $uqs = $user->UQs;
        for($i=0; $i<count($uqym); $i++) {
            foreach ($uqs as $uq) {
                if ($uq->add_YM == $uqym[$i] ) {
                    $uqym[$i] = '';
                    break;
                }
            }
        }

        // 有効範囲の有給がなければ作成
        for($i=0; $i<count($uqym); $i++) {
            if ($uqym[$i] != '') {
                $q = $this->getUQTime($user);
                if ($q > 0.0) {
                    UQ::create( array(
                        'add_YM' => $uqym[$i],
                        'expire_YM' => ((int)$uqym[$i] + 200),  // yyyYmm : 2018/01 + 200 = 2020/01
                        'user_id' => $user->id,
                        'use' => 0.0,
                        'initial' => $q,
                    ));
                }
            }
        }
    }

    /**
     * 有給レコードを確認して必要であれば削除や追加をしとく
     * @param User $user
     */
    public function create($user)
    {
        $this->delete($user);
        $this->create($user);
    }
}
