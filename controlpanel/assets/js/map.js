window.addEventListener('load', () => {
    let loadClass = document.getElementsByClassName('totalSales')
    let loadClassArea = document.getElementsByClassName('totalSalesArea')
    for(let i=0;i<loadClass.length;i++){
        let currentCityId = loadClass[i].id

        $.ajax({
            type: "POST",
            url: "ajax/getChartData.php",
            data:{'city_id': currentCityId},
            success: function(data){
                let parsedData = JSON.parse(data)
                if(parsedData.status){
                    var loadData = {
                        chart: {
                            id: 'unique-visits',
                            group: 'sparks2',
                            type: 'line',
                            height: 58,
                            sparkline: {
                                enabled: true
                            },
                        },
                        series: [{
                            data: parsedData.sales
                        }],
                        stroke: {
                          curve: 'smooth',
                          width: 2,
                        },
                        markers: {
                            size: 0
                        },
                        grid: {
                          padding: {
                            top: 0,
                            bottom: 0,
                            left: 0
                          }
                        },
                        colors: parsedData.color,
                        tooltip: {
                            x: {
                                show: false
                            },
                            y: {
                                title: {
                                    formatter: function formatter(val) {
                                        return '';
                                    }
                                }
                            }
                        },
                        responsive: [
                            {
                                breakpoint: 576,
                                options: {
                                chart: {
                                    height: 95,
                                },
                                grid: {
                                    padding: {
                                        top: 45,
                                        bottom: 0,
                                        left: 0
                                    }
                                },
                                },
                            }
                        ]
                    }
                    
                    let loadChart = new ApexCharts(document.getElementById(currentCityId), loadData);
                    loadChart.render();
                }
            }
        });
    }
    for(let i=0;i<loadClassArea.length;i++){
        let currentAreaId = loadClassArea[i].id

        $.ajax({
            type: "POST",
            url: "ajax/getChartDataArea.php",
            data:{'area_id': currentAreaId},
            success: function(data){
                let parsedData = JSON.parse(data)
                if(parsedData.status){
                    var loadData = {
                        chart: {
                            id: 'unique-visits',
                            group: 'sparks2',
                            type: 'line',
                            height: 58,
                            sparkline: {
                                enabled: true
                            },
                        },
                        series: [{
                            data: parsedData.sales
                        }],
                        stroke: {
                          curve: 'smooth',
                          width: 2,
                        },
                        markers: {
                            size: 0
                        },
                        grid: {
                          padding: {
                            top: 0,
                            bottom: 0,
                            left: 0
                          }
                        },
                        colors: parsedData.color,
                        tooltip: {
                            x: {
                                show: false
                            },
                            y: {
                                title: {
                                    formatter: function formatter(val) {
                                        return '';
                                    }
                                }
                            }
                        },
                        responsive: [
                            {
                                breakpoint: 576,
                                options: {
                                chart: {
                                    height: 95,
                                },
                                grid: {
                                    padding: {
                                        top: 45,
                                        bottom: 0,
                                        left: 0
                                    }
                                },
                                },
                            }
                        ]
                    }
                    
                    let loadChart = new ApexCharts(document.getElementById(currentAreaId), loadData);
                    loadChart.render();
                }
            }
        });
    }
})