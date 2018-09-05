<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style type="text/css">
  .title25 { width: 25%; text-align: left; }
  .title25r { width: 25%; text-align: right; }
  .title25c { width: 25%; text-align: center; }
  .title50 { width: 50%; text-align: left; }
  .title50c { width: 50%; text-align: center; }
  .title100r { width: 100%; text-align: right; }
  .title100c { width: 100%; text-align: center; }

  .data25  { width: 25%; text-align: right; }
  .data50  { width: 50%; text-align: right; }
  .data75c { width: 75%; text-align: center; }
  .data100 { width: 100%; text-align: left; }

  .color0 { background-color: #fff; }
  .color1 { background-color: #fc3; }

  .fonts { font-size: 8; }
  .fontm { font-size: 10; }
  .fontmb { font-size: 14; font-weight: bold; }
</style>
</head>
<body style="background-color: #fff;">
  <div
   ><table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr><td class="title100r fonts">{{ $ymd }}</td></tr>
      <tr><td class="title100c fontmb">給与支給明細書</td></tr>
      <tr><td class="title100r fonts">{{ $company }}</td></tr>
    </table>
  </div>

  <div
   ><table border="1" width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td class="title25 color1">&nbsp;&nbsp;支給年月</td>
        <td class="data75c color0">{{ $pay_ym }}</td>
      </tr>
      <tr>
        <td class="title25 color1">&nbsp;&nbsp;氏名</td>
        <td class="data75c color0">{{ $name }}</td>
      </tr>
    </table>
  </div><div
   ><table border="1" width="50%" cellpadding="2" cellspacing="0">
      <tr>
        <td class="title50 color1">&nbsp;&nbsp;{{ $title[0] }}</td>
        <td class="data50  color0">{{ $data[0] }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="2" cellspacing="0" class="fonts">
      <tr class="color1 fontm">
        <td class="title50c" colspan="2">支　給</td>
        <td class="title50c" colspan="2">控　除</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[1] }}</td>
        <td class="data25">{{ $data[1] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[21] }}</td>
        <td class="data25">{{ $data[21] }}</td>
      </tr>
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
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
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[18] }}</td>
        <td class="data25">{{ $data[18] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[38] }}</td>
        <td class="data25">{{ $data[38] }}</td>
      </tr>
      <tr class="color0 fontm">
        <td class="title25r">&nbsp;&nbsp;{{ $title[19] }}</td>
        <td class="data25">{{ $data[19] }}</td>
        <td class="title25r">&nbsp;&nbsp;{{ $title[39] }}</td>
        <td class="data25">{{ $data[39] }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="2" cellspacing="0" class="fontm">
      <tr>
        <td class="title25 color1">&nbsp;&nbsp;{{ $title[40] }}</td>
        <td class="data25  color0">{{ $data[40] }}</td>
        <td class="title25 color1">&nbsp;&nbsp;{{ $title[41] }}</td>
        <td class="data25  color0">{{ $data[41] }}</td>
      </tr>
    </table>
  </div>

  <div><table border="1" width="100%" cellpadding="2" cellspacing="0" class="fonts">
      <tr class="color1 fontm">
        <td class="title25c">勤怠</td>
        <td class="title25c">時間</td>
        <td class="title25c">勤怠</td>
        <td class="title25c">時間</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[42] }}</td>
        <td class="data25">{{ $data[42] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[49] }}</td>
        <td class="data25">{{ $data[49] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[43] }}</td>
        <td class="data25">{{ $data[43] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[51] }}</td>
        <td class="data25">{{ $data[51] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[44] }}</td>
        <td class="data25">{{ $data[44] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[52] }}</td>
        <td class="data25">{{ $data[52] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[45] }}</td>
        <td class="data25">{{ $data[45] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[53] }}</td>
        <td class="data25">{{ $data[53] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[46] }}</td>
        <td class="data25">{{ $data[46] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[54] }}</td>
        <td class="data25">{{ $data[54] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[47] }}</td>
        <td class="data25">{{ $data[47] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[55] }}</td>
        <td class="data25">{{ $data[55] }}</td>
      </tr>
      <tr class="color0">
        <td class="title25">&nbsp;&nbsp;{{ $title[48] }}</td>
        <td class="data25">{{ $data[48] }}</td>
        <td class="title25">&nbsp;&nbsp;{{ $title[56] }}</td>
        <td class="data25">{{ $data[56] }}</td>
      </tr>
    </table>
  </div>

  <div
   ><table border="1" width="100%" cellpadding="2" cellspacing="0" class="fonts">
      <tr class="color0">
        <td class="data100">
          {!! nl2br(e($data[60]), false) !!}
          @for($i=substr_count($data[60], "\n"); $i<5; $i++)
            <br>
          @endfor
        </td>
      </tr>
    </table>
  </div>

</body>
</html>
