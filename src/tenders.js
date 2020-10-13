import jquery from 'jquery';
window.jQuery = jquery;
window.$ = jquery;
require( 'datatables.net' )( window, $ )
require( 'datatables.net-dt' )( window, $ )

import underscore from 'underscore';
window.underscore = underscore;
window._ = underscore;

import '../public/vendor/js/popper.min.js'
import '../public/vendor/js/bootstrap.min.js'
import { csv } from 'd3-request'
import { json } from 'd3-request'

import '../public/vendor/css/bootstrap.min.css'
import '../public/vendor/css/dc.css'
import '/scss/main.scss';

import Vue from 'vue';
import Loader from './components/Loader.vue';
import ChartHeader from './components/ChartHeader.vue';


// Data object - is also used by Vue

var vuedata = {
  page: 'redflags',
  oldcommission: false,
  loader: true,
  showInfo: true,
  showShare: true,
  chartMargin: 40,
  showCities: true,
  mapZoom: 0,
  organizations: {},
  citiesStats: {},
  countriesStats: {},
  infoboxTitle: "Select",
  charts: {
    dotsMap: {
      title: 'Map',
      info: 'Description'
    },
    ctype: {
      title: 'Contract type',
      info: 'Description'
    },
    sector: {
      title: 'Top Sectors',
      info: 'Description'
    },
    flagsNum: {
      title: 'Flags Number',
      info: 'Description'
    },
    flagsType: {
      title: 'Flags Type',
      info: 'Description'
    },
    table: {
      chart: null,
      type: 'table',
      title: 'Notices',
      info: ''
    }
  },
  flagsTypes: {
    "a": "Term of contract (long or indefinite)",
    "b": "Number of tenders received low",
    "c": "Difference between final value and estimated value",
    "d": "High final value without estimated value",
    "e": "Not awarded without reason",
    "f": "Missing contract date",
    "g": "Procedure succesful without prior publication",
    "h": "Framework agreement with limited tenderer",
    "j": "Framework agreement concluded for long period without justification",
    "k": "Estimated value of framework agreement (high)",
    "l": "Economic & financial ability – no minimum requirements",
    "m": "Economic & financial ability – criteria for captial levels",
    "n": "Use of accelerated procedure without justification",
    "o": "Use of competitive negotiated procedure without justification",
    "p": "Deadline for bids differ from date of openining",
    "q": "Framework agreement with several bidders - number of participants too low",
    "r": "Restricted procedures - number of candidates low",
    "s": "Negotiated procedures - number of candidates low",
    "t": "Contract can be renewed (for a long time or several times)",
    "u": "Contract can be renewed without any information",
    "v": "Too few evaluation criteria",
    "w": "Negotiated without a prior call for competition"
  },
  codes: {
    //Type of Authority
    AA: {
    "1": "Ministry or any other national or federal authority",
    "3": "Regional or local authority",
    "4": "Utilities entity",
    "5": "European Institution/Agency or International Organisation",
    "6": "Body governed by public law",
    "N": "National or federal Agency/Office",
    "R": "Regional or local Agency/Office",
    "8": "Other",
    "9": "Other",
    "Z": "Other"
    },
    //Award Criteria
    AC: {
    "1": "Lowest price",
    "2": "The most economic tender",
    "3": "Mixed",
    "8": "Other",
    "9": "Other",
    "Z": "Other"
    },
    //Legal Basis
    DI: {
    "1":"Classical Directive (2004/18/EC)",
    "2":"Utilities Directive (2004/17/EC)",
    "3":"Defence Directive (2009/81/EC)",
    "4":"Regulation ”Public passenger transport” (1370/2007/EC)",
    "5":"Concession contract Directive (2014/23/EU)",
    "6":"Public procurement Directive (2014/24/EU)",
    "7":"Utilities (water, energy, transport and postal services) Directive (2014/25/EU)",
    },
    //Main Activities
    MA: {
    "A": "Housing and community amenities",
    "B": "Social protection",
    "C": "Recreation, culture and religion",
    "D": "Defence",
    "E": "Environment",
    "F": "Economic and financial affairs",
    "G": "Production, transport and distribution of gas and heat",
    "H": "Health",
    "I": "Airport-related activities",
    "J": "Extraction of gas and oil",
    "K": "Port-related activities / Maritime or inland waterway",
    "L": "Education",
    "M": "Exploration and extraction of coal and other solid fuels",
    "N": "Electricity",
    "O": "Other",
    "P": "Postal services",
    "R": "Railway services",
    "S": "General public services",
    "T": "Urban railway / light rail, metro, tramway, trolleybus or bus services",
    "U": "Public order and safety",
    "W": "Water",
    "8": "Other",
    "9": "Other",
    "Z": "Other"
    },
    //Type of contract
    NC: {
    "1": "Works",
    "2": "Supplies",
    "3": "Combined",
    "4": "Services",
    "8": "Not defined",
    "9": "Not defined",
    "Z": "Not defined"
    },
    //Type of procedure
    PR: {
    "1": "Open procedure",
    "2": "Restricted procedure",
    "3": "Accelerated restricted procedure",
    "4": "Negotiated procedure",
    "6": "Accelerated negotiated procedure",
    "A": "Direct Award",
    "B": "Competitive procedure with negotiation",
    "C": "Competitive dialogue",
    "E": "Concession award procedure",
    "F": "Concession award without prior concession notice",
    "G": "Innovation partnership",
    "T": "Negotiated without a prior call for competition",
    "V": "Contract award without prior publication",
    "8": "Other",
    "9": "Other",
    "Z": "Other"
    },
    //Market regulation
    RP: {
    "0": "PHARE, TACIS and countries of Central and Eastern Europe",
    "1": "External aid and European Development Fund",
    "2": "European Investment Bank, European Investment Fund, European Bank for Reconstruction and Development",
    "3": "European Institution/Agency or International Organisation",
    "4": "European Union",
    "5": "European Union, with participation by GPA countries",
    "6": "European Economic Area (EEA)",
    "7": "GPA",
    "B": "European Economic Area (EEA), with participation by GPA countries",
    "S": "Agreement between the European Union and the Swiss Confederation",
    "8": "Other",
    "9": "Other",
    "Z": "Other"
    },
    //Type of document
    TD: {
    "0": "Prior Information Notice without call for competition",
    "1": "Corrigendum",
    "2": "Additional information (IC = AA changes and cancellations)",
    "3": "Contract notice",
    "4": "Prequalification notices",
    "5": "Request for proposals",
    "6": "General information",
    "7": "Contract award notice",
    "A": "Prior Information Notice with call for competition",
    "B": "Buyer profile",
    "C": "Public Works concession",
    "D": "Design contest",
    "E": "Works contracts awarded by the concessionnaire",
    "F": "Subcontracts notice (in the fields of defence and security)",
    "G": "European economic interest grouping (EEIG)",
    "H": "Services concession",
    "I": "Call for expressions of interest",
    "J": "Concession award notice",
    "K": "Modification of a contract/concession during its term",
    "M": "Periodic indicative notice (PIN) with call for competition",
    "O": "Qualification system with call for competition",
    "P": "Periodic indicative notice (PIN) without call for competition",
    "Q": "Qualification system without call for competition",
    "R": "Results of design contests",
    "S": "European company",
    "Y": "Dynamic purchasing system",
    "V": "Voluntary ex ante transparency notice",
    "8": "Other",
    "9": "Other"
    },
    //Type of bid
    TY: {
    "1": "Submission for all lots",
    "2": "Submission for one lot only",
    "3": "Submission for one or more lots",
    "8": "Not defined / Other",
    "9": "Other",
    "Z": "Other"
    },
    //European Institutions
    HA: {
    "PA": "European Parliament",
    "CL": "Council of the European Union",
    "other": "European Institutions",
    "EC": "European Commission",
    "CJ": "Court of Justice of the European Union",
    "BC": "European Central Bank",
    "CA": "European Court of Auditors",
    "EA": "European External Action Service",
    "ES": "European Economic and Social Committee",
    "CR": "European Committee of the Regions",
    "BI": "European Investment Bank",
    "FI": "European Investment Fund",
    "OP": "Publications Office of the European Union",
    "AG": "Agencies",
    "OB": "European Patent Office",
    "BR": "European Bank of Reconstruction and Development",
    "TX": "External aid and European Development Fund",
    "AP": "External aid programmes"
    },
    otherCodes: {
    "OJ": "Edition number",
    "CY": "Country",
    "ND": "Document number",
    "PD": "Publication date",
    "PC": "CPV code",
    "RC": "NUTS code",
    "DD": "Documentation date",
    "DT": "Deadline",
    "TW": "Place",
    "AU": "Authority name"
    }
  },
  selEl: { "P": "", "Sub": ""},
  selectedCity: {},
  selectedCountry: "",
  choroplethType: "percentFlagged",
  tooltipData: {},
  colors: {
    default: "#319aab",
    default2: "#df3152",
    contractType: {
      "Services": "#117a8b",
      "Supplies": "#319aab",
      "Works": "#51bacb"
    },
    flags: {
      "0": "#319aab",
      "1": "#de6169",
      "2": "#df3152",
      "3": "#d11949",
      "4": "#c71040",
      "5+": "#b8093a"
    },
    flagsMap: ["#117a8b","#de6169","#df3152","#d11949","#c71040","#b8093a"]
  }
}

//Set vue components and Vue app
Vue.component('chart-header', ChartHeader);
Vue.component('loader', Loader);

new Vue({
  el: '#app',
  data: vuedata,
  methods: {
    //Share
    share: function (platform) {
      if(platform == 'twitter'){
        var thisPage = window.location.href.split('?')[0];
        var shareText = 'Lorem Ipsum ' + thisPage;
        var shareURL = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText);
        window.open(shareURL, '_blank');
        return;
      }
      if(platform == 'facebook'){
        //var toShareUrl = window.location.href.split('?')[0];
        var toShareUrl = 'https://integritywatch.eu';
        var shareURL = 'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(toShareUrl);
        window.open(shareURL, '_blank', 'toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250,top=300,left=300');
        return;
      }
    },
    pathExists: function (pathString) {
      var args = pathString.split('.');
      var currentPath = this.selEl;
      for (var i = 0; i < args.length; i++) {
        if(currentPath[args[i]]) {
          currentPath = currentPath[args[i]];
        } else {
          return false;
        }
      }
      return true;
    },
    pathExistsLot: function (pathString, pathBase) {
      var args = pathString.split('.');
      var currentPath = pathBase;
      for (var i = 0; i < args.length; i++) {
        if(currentPath[args[i]]) {
          currentPath = currentPath[args[i]];
        } else {
          return false;
        }
      }
      return true;
    },
    hasLeftiInfo: function(el) {
      if(el.fullData && el.fullData.FORM_SECTION_EN.LEFTI) {
        var lefti = el.fullData.FORM_SECTION_EN.LEFTI;
        if(lefti) {
          if(lefti.PERFORMANCE_STAFF_QUALIFICATION || lefti.ECONOMIC_CRITERIA_DOC || lefti.TECHNICAL_CRITERIA_DOC || lefti.ECONOMICAL_FINANCIAL_INFO || lefti.TECHNICAL_PROFESSIONAL_INFO || lefti.PERFORMANCE_CONDITIONS ) {
            return true;
          }
          return false;
        }
        return false;
      }
      return false;
    },
    findTedUrl: function(uriList) {
      var html = "ttt";
      if(uriList && Array.isArray(uriList) && uriList.length > 0) {
        _.each(uriList, function (url) {
          if(url.indexOf(":TEXT:EN:HTML") > -1) {
            html = "<a href='" + url + "' target='_blank'>" + url + "</a>";
          }
        });
      } else if(uriList && uriList !== "") {
        html = "<a href='" + uriList + "' target='_blank'>" + uriList + "</a>";
      } else {
        return "/";
      }
      return html;
    },
    addThousandsSeparator: function(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
  }
});

//Initialize info popovers
$(function () {
  $('[data-toggle="popover"]').popover()
})

//Charts
var charts = {
  ctype: {
    chart: dc.pieChart("#ctype_chart"),
    type: 'pie',
    divId: 'ctype_chart'
  },
  sector: {
    chart: dc.rowChart("#sector_chart"),
    type: 'row',
    divId: 'sector_chart'
  },
  flagsNum: {
    chart: dc.pieChart("#flagsnum_chart"),
    type: 'pie',
    divId: 'flagsnum_chart'
  },
  flagsType: {
    chart: dc.rowChart("#flagstype_chart"),
    type: 'row',
    divId: 'flagstype_chart'
  },
  meetingsTable: {
    chart: null,
    type: 'table',
  }
}

//Functions for responsivness
var recalcWidth = function(divId) {
  return document.getElementById(divId).offsetWidth - vuedata.chartMargin;
};
var recalcWidthWordcloud = function() {
  //Replace element if with wordcloud column id
  var width = document.getElementById("party_chart").offsetWidth - vuedata.chartMargin*2;
  return [width, 550];
};
var recalcCharsLength = function(width) {
  return parseInt(width / 8);
};
var calcPieSize = function(divId) {
  var newWidth = recalcWidth(divId);
  var sizes = {
    'width': newWidth,
    'height': 0,
    'radius': 0,
    'innerRadius': 0,
    'cy': 0,
    'legendY': 0
  }
  if(newWidth < 300) { 
    sizes.height = newWidth + 170;
    sizes.radius = (newWidth)/2;
    sizes.innerRadius = (newWidth)/4;
    sizes.cy = (newWidth)/2;
    sizes.legendY = (newWidth) + 30;
  } else {
    sizes.height = newWidth*0.75 + 170;
    sizes.radius = (newWidth*0.75)/2;
    sizes.innerRadius = (newWidth*0.75)/4;
    sizes.cy = (newWidth*0.75)/2;
    sizes.legendY = (newWidth*0.75) + 30;
  }
  return sizes;
};
var resizeGraphs = function() {
  for (var c in charts) {
    var sizes = calcPieSize(charts[c].divId);
    var newWidth = recalcWidth(charts[c].divId);
    var charsLength = recalcCharsLength(newWidth);
    if(charts[c].type == 'row'){
      charts[c].chart.width(newWidth);
      charts[c].chart.label(function (d) {
        var thisKey = d.key;
        if(thisKey.indexOf('###') > -1){
          thisKey = thisKey.split('###')[0];
        }
        if(thisKey.length > charsLength){
          return thisKey.substring(0,charsLength) + '...';
        }
        return thisKey;
      })
      //Custom resize for chart 3 and 4
      if(charts[c].divId == 'group_chart' || charts[c].divId == 'country_chart') {
        charts[c].chart.x(d3.scaleLinear().range([0,(charts[c].chart.width()-0)]).domain([0,100]));
        charts[c].chart.xAxis()
          .scale(charts[c].chart.x())
          .ticks(5)
          .tickFormat(function(d) { return d + '%' });
      }
      charts[c].chart.redraw();
    } else if(charts[c].type == 'bar') {
      charts[c].chart.width(newWidth);
      charts[c].chart.rescale();
      charts[c].chart.redraw();
    } else if(charts[c].type == 'pie') {
      charts[c].chart
        .width(sizes.width)
        .height(sizes.height)
        .cy(sizes.cy)
        .innerRadius(sizes.innerRadius)
        .radius(sizes.radius)
        .legend(dc.legend().x(0).y(sizes.legendY).gap(10).legendText(function(d) { 
          var thisKey = d.name;
          if(thisKey.length > charsLength){
            return thisKey.substring(0, charsLength) + '...';
          }
          return thisKey;
        }));
      charts[c].chart.redraw();
    } else if(charts[c].type == 'cloud') {
      charts[c].chart.size(recalcWidthWordcloud());
      charts[c].chart.redraw();
    }
  }
};
//Add commas to thousands
function addcommas(x){
  if(parseInt(x)){
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  return x;
}
//Custom date order for dataTables
var dmy = d3.timeParse("%d/%m/%Y");
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
  "date-eu-pre": function (date) {
    if(date.indexOf("Cancelled") > -1){
      date = date.split(" ")[0];
    }
      return dmy(date);
  },
  "date-eu-asc": function ( a, b ) {
      return ((a < b) ? -1 : ((a > b) ? 1 : 0));
  },
  "date-eu-desc": function ( a, b ) {
      return ((a < b) ? 1 : ((a > b) ? -1 : 0));
  }
});
//Get URL parameters
function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

//Load data and generate charts
//Generate random parameter for dynamic dataset loading (to avoid caching)

var randomPar = '';
var randomCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
for ( var i = 0; i < 5; i++ ) {
  randomPar += randomCharacters.charAt(Math.floor(Math.random() * randomCharacters.length));
}

var tendersDataFile = './data/tenders.json';
var citiesDataFile = './data/cities_geocoded.json';
var europeMap = './data/europe.geo.json';
var sectorCodesFile = './data/CPV_codes.csv';

//Load tenders data
json(tendersDataFile + '?' + randomPar, (err, tenders) => {
  json(citiesDataFile + '?' + randomPar, (err, cities) => {
    json(europeMap + '?' + randomPar, (err, europeGeojson) => {
      csv(sectorCodesFile, (err, sectorCodes) => {
        if (err) {
          console.error(err)
        }
        //tenders = tenders.results;
        //Loop through meetings data to apply data fixes
        _.each(tenders, function (d) {
          //Flags
          d.flagsNames = [];
          if(!d.flags){
            d.flags = [];
            d.flagsRange = 0;
          } else {
            if(d.flags.length >= 5) {
              d.flagsRange = "5+";
            } else {
              d.flagsRange = d.flags.length.toString();
            }
            _.each(d.flags, function (f) {
              d.flagsNames.push(vuedata.flagsTypes[f]);
            });
          }
          //Save cities and countries stats
          //countriesStats, citiesStats
          if(d.cb_town && d.cb_name_fixed) {
            if(d.cb_town == "Warsaw") {
              console.log(d);
            }
            if(!vuedata.citiesStats[d.cb_town]) {
              vuedata.citiesStats[d.cb_town] = {
                tenders: 0,
                flaggedTenders: 0,
                totFlags: 0
              }
            }
            vuedata.citiesStats[d.cb_town].tenders ++;
            vuedata.citiesStats[d.cb_town].totFlags += d.flags.length;
            if(d.flags.length > 0) {
              vuedata.citiesStats[d.cb_town].flaggedTenders ++;
            }
          }
          if(!vuedata.countriesStats[d.CY]) {
            vuedata.countriesStats[d.CY] = {
              tenders: 0,
              flaggedTenders: 0,
              totFlags: 0
            }
          }
          vuedata.countriesStats[d.CY].tenders ++;
          if(d.flags.length > 0) {
            vuedata.citiesStats[d.cb_town].flaggedTenders ++;
            vuedata.countriesStats[d.CY].flaggedTenders ++;
          }
          vuedata.countriesStats[d.CY].totFlags += d.flags.length;
          //Codes and acroynms
          d.authorityType = vuedata.codes.AA[d.AA];
          d.awardCriteria = vuedata.codes.AC[d.AC];
          d.legalBasis = vuedata.codes.DI[d.DI];
          if(!d.legalBasis) {
            d.legalBasis = d.DI;
          }
          d.mainActivities = vuedata.codes.MA[d.MA];
          d.contractType = vuedata.codes.NC[d.NC];
          d.procedureType = vuedata.codes.PR[d.PR];
          d.marketRegulation = vuedata.codes.RP[d.RP];
          d.documentType = vuedata.codes.TD[d.TD];
          d.bidType = vuedata.codes.TY[d.TY];
          d.europeanInstitutions = vuedata.codes.HA[d.HA];
          //Sectors sectorCodes
          d.sectors = [];
          d.sectorsNames = [];
          if(d.PC) {
            d.sectors = d.PC;
            _.each(d.sectors, function (s) {
              var thisSector = _.find(sectorCodes, function(sc){ return sc.CODE == s; });
              if(thisSector) {
                d.sectorsNames.push(thisSector.EN);
              }
            });
          }
        });
        console.log(tenders);
        console.log(vuedata.countriesStats);
        console.log(vuedata.citiesStats);

        //Set dc main vars
        var ndx = crossfilter(tenders);
        //Dimensions used for filtering outside of DC
        //Can then get the dataset to use for example for the map parsing like this: searchDimension.top(Infinity)
        var searchDimension = ndx.dimension(function (d) {
          var entryString = "iso2:" + d.CY + " i_name:" + d.cb_name + " i_type:" + d.cb_type + " i_town:" + d.cb_town + " title:" + d.title_en;
          return entryString.toLowerCase();
        });
        //When clicking on institution in list apply filter, like for search
        $('#map-side-panel').on('click', '.selected-city-container .institution-list-el', function () {
          var instName = $(this).text().trim();
          $('.institution-list-el').removeClass('selected');
          $(this).addClass('selected');
          searchDimension.filter(function(d) { 
            return d.indexOf(instName.toLowerCase()) !== -1;
          });
          throttle();
          var throttleTimer;
          function throttle() {
            window.clearTimeout(throttleTimer);
            throttleTimer = window.setTimeout(function() {
                dc.redrawAll();
            }, 250);
          }
        });

        var getCountryColor = function(iso2) {
          var currentDataFull = searchDimension.top(Infinity);
          var totalFilteredTenders = currentDataFull.length;
          var currentData = currentDataFull.filter(function(d) { 
            return d.CY == iso2;
          });
          var tendersNum = currentData.length;
          var flaggedTendersNum = 0;
          var flagsNum = 0;
          if(tendersNum == 0) {
            return "#bbb";
          } else {
            _.each(currentData, function (d) {
              if(d.flags && d.flags.length > 0) {
                flaggedTendersNum ++;
                flagsNum += d.flags.length;
              }
            });
            //Save data for tooltip
            vuedata.tooltipData[iso2] = {};
            vuedata.tooltipData[iso2].tendersNum = tendersNum;
            vuedata.tooltipData[iso2].flaggedTendersNum = flaggedTendersNum;
            vuedata.tooltipData[iso2].flagsNum = flagsNum;
            if(vuedata.choroplethType == 'percentFlagged') {
              //Calc percentage of flags
              var flagsPercentage = (flagsNum*100)/tendersNum;
              var flaggedPercentage = (flaggedTendersNum*100)/tendersNum;
              //Save data for tooltip
              vuedata.tooltipData[iso2].flaggedPercentage = flaggedPercentage.toFixed(0) + "%";
              var valueToUse = flaggedPercentage;
              var mapColorScale = d3.scaleLinear()
                .domain([0, 20, 50, 70])
                .range(['#117a8b', '#ff7a73', '#df3152', '#ae0333']);
              if(valueToUse < 10) {
                return vuedata.colors.flagsMap[0];
              } else {
                return mapColorScale(valueToUse);
              }
            } else {
              //Save data for tooltip
              var maxTotDivider = 5;
              if(tendersNum > totalFilteredTenders/maxTotDivider) {
                return "#7b0023";
              }
              var mapColorScale = d3.scaleLinear()
                .domain([1, totalFilteredTenders/(maxTotDivider*8), totalFilteredTenders/(maxTotDivider*3), totalFilteredTenders/maxTotDivider])
                .range(['#ccc', '#e0758c', '#ae0333', '#880026']);
              return mapColorScale(tendersNum);
            }
          }
        };

        function updateMap() {
          d3.selectAll('.land').transition() 
            .duration(750) 
            .attr("fill", function (d){
              return getCountryColor(d.properties.iso_a2);
            });
        }

        function selectCountry(iso2) {
          vuedata.selectedCountry = iso2;
          vuedata.selectedCity = {};
          vuedata.infoboxTitle = iso2;
          $('.institution-list-el').removeClass('selected');
          searchDimension.filter(function(d) { 
            return d.indexOf("iso2:" + iso2.toLowerCase()) !== -1;
          });
          throttle();
          var throttleTimer;
          function throttle() {
            window.clearTimeout(throttleTimer);
            throttleTimer = window.setTimeout(function() {
                dc.redrawAll();
                updateMap();
            }, 250);
          }
        }

        function selectCity(cityData) {
          vuedata.selectedCity = cityData;
          vuedata.selectedCountry = "";
          vuedata.infoboxTitle = cityData.city;
          $('.institution-list-el').removeClass('selected');
          searchDimension.filter(function(d) { 
            return d.indexOf("i_town:" + cityData.city.toLowerCase()) !== -1;
          });
          throttle();
          var throttleTimer;
          function throttle() {
            window.clearTimeout(throttleTimer);
            throttleTimer = window.setTimeout(function() {
                dc.redrawAll();
                updateMap();
            }, 250);
          }
        }

        //Toggle Cities
        $('.toggle-cities-btn').click(function(){
          vuedata.showCities = !vuedata.showCities;
          if(vuedata.showCities) {
            $(this).addClass('active');
            svg.selectAll("circle").transition() 
              .duration(750) 
              .style("opacity", 0.7)
              .attr("r", function(d){
                if(vuedata.mapZoom !== 0) {
                  return calcDotSize(d.institutions.length)/vuedata.mapZoom;
                } else {
                  return calcDotSize(d.institutions.length);
                }
              });
          } else {
            $(this).removeClass('active');
            svg.selectAll("circle").transition() 
              .duration(750) 
              .style("opacity", 0.0)
              .attr("r", function(d){return 0});
          }
        });

        //Change choropleth type
        $('#choropleth-type-percent').click(function(){
          $('.choropleth-type-btn').removeClass('active');
          $(this).addClass('active');
          vuedata.choroplethType = 'percentFlagged';
          updateMap();
        });
        $('#choropleth-type-amount').click(function(){
          $('.choropleth-type-btn').removeClass('active');
          $(this).addClass('active');
          vuedata.choroplethType = 'tendersAmt';
          updateMap();
        });


        //Tooltip
        var mapTooltip = d3.select("body").append("div").attr("class", "mapTooltip");

        //DOTS MAP
        var countries = europeGeojson.features;
        var map_container_width = $("#map").width();
        var width = map_container_width * 1;
        var height = width/2;
        //SVG container
        var svg = d3.select("#map").append("svg")
          .attr("width", width)
          .attr("height", height);
        const markerGroup = svg.append('g');
        //Projection
        var projection = d3.geoMercator()
          .scale([width/2])
          .center([8.245238,52.882242])
          .translate([width / 2, height / 2])
          .precision(.1);
        var path = d3.geoPath()
          .projection(projection);
        //Draw water
        svg.append("path")
          .datum({type: "Sphere"})
          .attr("class", "water")
          .attr("d", path);
        //Draw countries
        var world = svg.selectAll("path.land")
          .data(countries)
          .enter().append("path")
            .attr("class", "land")
            .attr("fill", function (d){
              return getCountryColor(d.properties.iso_a2);
            })
            .attr("d", path)
          //Mouse events
          .on("mouseover", function(d) {
            var countryVal = vuedata.tooltipData[d.properties.iso_a2];
            mapTooltip
              .html(function(a) {
                if(countryVal) {
                  var iso2 = d.properties.iso_a2;
                  var tooltipHtml = "<div class='tooltip-title'>" + iso2 + "</div>";
                  if(vuedata.tooltipData[iso2].tendersNum) { tooltipHtml += "<div class='tooltip-info'>Total tenders: " + vuedata.tooltipData[iso2].tendersNum + "</div>"; }
                  if(vuedata.tooltipData[iso2].flaggedTendersNum) { tooltipHtml += "<div class='tooltip-info'>Flagged tenders: " + vuedata.tooltipData[iso2].flaggedTendersNum + "</div>"; }
                  if(vuedata.tooltipData[iso2].flagsNum) { tooltipHtml += "<div class='tooltip-info'>Total flags: " + vuedata.tooltipData[iso2].flagsNum + "</div>"; }
                  if(vuedata.tooltipData[iso2].flaggedPercentage) { tooltipHtml += "<div class='tooltip-info'>Percentage of flagged tenders: " + vuedata.tooltipData[iso2].flaggedPercentage + "</div>"; }
                  return tooltipHtml;
                } else {
                  return d.properties.iso_a2 + ": no data";
                }
              })
              .style("left", (d3.event.pageX + 15) + "px")
              .style("top", (d3.event.pageY - 15) + "px")
              .style("display", "block")
              .style("opacity", 1);
          })
          .on("mouseout", function(d) {
            mapTooltip.style("opacity", 0)
              .style("display", "none");
          })
          .on("mousemove", function(d) {
            mapTooltip.style("left", (d3.event.pageX + 7) + "px")
              .style("top", (d3.event.pageY - 15) + "px");
          })
          .on("click", function(d) {
            selectCountry(d.properties.iso_a2);
          });
          var zoom = d3.zoom()
          .translateExtent([[-300,-200],[1300,900]])
          .scaleExtent([1, 8])
          .on('zoom', function() {
            svg.selectAll("path.land")
              .attr('transform', d3.event.transform);
            svg.selectAll("circle")
              .attr('transform', d3.event.transform)
              .attr("r", function(d){return calcDotSize(d.institutions.length)/d3.event.transform.k});
            vuedata.mapZoom = d3.event.transform.k;
            //renderDots();
          });

        var dotsSize = d3.scaleLinear().domain([1, 100]).range([2, 20]);
        var calcDotSize = function(n) {
          var scaleVal = dotsSize(n);
          if(scaleVal > 40) {
            scaleVal = 40;
          }
          return scaleVal;
        }

        var dots = svg.selectAll("circle")
        .data(cities);
        dots
        .enter()
        .append("circle")
        .attr("r", function(d){return calcDotSize (d.institutions.length)})
        .attr('cx', function(d){return projection([d.coordinates.lon, d.coordinates.lat])[0]})
        .attr('cy', function(d){return projection([d.coordinates.lon, d.coordinates.lat])[1]})
        //.attr('fill', function(d){return '#2a7aae'})
        //.attr('stroke', function(d){return '#0a4a74'})
        .attr('fill', function(d){return '#078298'})
        .attr('stroke', function(d){return '#076278'})
        .style("opacity", 0.7)
        //Mouse events
        .on("mouseover", function(d) {
          mapTooltip
            .html(function(a) {
              var tooltipHtml = "<div class='tooltip-title'>" + d.city + "</div>";
              tooltipHtml += "<div class='tooltip-info'>EU Institutions: " + d.institutions.length + "</div>";
              return tooltipHtml;
            })
            .style("left", (d3.event.pageX + 15) + "px")
            .style("top", (d3.event.pageY - 15) + "px")
            .style("display", "block")
            .style("opacity", 1);
        })
        .on("mouseout", function(d) {
          mapTooltip.style("opacity", 0)
            .style("display", "none");
        })
        .on("mousemove", function(d) {
          mapTooltip.style("left", (d3.event.pageX + 7) + "px")
            .style("top", (d3.event.pageY - 15) + "px");
        })
        .on("click", function(d) {
          selectCity(d);
        })
        .transition()
        .duration(1000);
        
        svg.call(zoom);

        //CHART 2
        var createCtypeChart = function(){
          var chart = charts.ctype.chart;
          var dimension = ndx.dimension(function (d) {
            return d.contractType; 
          });
          var group = dimension.group().reduceSum(function (d) { return 1; });
          var sizes = calcPieSize(charts.ctype.divId);
          chart
            .width(sizes.width)
            .height(sizes.height)
            .cy(sizes.cy)
            .innerRadius(sizes.innerRadius)
            .radius(sizes.radius)
            .legend(dc.legend().x(0).y(sizes.legendY).gap(10))
            .dimension(dimension)
            .group(group)
            .colorCalculator(function(d, i) {
              return vuedata.colors.contractType[d.key];
            });
            /*
            .colorCalculator(function(d, i) {
              return vuedata.colors.ecPolicy[d.key];
            });
            */
          //chart.filter = function() {};
          chart.render();
          chart.on('filtered', function(c) {
            updateMap(); 
          });
        }

        //CHART 3
        var createSectorChart = function() {
          var chart = charts.sector.chart;
          var dimension = ndx.dimension(function (d) {
              return d.sectorsNames;
          }, true);
          var group = dimension.group().reduceSum(function (d) {
              return 1;
          });
          var filteredGroup = (function(source_group) {
            return {
              all: function() {
                return source_group.top(15).filter(function(d) {
                  return (d.value != 0);
                });
              }
            };
          })(group);
          var width = recalcWidth(charts.sector.divId);
          var charsLength = recalcCharsLength(width);
          chart
            .width(width)
            .height(410)
            .margins({top: 0, left: 0, right: 0, bottom: 20})
            .group(filteredGroup)
            .dimension(dimension)
            //.ordinalColors(vuedata.colors.countries)
            .label(function (d) {
              var thisKey = d.key;
                if(thisKey.length > charsLength){
                  return thisKey.substring(0,charsLength) + '...';
                }
                return thisKey;
            })
            .title(function (d) {
                return d.key + ': ' + d.value;
            })
            .colorCalculator(function(d, i) {
              return vuedata.colors.default;
            })
            .elasticX(true)
            .xAxis().ticks(4);
          chart.render();
          chart.on('filtered', function(c) {
            updateMap(); 
          });
        }

        //CHART 4
        var createFlagsNumChart = function(){
          var chart = charts.flagsNum.chart;
          var dimension = ndx.dimension(function (d) {
            return d.flagsRange;
          });
          var group = dimension.group().reduceSum(function (d) { return 1; });
          var sizes = calcPieSize(charts.flagsNum.divId);
          chart
            .width(sizes.width)
            .height(sizes.height)
            .cy(sizes.cy)
            .innerRadius(sizes.innerRadius)
            .radius(sizes.radius)
            .legend(dc.legend().x(0).y(sizes.legendY).gap(10).legendText(function(d){ return d.name + " (" + d.data + ")"; }))
            .dimension(dimension)
            .group(group)
            .title(function(d) {
              return d.key + " (" + d.value + ")";
            })
            .colorCalculator(function(d, i) {
              return vuedata.colors.flags[d.key];
            });
          chart.render();
          chart.on('filtered', function(c) {
            updateMap(); 
          });
        }

        //CHART 5
        var createFlagsTypeChart = function() {
          var chart = charts.flagsType.chart;
          var dimension = ndx.dimension(function (d) {
              return d.flagsNames;
          }, true);
          var group = dimension.group().reduceSum(function (d) {
              return 1;
          });
          var filteredGroup = (function(source_group) {
            return {
              all: function() {
                return source_group.top(15).filter(function(d) {
                  return (d.value != 0);
                });
              }
            };
          })(group);
          var width = recalcWidth(charts.flagsType.divId);
          var charsLength = recalcCharsLength(width);
          chart
            .width(width)
            .height(410)
            .margins({top: 0, left: 0, right: 0, bottom: 20})
            .group(filteredGroup)
            .dimension(dimension)
            //.ordinalColors(vuedata.colors.countries)
            .label(function (d) {
              var thisKey = d.key;
                if(thisKey.length > charsLength){
                  return thisKey.substring(0,charsLength) + '...';
                }
                return thisKey;
            })
            .title(function (d) {
                return d.key + ': ' + d.value;
            })
            .colorCalculator(function(d, i) {
              return vuedata.colors.default2;
            })
            .elasticX(true)
            .xAxis().ticks(4);
          chart.render();
          chart.on('filtered', function(c) {
            updateMap(); 
          });
        }
      
        //TABLE
        var createTable = function() {
          var count=0;
          charts.meetingsTable.chart = $("#dc-data-table").dataTable({
            "columnDefs": [
              {
                "searchable": false,
                "orderable": false,
                "targets": 0,   
                data: function ( row, type, val, meta ) {
                  return count;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 1,
                "defaultContent":"N/A",
                "data": function(d) {
                  return d.cb_name;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 2,
                "defaultContent":"N/A",
                "data": function(d) {
                  return d.CY;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 3,
                "defaultContent":"N/A",
                "data": function(d) {
                  return d.documentType + " - " + d.contractType;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 4,
                "defaultContent":"N/A",
                "type":"date-eu",
                "data": function(d) {
                  return d.title_en;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 5,
                "defaultContent":"N/A",
                "data": function(d) {
                  return d.DS;
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 6,
                "defaultContent":"N/A",
                "data": function(d) {
                  if(d.TVH) {
                    return d.TVH;
                  }
                  return "-";
                }
              },
              {
                "searchable": false,
                "orderable": true,
                "targets": 7,
                "defaultContent":"N/A",
                "data": function(d) {
                  return d.flags.length;
                }
              }
            ],
            "iDisplayLength" : 25,
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": false,
            "order": [[ 1, "desc" ]],
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": false,
            "bDeferRender": true,
            "aaData": searchDimension.top(Infinity),
            "bDestroy": true,
          });
          var datatable = charts.meetingsTable.chart;
          datatable.on( 'draw.dt', function () {
            var PageInfo = $('#dc-data-table').DataTable().page.info();
              datatable.DataTable().column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              });
            });
            datatable.DataTable().draw();

          $('#dc-data-table tbody').on('click', 'tr', function () {
            var data = datatable.DataTable().row( this ).data();
            json('./data/tenders_data/'+data.ND+'.json', (err, selectedTender) => {
              if(!selectedTender.FORM_SECTION_EN || selectedTender.FORM_SECTION_EN == null) {
                selectedTender.FORM_SECTION_EN = selectedTender.FORM_SECTION_O;
              }
              data.fullData = selectedTender;
              vuedata.selEl = data;
              console.log("uid: " + data.ND);
              $('#detailsModal').modal();
            });
          });
        }
        //REFRESH TABLE
        function RefreshTable() {
          dc.events.trigger(function () {
            var alldata = searchDimension.top(Infinity);
            charts.meetingsTable.chart.fnClearTable();
            charts.meetingsTable.chart.fnAddData(alldata);
            charts.meetingsTable.chart.fnDraw();
          });
        }

        //SEARCH INPUT FUNCTIONALITY
        var typingTimer;
        var doneTypingInterval = 1000;
        var $input = $("#search-input");
        $input.on('keyup', function () {
          clearTimeout(typingTimer);
          typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });
        $input.on('keydown', function () {
          clearTimeout(typingTimer);
        });
        function doneTyping () {
          var s = $input.val().toLowerCase();
          searchDimension.filter(function(d) { 
            return d.indexOf(s) !== -1;
          });
          throttle();
          var throttleTimer;
          function throttle() {
            window.clearTimeout(throttleTimer);
            throttleTimer = window.setTimeout(function() {
                dc.redrawAll();
                updateMap(); 
            }, 250);
          }
        }

        //Reset charts
        var resetGraphs = function() {
          for (var c in charts) {
            if(charts[c].type !== 'table' && charts[c].chart.hasFilter()){
              charts[c].chart.filterAll();
            }
          }
          searchDimension.filter(null);
          $('#search-input').val('');
          dc.redrawAll();
        }
        $('.reset-btn').click(function(){
          vuedata.selectedCountry = "";
          vuedata.selectedCity = {};
          resetGraphs();
          updateMap();
          $('.institution-list-el').removeClass('selected');
          vuedata.infoboxTitle = "Select";
        })
        
        //Render charts
        createCtypeChart();
        createSectorChart();
        createFlagsNumChart();
        createFlagsTypeChart();
        createTable();

        $('.dataTables_wrapper').append($('.dataTables_length'));

        //Hide loader
        vuedata.loader = false;

        //COUNTERS
        //Main counter
        var all = ndx.groupAll();
        var counter = dc.dataCount('.dc-data-count')
          .dimension(ndx)
          .group(all);
        counter.render();
        //Update datatables
        counter.on("renderlet.resetall", function(c) {
          RefreshTable();
        });

        //Window resize function
        window.onresize = function(event) {
          resizeGraphs();
        };
      });
    });
  });
});
