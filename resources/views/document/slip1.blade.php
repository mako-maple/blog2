<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style type="text/css">
  .title15 { width: 15%; text-align: center; }
  .title30 { width: 30%; text-align: left; }
  .titletd { width: 50%; text-align: center; }
  .title25 { width: 25%; text-align: left; }
  .title25c { width: 25%; text-align: center; }
  .title100 { width: 100%; text-align: center; }
  .data25  { width: 25%; text-align: right; }
  .data100 { width: 100%; text-align: left; }
  .color0 { background-color: #fff; }
  .color1 { background-color: #fc3; }
  .color2 { background-color: #ffc; }
</style>
</head>
<body style="background-color: #fff;">
  <div><table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr >
        <td style="width: 100%; text-align: left; font-size: 18">{{ $company }}　給与明細</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="3" cellspacing="0">
      <tr>
        <td class="title15 color1">支給年月</td>
        <td class="title30 color0">{{ $pay_ym }}</td>
        <td class="title15 color1">氏　名</td>
        <td class="title30 color0">{{ $name }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="50%" cellpadding="3" cellspacing="0">
      <tr>
        <td class="color1" style="width: 50%; text-align: center;">&nbsp;&nbsp;{{ $title00 }}</td>
        <td class="color0" style="width: 50%; text-align: right;">￥{{ number_format( $data00 ) }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="2" cellspacing="0">
      <tr class="color1">
        <td class="titletd" colspan="2">支　給</td>
        <td class="titletd" colspan="2">控　除</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title01 }}</td>
        <td class="data25">{{ number_format( $data01 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title21 }}</td>
        <td class="data25">{{ number_format( $data21 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title02 }}</td>
        <td class="data25">{{ number_format( $data02 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title22 }}</td>
        <td class="data25">{{ number_format( $data22 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title03 }}</td>
        <td class="data25">{{ number_format( $data03 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title23 }}</td>
        <td class="data25">{{ number_format( $data23 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title04 }}</td>
        <td class="data25">{{ number_format( $data04 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title24 }}</td>
        <td class="data25">{{ number_format( $data24 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title05 }}</td>
        <td class="data25">{{ number_format( $data05 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title25 }}</td>
        <td class="data25">{{ number_format( $data25 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title06 }}</td>
        <td class="data25">{{ number_format( $data06 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title26 }}</td>
        <td class="data25">{{ number_format( $data26 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title07 }}</td>
        <td class="data25">{{ number_format( $data07 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title27 }}</td>
        <td class="data25">{{ number_format( $data27 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title08 }}</td>
        <td class="data25">{{ number_format( $data08 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title28 }}</td>
        <td class="data25">{{ number_format( $data28 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title09 }}</td>
        <td class="data25">{{ number_format( $data09 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title29 }}</td>
        <td class="data25">{{ number_format( $data29 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title10 }}</td>
        <td class="data25">{{ number_format( $data10 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title30 }}</td>
        <td class="data25">{{ number_format( $data30 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title11 }}</td>
        <td class="data25">{{ number_format( $data11 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title31 }}</td>
        <td class="data25">{{ number_format( $data31 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title12 }}</td>
        <td class="data25">{{ number_format( $data12 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title32 }}</td>
        <td class="data25">{{ number_format( $data32 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title13 }}</td>
        <td class="data25">{{ number_format( $data13 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title33 }}</td>
        <td class="data25">{{ number_format( $data33 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title14 }}</td>
        <td class="data25">{{ number_format( $data14 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title34 }}</td>
        <td class="data25">{{ number_format( $data34 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title15 }}</td>
        <td class="data25">{{ number_format( $data15 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title35 }}</td>
        <td class="data25">{{ number_format( $data35 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title16 }}</td>
        <td class="data25">{{ number_format( $data16 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title36 }}</td>
        <td class="data25">{{ number_format( $data36 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title17 }}</td>
        <td class="data25">{{ number_format( $data17 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title37 }}</td>
        <td class="data25">{{ number_format( $data37 ) }}</td>
      </tr>
      <tr>
        <td class="title25c color1">&nbsp;&nbsp;{{ $title20 }}</td>
        <td class="data25   color0">{{number_format( $data20 )}}</td>
        <td class="title25c color1">&nbsp;&nbsp;{{ $title40 }}</td>
        <td class="data25   color0">{{number_format( $data40 )}}</td>
      </tr>
    </table>
  </div>
  <div><table border="1" width="100%" cellpadding="2" cellspacing="0">
      <tr class="color1">
        <td class="title100" colspan="4">勤　怠</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title41 }}</td>
        <td class="data25">{{ number_format( $data41, 2 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title51 }}</td>
        <td class="data25">{{ number_format( $data51, 2 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title42 }}</td>
        <td class="data25">{{ number_format( $data42, 2 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title52 }}</td>
        <td class="data25">{{ number_format( $data52, 2 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title43 }}</td>
        <td class="data25">{{ number_format( $data43, 2 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title53 }}</td>
        <td class="data25">{{ number_format( $data53, 2 ) }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title44 }}</td>
        <td class="data25">{{ number_format( $data44, 2 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title54 }}</td>
        <td class="data25">{{ number_format( $data54, 2 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title45 }}</td>
        <td class="data25">{{ number_format( $data45, 2 ) }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title55 }}</td>
        <td class="data25">{{ number_format( $data55, 2 ) }}</td>
      </tr>
      <tr class="color0">
        <td class="data100" colspan="4">{{ $data60 }}</td>
      </tr>
    </table>
  </div>
</body>
</html>
