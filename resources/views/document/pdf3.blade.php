<!doctype html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <div>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr style="background-color: #fff;">
        <td style="width: 100%; text-align: left; font-size: 18">{{ $company }}　給与明細</td>
      </tr>
    </table>
  </div>

  <div>
    <table border="1" width="100%" cellpadding="5" cellspacing="0">
      <tr>
        <td style="width: 15%; background-color: #fc3; text-align: center;">支給年月</td>
        <td style="width: 30%; background-color: #fff; text-align: left;  ">{{ $pay_ym }}</td>
        <td style="width: 15%; background-color: #fc3; text-align: center;">氏　名</td>
        <td style="width: 40%; background-color: #fff; text-align: left;  ">{{ $name }}</td>
      </tr>
    </table>
  </div>

  <div>
    <table border="1" width="50%" cellpadding="5" cellspacing="0">
      <tr>
        <td style="width: 50%; background-color: #fc3; text-align: center;">差引支給額</td>
        <td style="width: 50%; background-color: #fff; text-align: right;">￥{{ number_format( $total ) }}</td>
      </tr>
    </table>
  </div>

      <div>
      <table border="1" width="100%" cellpadding="3" cellspacing="0">
        <tr style="background-color: #fc3;">
          <td style="width: 50%; text-align: center;" colspan="2">支　給</td>
          <td style="width: 50%; text-align: center;" colspan="2">控　除</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_101 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_101 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_201 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_201 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_102 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_102 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_202 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_202 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_103 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_103 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_203 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_203 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_104 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_104 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_204 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_204 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_105 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_105 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_205 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_205 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_106 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_106 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_206 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_206 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_107 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_107 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_207 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_207 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_108 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_108 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_208 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_208 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_109 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_109 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_209 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_209 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_110 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_110 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_210 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_210 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_111 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_111 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_211 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_211 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_112 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_112 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_212 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_212 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_113 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_113 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_213 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_213 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_114 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_114 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_214 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_214 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_115 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_115 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_215 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_215 ) }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_116 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_116 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_216 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_216 ) }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_117 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_117 ) }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_217 }}</td>
          <td style="width: 25%; text-align: right;">{{ number_format( $data_217 )}}</td>
        </tr>
        <tr>
          <td style="width: 25%; background-color: #fc3; text-align: center;">支給総額</td>
          <td style="width: 25%; background-color: #fff; text-align: right;" >{{number_format( $total_100 )}}</td>
          <td style="width: 25%; background-color: #fc3; text-align: center;">控除総額</td>
          <td style="width: 25%; background-color: #fff; text-align: right;" >{{number_format( $total_200 )}}</td>
        </tr>
      </table>
      </div>

      <div>
      <table border="1" width="100%" cellpadding="3" cellspacing="0">
        <tr style="background-color: #fc3;">
          <td style="width: 100%; background-color: #fc3; text-align: center;" colspan="4">勤　怠</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_301 }}</td>
          <td style="width: 25%; text-align: right;">{{ $data_301 }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_302 }}</td>
          <td style="width: 25%; text-align: right;">{{ $data_302 }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_303 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_303 }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_304 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_304 }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_305 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_305 }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_306 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_306 }}</td>
        </tr>
        <tr style="background-color: #ffc;">
          <td style="width: 25%; text-align: left; ">{{ $title_307 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_307 }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_308 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_308 }}</td>
        </tr>
        <tr style="background-color: #fff;">
          <td style="width: 25%; text-align: left; ">{{ $title_309 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_309 }}</td>
          <td style="width: 25%; text-align: left; ">{{ $title_310 }}</td>
          <td style="width: 25%; text-align: right;">{{  $data_310 }}</td>
        </tr>
      </table>
      </div>

    </body>
</html>
