<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><font size="+3">電子交付</font></div>

        <div class="card-body">
          <form method="POST" action="{{ route('agree') }}" aria-label="{{ __('Login') }}">
            @csrf
<font size="+2">
<pre>

当社は給与所得明細書を電子交付します。
下記注意事項を確認の上、同意ボタンを押下して明細を取得してください。

1.電子交付する対象書類
　　・給与明細書

2.交付方法
　　・電子ファイル（PDF形式）にて交付します。
　　　※明細書データは暗号化保存されています。

3.明細書閲覧、ダウンロード方法
　　インターネットの接続されたPC、スマホ等にてダウンロード画面に接続し、
　　任意の明細書を閲覧、またはダウンロード。
　　※保存する場合は、任意のフォルダに保存してください。　　

4.交付開始日　
　　{!! date("Y年 m月 d日") !!}

以上、上記電子交付に事項について同意します。　                      

</pre>
</font>

            <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">同意</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
