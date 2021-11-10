<?php
$id = $_GET['id'];
?>

<div class="row text-dark">
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-indigo">
      <div class="credict-card-content">
        <div class="fw-bold">NET</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$net}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-blue">
      <div class="credict-card-content">
        <div class="fw-bold">Available Funds</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availablecash}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-green">
      <div class="credict-card-content">
        <div class="fw-bold">Available Day Payin</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availableintradaypayin}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
</div>

<div class="row text-dark">
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-green">
      <div class="credict-card-content">
        <div class="fw-bold">Available available Limit Margin</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availablelimitmargin}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-indigo">
      <div class="credict-card-content">
        <div class="fw-bold">M2M Unrealized</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$m2munrealized}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-blue">
      <div class="credict-card-content">
        <div class="fw-bold">M2M Realized</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$m2mrealized}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
</div>

<div class="row text-dark">
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-blue">
      <div class="credict-card-content">
        <div class="fw-bold">Utilized Turnover</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utilisedturnover}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-green">
      <div class="credict-card-content">
        <div class="fw-bold">Utilized Debits</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utiliseddebits}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
  <div class="col-md-6 col-xl-4 mb-4">
    <div class="card credit-card bg-hover-gradient-indigo">
      <div class="credict-card-content">
        <div class="fw-bold">Utilised Span</div>
        <div class="credict-card-bottom">
          <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utilisedspan}}</h4>
        </div>
      </div><a class="stretched-link" href="#"></a>
    </div>
  </div>
</div>