 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Google Ads Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #0d2b22, #1b4533);
      color: #fff;
    }
    .dashboard {
      max-width: 1600px;
      margin: auto;
      padding: 20px;
      display: grid;
      gap: 20px;
    }
    .grid-3 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    .card {
      background: #123c2e;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .card .title {
      font-size: 14px;
      color: #ccc;
      margin-bottom: 8px;
    }
    .card .value {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 6px;
    }
    .card .subtext {
      font-size: 14px;
      color: #90ee90;
    }
    .gauge, .chart {
      background: #234f3c;
      height: 120px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ccc;
      font-size: 14px;
    }
    .summary {
      background-color: #1e5141;
      border-left: 5px solid #f1c40f;
      padding: 15px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.6;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 14px;
    }
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #3c6f5a;
    }
    th {
      color: #f1c40f;
    }
    .footer {
      text-align: center;
      margin-top: 40px;
      color: #777;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    
    <div class="grid-3">
      <div class="card">
        <div class="title">Cost (Mar 26 - Apr 24)</div>
        <div class="value">$618.00</div>
        <div class="subtext">▼ 22% vs previous ($792.00)</div>
      </div>
      <div class="card">
        <div class="title">Conversion Value</div>
        <div class="value">1.24k</div>
        <div class="subtext">▲ 19% vs previous (1.04k)</div>
      </div>
      <div class="card">
        <div class="title">CPC</div>
        <div class="gauge">[ CPC Gauge Placeholder ]</div>
      </div>
      <div class="card">
        <div class="title">CPM</div>
        <div class="gauge">[ CPM Gauge Placeholder ]</div>
      </div>
      <div class="card">
        <div class="title">CTR</div>
        <div class="gauge">[ CTR Gauge Placeholder ]</div>
      </div>
      <div class="card">
        <div class="title">Clicks Over Time</div>
        <div class="chart">[ Clicks Line Chart ]</div>
      </div>
      <div class="card">
        <div class="title">Impressions Over Time</div>
        <div class="chart">[ Impressions Line Chart ]</div>
      </div>
      <div class="card" style="grid-column: span 2;">
        <div class="title">Summary</div>
        <div class="summary">
          The number of <strong>Impressions</strong> recorded in the last 30 days is <strong>6,992</strong>, down from <strong>8,558</strong>, indicating a <span style="color:red">-1,566</span> drop.<br><br>
          <strong>Clicks</strong> fell from <strong>392</strong> to <strong>211</strong> (▼ 181).<br>
          <strong>Cost</strong> dropped from <strong>$989.00</strong> to <strong>$925.00</strong>, saving <span style="color:limegreen">$64.00</span>.<br>
          <strong>CTR</strong> is high at <span style="color:limegreen">600%</span>.
        </div>
      </div>
    </div>

    <div class="card">
      <div class="title">Campaigns Overview</div>
      <table>
        <thead>
          <tr><th>Campaign</th><th>Impr.</th><th>Cost</th></tr>
        </thead>
        <tbody>
          <tr><td>Cyber Monday Sale</td><td>997</td><td>$652.00</td></tr>
          <tr><td>Mobile Only</td><td>659</td><td>$531.00</td></tr>
          <tr><td>Black Friday</td><td>601</td><td>$933.00</td></tr>
          <tr><td>Spring Sales</td><td>413</td><td>$111.00</td></tr>
          <tr><td>50% Off Clearance</td><td>222</td><td>$625.00</td></tr>
        </tbody>
      </table>
    </div>

    <div class="card">
      <div class="title">Ad Groups Overview</div>
      <table>
        <thead>
          <tr><th>Ad Group</th><th>Impr.</th><th>Cost</th></tr>
        </thead>
        <tbody>
          <tr><td>Sienna Winterheiler</td><td>990</td><td>772</td></tr>
          <tr><td>Annabelle Stehr</td><td>945</td><td>905</td></tr>
          <tr><td>Ms. Vivian Smith IV</td><td>930</td><td>754</td></tr>
          <tr><td>Karl Walter</td><td>929</td><td>341</td></tr>
          <tr><td>Mr. Grayson Swift III</td><td>918</td><td>693</td></tr>
        </tbody>
      </table>
    </div>

    <div class="card">
      <div class="title">Ads Overview</div>
      <table>
        <thead>
          <tr><th>Ad</th><th>Impr.</th><th>Cost</th></tr>
        </thead>
        <tbody>
          <tr><td>Christina Fahey</td><td>954</td><td>816</td></tr>
          <tr><td>Ms. Marcia Veum IV</td><td>936</td><td>133</td></tr>
          <tr><td>Prof. Cameron Ebert</td><td>928</td><td>583</td></tr>
          <tr><td>Loren Harin</td><td>888</td><td>532</td></tr>
          <tr><td>Linda Steuber</td><td>862</td><td>456</td></tr>
        </tbody>
      </table>
    </div>

    <div class="footer">
      Google Ads Dashboard – Sample UI © 2025
    </div>
  </div>
</body>
</html>
