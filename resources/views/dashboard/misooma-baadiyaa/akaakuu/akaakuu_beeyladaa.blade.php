<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OPO</title>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f0f2f5;
    }
    .container {
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .facebook-page h1 {
      color: #1877f2;
      font-size: 3rem;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1000px;
      width: 100%;
    }
    .card {
      background: white;
      border-radius: 16px;
      padding: 30px 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      text-decoration: none;
      color: #333;
      font-weight: 600;
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      background: #e6f0ff;
      color: #0a58ca;
    }
    .card::after {
      content: "→";
      display: block;
      margin-top: 10px;
      font-size: 1.5rem;
      color: #0a58ca;
    }
    .page-header {
  margin: 40px 0;
  text-align: center;
}
.page-header p {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #555;
  max-width: 800px;
  margin: 0 auto;
}

  </style>
</head>
<body>
  <div class="container">
    <div class="facebook-page flex">
      <h1>
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="120" height="80" />
        OPO
      </h1>
    </div>

    <div class="page-header">
      <h2>Inisheetiiviwwan Qonnaa</h2>
      <p>
        Barreeffamni armaan gadii dameewwan misoomaa garaagaraa kan qonnaa fi qabeenya uumamaa keessatti xiyyeeffatanii hojjetaman ibsu.
        Filannoo kamiiyyuu tuqaa keessaa tokkotti cuqaasuun odeeffannoo bal’inaan argachuuf ni dandeessu.
      </p>
    </div>

    <div class="card-grid">
        @foreach($livestocktypes as $livestocktype)
        <a href="{{ url('/livestock-type/'.$livestocktype->id.'/plan-achievement/') }}" class="card">{{$livestocktype->name}}</a>
        @endforeach
    </div>
  </div>
</body>
</html>
