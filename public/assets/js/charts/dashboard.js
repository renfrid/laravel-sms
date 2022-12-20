$.getJSON('api/dashboard/monthly_status', (data) => {
    if (data.error == false) {
        var options = {
            series: [{
                name: "Total SMS",
                data: data.data.total_sms
            },
            {
                name: "Pending SMS",
                data: data.data.pending_sms
            },
            {
                name: "Delivered SMS",
                data: data.data.delivered_sms
            },
            {
                name: "Undelivered SMS",
                data: data.data.undelivered_sms
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: data.data.month,
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            colors: ["#0D47A1", "#FF8F00", "#1B5E20", "#C62828"],
        },
        chart = new ApexCharts(document.querySelector("#delivery-status-chart"), options);
        chart.render();
    }
});