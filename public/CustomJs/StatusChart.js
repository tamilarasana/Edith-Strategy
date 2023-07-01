let myChart = document.getElementById("myStatusChart").getContext("2d");
dataLabels = [];
dataValues = [];
barColors = [];
$.ajax({
  url: "https://strategy.kalyaniaura.com/api/reportdata",
  beforeSend: function (xhr) {
    xhr.overrideMimeType("application/json; charset=x-user-defined");
  },
}).done(function (data) {
  datasets = data.basket_status;
  datasets.forEach((element) => {
    dataLabels.push(element[0].status);
    dataValues.push(element[0].value);

    if (element[0].value < 0) {
      barColors.push("rgb(255, 112, 41)");
    } else {
      barColors.push("rgb(41, 255, 151)");
    }
  });

  new Chart(myChart, {
    type: "bar",
    data: {
      labels: dataLabels,
      datasets: [
        {
          label: "Profit & Loss",
          data: dataValues,
          backgroundColor: barColors,
        },
      ],
    },
  });
});
