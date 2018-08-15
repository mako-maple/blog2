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
        <td class="color1" style="width: 50%; text-align: center;">&nbsp;&nbsp;{{ $title[0] }}</td>
        <td class="color0" style="width: 50%; text-align: right;">￥{{ $data[0] }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="2" cellspacing="0">
      <tr class="color1">
        <td class="titletd" colspan="2">支　給</td>
        <td class="titletd" colspan="2">控　除</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[1] }}</td>
        <td class="data25">{{ $data[1] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[21] }}</td>
        <td class="data25">{{ $data[21] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[2] }}</td>
        <td class="data25">{{ $data[2] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[22] }}</td>
        <td class="data25">{{ $data[22] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[3] }}</td>
        <td class="data25">{{ $data[3] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[23] }}</td>
        <td class="data25">{{ $data[23] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[4] }}</td>
        <td class="data25">{{ $data[4] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[24] }}</td>
        <td class="data25">{{ $data[24] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[5] }}</td>
        <td class="data25">{{ $data[5] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[25] }}</td>
        <td class="data25">{{ $data[25] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[6] }}</td>
        <td class="data25">{{ $data[6] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[26] }}</td>
        <td class="data25">{{ $data[26] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[7] }}</td>
        <td class="data25">{{ $data[7] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[27] }}</td>
        <td class="data25">{{ $data[27] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[8] }}</td>
        <td class="data25">{{ $data[8] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[28] }}</td>
        <td class="data25">{{ $data[28] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[9] }}</td>
        <td class="data25">{{ $data[9] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[29] }}</td>
        <td class="data25">{{ $data[29] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[10] }}</td>
        <td class="data25">{{ $data[10] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[30] }}</td>
        <td class="data25">{{ $data[30] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[11] }}</td>
        <td class="data25">{{ $data[11] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[31] }}</td>
        <td class="data25">{{ $data[31] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[12] }}</td>
        <td class="data25">{{ $data[12] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[32] }}</td>
        <td class="data25">{{ $data[32] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[13] }}</td>
        <td class="data25">{{ $data[13] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[33] }}</td>
        <td class="data25">{{ $data[33] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[14] }}</td>
        <td class="data25">{{ $data[14] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[34] }}</td>
        <td class="data25">{{ $data[34] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[15] }}</td>
        <td class="data25">{{ $data[15] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[35] }}</td>
        <td class="data25">{{ $data[35] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[16] }}</td>
        <td class="data25">{{ $data[16] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[36] }}</td>
        <td class="data25">{{ $data[36] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[17] }}</td>
        <td class="data25">{{ $data[17] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[37] }}</td>
        <td class="data25">{{ $data[37] }}</td>
      </tr>
      <tr>
        <td class="title25c color1">&nbsp;&nbsp;{{ $title[20] }}</td>
        <td class="data25   color0">{{$data[20] }}</td>
        <td class="title25c color1">&nbsp;&nbsp;{{ $title[40] }}</td>
        <td class="data25   color0">{{$data[40] }}</td>
      </tr>
    </table>
  </div>
  <div><table border="1" width="100%" cellpadding="2" cellspacing="0">
      <tr class="color1">
        <td class="title100" colspan="4">勤　怠</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[41] }}</td>
        <td class="data25">{{ $data[41] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[51] }}</td>
        <td class="data25">{{ $data[51] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[42] }}</td>
        <td class="data25">{{ $data[42] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[52] }}</td>
        <td class="data25">{{ $data[52] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[43] }}</td>
        <td class="data25">{{ $data[43] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[53] }}</td>
        <td class="data25">{{ $data[53] }}</td>
      </tr>
      <tr class="color2">
        <td class="title25">&nbsp;&nbsp;{{ $title[44] }}</td>
        <td class="data25">{{ $data[44] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[54] }}</td>
        <td class="data25">{{ $data[54] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[45] }}</td>
        <td class="data25">{{ $data[45] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[55] }}</td>
        <td class="data25">{{ $data[55] }}</td>
      </tr>
      <tr class="color0">
        <td class="data100" colspan="4">{!! nl2br(e($data[60])) !!}</td>
      </tr>
    </table>
  </div>
</body>
</html>
