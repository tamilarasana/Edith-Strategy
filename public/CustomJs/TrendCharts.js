// Profit and Loss Trend Chart

let pnlTrend = document.getElementById("pnlTrend").getContext("2d");
const pnlTrendlabels = ["JAN", "FEB", "MAR", "APRIL", "MAY", "JUN", "JUL"];
const pnlTrendData = [3000, 1000, 6000, 5700, 5000, 7000, 8000];
colors = [];
for (let i = 0; i < pnlTrendData.length; i++) {
  this.colors.push("#" + Math.floor(Math.random() * 16777215).toString(16));
}
let pnlTrendChart = new Chart(pnlTrend, {
  type: "line",
  data: {
    labels: pnlTrendlabels,
    datasets: [
      {
        label: "Net Profit & Loss",
        data: pnlTrendData,
        backgroundColor: colors,
      },
    ],
  },
});
