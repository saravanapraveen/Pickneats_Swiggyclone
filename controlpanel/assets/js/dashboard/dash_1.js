try {


  /*
      ==============================
      |    @Options Charts Script   |
      ==============================
  */

  /*
      ======================================
          Visitor Statistics | Options
      ======================================
  */

  
  // Total Visits

  var spark1 = {
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
          data: [21, 9, 36, 12, 44, 25, 59, 41, 66, 25]
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
      colors: ['#2196f3'],
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

  // Paid Visits

  var spark2 = {
      chart: {
        id: 'total-users',
        group: 'sparks1',
        type: 'line',
        height: 58,
        sparkline: {
          enabled: true
        },
      },
      series: [{
        data: [22, 19, 30, 47, 32, 44, 34, 55, 41, 69]
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
      colors: ['#e2a03f'],
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
                  top: 35,
                  bottom: 0,
                  left: 0
                }
            },
          },
      }
      ]
  }
  

  /*
      ===================================
          Unique Visitors | Options
      ===================================
  */

  // var d_1options1 = {
  //   chart: {
  //       height: 350,
  //       type: 'bar',
  //       toolbar: {
  //         show: false,
  //       }
  //   },
  //   colors: ['#517281', '#f67062'],
  //   plotOptions: {
  //       bar: {
  //           horizontal: false,
  //           columnWidth: '55%',
  //           endingShape: 'rounded'  
  //       },
  //   },
  //   dataLabels: {
  //       enabled: false
  //   },
  //   legend: {
  //         position: 'bottom',
  //         horizontalAlign: 'center',
  //         fontSize: '14px',
  //         markers: {
  //           width: 10,
  //           height: 10,
  //         },
  //         itemMargin: {
  //           horizontal: 0,
  //           vertical: 8
  //         }
  //   },
  //   stroke: {
  //       show: true,
  //       width: 2,
  //       colors: ['transparent']
  //   },
  //   series: [{
  //       name: 'Direct',
  //       data: [58, 44, 55, 57, 56, 61, 58, 63, 60, 66, 56, 63]
  //   }, {
  //     name: 'Organic',
  //     data: [91, 76, 85, 101, 98, 87, 105, 91, 114, 94, 66, 70]
  //   }],
  //   xaxis: {
  //       categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  //   },
  //   tooltip: {
  //       y: {
  //           formatter: function (val) {
  //               return val
  //           }
  //       }
  //   }
  // }


  var d_1options1 = {
    chart: {
      height: 350,
      type: 'line',
      toolbar: {
        show: false,
      }
    },
    plotOptions: {
      bar: {
          horizontal: false,
          columnWidth: '55%',
          // endingShape: 'rounded'  
      },
    },
    legend: {
      offsetX: 0,
      offsetY: -10,
    },
    // colors: ['#f67062', '#517281'],
    colors: ['#61b6cd', '#805dca'],
    // colors: ['#ffbb44', '#5c1ac3'],
    
    series: [{
      name: 'Organic',
      type: 'column',
      data: [4400, 5050, 4140, 6710, 2270, 4130, 2010, 3520, 7520, 3200, 2570, 1600]
    }, {
      name: 'Direct',
      type: 'line',
      data: [230, 420, 350, 270, 430, 220, 170, 310, 220, 220, 120, 160]
    }],
    // stroke: {
    //   width: [0, 4]
    // },

    stroke: {
      show: true,
      curve: 'smooth',
      width: [0, 4],
      lineCap: 'square'
    },
    // title: {
    //   text: 'Traffic Sources'
    // },
    // labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
    xaxis: {
      // type: 'datetime'
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: [{
      title: {
        text: 'Organic',
      },
  
    }, {
      opposite: true,
      title: {
        text: 'Direct'
      }
    }],

    responsive: [{
      breakpoint: 576,
      options: {
        yaxis: [{
          title: {
            text: undefined,
          },
      
        }, {
          opposite: true,
          title: {
            text: undefined
          }
        }],
      },
    }]
  
  }
  
  
  

  /*
      ==============================
          Statistics | Options
      ==============================
  */

  // Followers

  var d_1options3 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
        curve: 'smooth',
        width: 2,
    },
    series: [{
      name: 'Sales',
      data: [38, 60, 38, 52, 36, 40, 28 ]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#4361ee'],
    tooltip: {
      x: {
        show: false,
      }
    },
  }

  // Referral

  var d_1options4 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
        curve: 'smooth',
        width: 2,
    },
    series: [{
      name: 'Sales',
      data: [ 60, 28, 52, 38, 40, 36, 38]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#e7515a'],
    tooltip: {
      x: {
        show: false,
      }
    }
  }

  // Engagement Rate

  var d_1options5 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
        curve: 'smooth',
        width: 2,
    },
    fill: {
      opacity: 1,
    },
    series: [{
      name: 'Sales',
      data: [28, 50, 36, 60, 38, 52, 38 ]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#1abc9c'],
    tooltip: {
      x: {
        show: false,
      }
    }
  }

  


  /*
      ==============================
      |    @Render Charts Script    |
      ==============================
  */


  /*
      ======================================
          Visitor Statistics | Script
      ======================================
  */

  // Total Visits
  d_1C_1 = new ApexCharts(document.querySelector("#total-users"), spark1);
  d_1C_1.render();

  // Paid Visits
  d_1C_2 = new ApexCharts(document.querySelector("#paid-visits"), spark2);
  d_1C_2.render();

  /*
      ===================================
          Unique Visitors | Script
      ===================================
  */

  var d_1C_3 = new ApexCharts(
      document.querySelector("#uniqueVisits"),
      d_1options1
  );
  d_1C_3.render();

  /*
      ==============================
          Statistics | Script
      ==============================
  */


  // Followers

  var d_1C_5 = new ApexCharts(document.querySelector("#hybrid_followers"), d_1options3);
  d_1C_5.render()

  // Referral

  var d_1C_6 = new ApexCharts(document.querySelector("#hybrid_followers1"), d_1options4);
  d_1C_6.render()

  // Engagement Rate

  var d_1C_7 = new ApexCharts(document.querySelector("#hybrid_followers3"), d_1options5);
  d_1C_7.render()



/*
    =============================================
        Perfect Scrollbar | Notifications
    =============================================
*/
const ps = new PerfectScrollbar(document.querySelector('.mt-container'));


} catch(e) {
// statements
console.log(e);
}
