<div class="cards-container">
  <div class="presentation-card-wrapper">
    <div class="presentation-card">
      <h2>Eurostat</h2>
      <p>View statistics on Europe provided by Eurostat</p>
      <div class="presentation-buttons-container">
        <a class="button" href="https://ec.europa.eu/eurostat/databrowser/view/sdg_02_10/default/table">Source</a>
        <a class="button" href="/eurostat">View</a>
      </div>
    </div>
  </div>
  <div class="presentation-card-wrapper">
    <div class="presentation-card">
      <h2>World Health Organization</h2>
      <p>View statistics on the world provided by the World Health Organization</p>
      <div class="presentation-buttons-container">
        <a class="button" href="https://www.who.int/data/gho/data/themes/theme-details/GHO/body-mass-index-(bmi)">Source</a>
        <a class="button" href="/who">View</a>
      </div>
    </div>
  </div>
</div>
<h1>What is obesity?</h1>
<p>
  Obesity is a medical condition in which excess body fat has accumulated to
  an extent that it may have a negative effect on health. People are generally considered
  obese when their body mass index (BMI), a measurement obtained by dividing a person's weight by the
  square of the person's height—despite known allometric inaccuracies is over 30 kg/m<sup>2</sup>;
  the range 25–30 kg/m<sup>2</sup> is defined as overweight.
</p>
<dl class="bmi-categories-list">
  <div class="bmi-category">
    <dt>BMI &lt;18.5</dt>
    <dd>Underweight</dd>
  </div>
  <div class="bmi-category">
    <dt>BMI 18.5-25</dt>
    <dd>Normal weight</dd>
  </div>
  <div class="bmi-category">
    <dt>BMI 25-30</dt>
    <dd>Overweight</dd>
  </div>
  <div class="bmi-category">
    <dt>BMI >30</dt>
    <dd>Obese</dd>
  </div>
</dl>
<h1>Facts about obesity</h1>
<p>Some recent WHO global estimates follow.</p>
<ul>
  <li>In 2016, more than 1.9 billion adults aged 18 years and older were overweight. Of these over 650 million adults were obese.</li>
  <li>In 2016, 39% of adults aged 18 years and over (39% of men and 40% of women) were overweight.</li>
  <li>Overall, about 13% of the world’s adult population (11% of men and 15% of women) were obese in 2016.</li>
  <li>The worldwide prevalence of obesity nearly tripled between 1975 and 2016.</li>
</ul>
<h1>Examples</h1>
<p>Below is a line chart showing data from Eurostat from Austria.</p>
<?php include('views/components/lineChart.php'); ?>
<p>This is a bar chart showing data from Eurostat with the obesity category selected, ordered by location.</p>
<?php include('views/components/barChart.php'); ?>
<p>This shows the same data as the previous chart, but as a table.</p>
<?php include('views/components/table.php'); ?>
