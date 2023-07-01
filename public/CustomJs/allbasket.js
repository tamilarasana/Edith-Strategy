const allbasket = document.getElementById("allbasketChart").getContext("2d");

basket_names = [];
basket_value = [];

$.ajax({
  url: "https://strategy.kalyaniaura.com/api/reportdata",
  beforeSend: function (xhr) {
    xhr.overrideMimeType("application/json; charset=x-user-defined");
  },
}).done(function (data) {
  basket_pnl = data.basket_pnl;
  basket_pnl.forEach((element) => {
    basket_names.push(element.basket_name);
    basket_value.push(element.value);
  });

  const datas = {
    labels: basket_names,
    datasets: [
      {
        label: "My First Dataset",
        data: basket_value,
        backgroundColor: [
          "rgb(255, 99, 132)",
          "rgb(75, 192, 192)",
          "rgb(255, 205, 86)",
          "rgb(201, 203, 207)",
          "rgb(54, 162, 235)",
        ],
      },
    ],
  };

  new Chart(allbasket, {
    type: "polarArea",
    data: datas,
    options: {
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
});
