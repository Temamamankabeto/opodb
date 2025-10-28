<!DOCTYPE html>
<x-layout>
  <title>Midhaan innishatiivii: Boqolloo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f7f7f7;
      color: #222;
    }

    .header {
      background: black;
      color: white;
      font-size: 24px;
      font-weight: bold;
      padding: 10px 20px;
    }

    .highlight {
      color: yellow;
    }

    .content {
      display: flex;
      padding: 20px;
      background: url('corn-background.jpg') no-repeat center center;
      background-size: cover;
      flex-wrap: wrap;
    }

    .chart {
      flex: 1;
      padding: 10px;
      min-width: 350px;
    }

    .chart-section {
      margin-bottom: 40px;
    }

    .vertical-title {
      writing-mode: vertical-lr;
      text-align: center;
      color: red;
      font-size: 20px;
      margin-bottom: 10px;
      height: 200px;
    }

    .bars-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .bars {
      display: flex;
      gap: 10px;
      align-items: flex-end;
      max-height: 200px;
    }

    .bar {
      width: 30px;
      background: red;
      text-align: center;
      color: white;
      font-size: 12px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
    }

    .bar-labels {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 5px;
      font-size: 12px;
    }

    .table-container {
      flex: 1.5;
      padding: 10px;
      overflow-x: auto;
      min-width: 350px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #999;
      padding: 5px 10px;
      text-align: left;
    }

    th {
      background: #ddd;
    }
  </style>

  <div class="header">Midhaan innishatiivii: <span class="highlight">Boqolloo</span></div>

  <div class="content">
    <div class="chart">
      <div class="chart-section">
        <div class="vertical-title">Lafa (he.mil)</div>
        <div class="bars-wrapper">
          <div class="bars">
            <div class="bar" style="height: 133px;">1.33</div>
            <div class="bar" style="height: 145px;">1.45</div>
            <div class="bar" style="height: 141px;">1.41</div>
            <div class="bar" style="height: 173px;">1.73</div>
            <div class="bar" style="height: 178px;">1.78</div>
            <div class="bar" style="height: 196px;">1.96</div>
            <div class="bar" style="height: 219px;">2.19</div>
          </div>
          <div class="bar-labels">
            <div>2011/12</div>
            <div>2012/13</div>
            <div>2013/14</div>
            <div>2014/15</div>
            <div>2015/16</div>
            <div>2016/17</div>
            <div>2017/18</div>
          </div>
        </div>
      </div>

      <div class="chart-section">
        <div class="vertical-title">Oomisha (kun.mil)</div>
        <div class="bars-wrapper">
          <div class="bars">
            <div class="bar" style="height: 120px;">59.4</div>
            <div class="bar" style="height: 135px;">67.0</div>
            <div class="bar" style="height: 132px;">65.8</div>
            <div class="bar" style="height: 115px;">57.4</div>
            <div class="bar" style="height: 160px;">80.1</div>
            <div class="bar" style="height: 184px;">91.9</div>
            <div class="bar" style="height: 194px;">96.8</div>
          </div>
          <div class="bar-labels">
            <div>2011/12</div>
            <div>2012/13</div>
            <div>2013/14</div>
            <div>2014/15</div>
            <div>2015/16</div>
            <div>2016/17</div>
            <div>2017/18</div>
          </div>
        </div>
      </div>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Region</th>
            <th>Lafa (hek)</th>
            <th>Oomisha (kun)</th>
            <th>(kun/hek)</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Arsii</td><td>76,547</td><td>3,140,933</td><td>41.0</td></tr>
          <tr><td>Baalee</td><td>97,070</td><td>3,970,014</td><td>40.9</td></tr>
          <tr><td>Baalee Bahaa</td><td>30,306</td><td>1,361,559</td><td>44.9</td></tr>
          <tr><td>Ida'amaa</td><td>1,701,474</td><td>83,969,852</td><td>49.4</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</x-layout>
