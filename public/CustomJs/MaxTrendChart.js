const maxTrendChart = document.getElementById("maxTrendChart").getContext("2d");

// <block:setup:1>
const data = {
  labels: [
    "0.7:1 - BNKNFT",
    "0.7:1.3 - BNKNFT",
    "0.7:1.5 - BNKNFT",
    "1.5:1 - BNKNFT",
    "1.5:1.3 - BNKNFT",
    "1.5:1.5 - BNKNFT",
    "1:0.8 - BNKNFT",
    "1:1 - BNKNFT",
    "1:1.3 - BNKNFT",
  ],
  datasets: [
    {
      label: "Maximum Trend",
      data: [1358, 1368, 1579, 2539, 2577, 2678, 1604, 1837, 2021],
      fill: true,
      backgroundColor: "rgba(255, 99, 132, 0.2)",
      borderColor: "rgb(255, 99, 132)",
      pointBackgroundColor: "rgb(255, 99, 132)",
      pointBorderColor: "#fff",
      pointHoverBackgroundColor: "#fff",
      pointHoverBorderColor: "rgb(255, 99, 132)",
    },
    {
      label: "Actual Profit & Loss",
      data: [1525, -1193, 2243, 3450, -715, 1748, 343, 3298, 5795],
      fill: true,
      backgroundColor: "rgba(54, 162, 235, 0.2)",
      borderColor: "rgb(54, 162, 235)",
      pointBackgroundColor: "rgb(54, 162, 235)",
      pointBorderColor: "#fff",
      pointHoverBackgroundColor: "#fff",
      pointHoverBorderColor: "rgb(54, 162, 235)",
    },
  ],
};

const myMaxTrend = new Chart(maxTrendChart, {
  type: "radar",
  data: data,
  options: {
    elements: {
      line: {
        borderWidth: 3,
      },
    },
  },
});
