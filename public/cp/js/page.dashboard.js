/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/page.dashboard.js":
/*!**********************************!*\
  !*** ./src/js/page.dashboard.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function () {
  'use strict';

  $('[data-toggle="tab"]').on('hide.bs.tab', function (e) {
    $(e.target).removeClass('active');
  });
  Charts.init();

  var EarningsTraffic = function EarningsTraffic(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      elements: {
        line: {
          fill: 'start',
          backgroundColor: settings.charts.colors.area
        }
      }
    }, options);
    var data = {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      datasets: [{
        label: "Traffic",
        data: [10, 2, 5, 15, 10, 12, 15, 25, 22, 30, 25, 40]
      }]
    };
    Charts.create(id, type, options, data);
  };

  var Transactions = function Transactions(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      scales: {
        yAxes: [{
          ticks: {
            maxTicksLimit: 5,
            callback: function callback(a) {
              return "$" + a + "k";
            }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function label(a, e) {
            var t = e.datasets[a.datasetIndex].label || "",
                o = a.yLabel,
                r = "";
            return 1 < e.datasets.length && (r += '<span class="popover-body-label mr-auto">' + t + "</span>"), r += '<span class="popover-body-value">$' + o + "k</span>";
          }
        }
      }
    }, options);
    var data = {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      datasets: [{
        label: "Transactions",
        data: [8, 5, 9, 6, 45, 4, 35, 14, 55, 19, 25, 20]
      }]
    };
    Charts.create(id, type, options, data);
  };

  var Products = function Products(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    var data = arguments.length > 3 ? arguments[3] : undefined;
    options = Chart.helpers.merge({
      elements: {
        line: {
          fill: 'start',
          backgroundColor: settings.charts.colors.area,
          tension: 0,
          borderWidth: 1
        },
        point: {
          pointStyle: 'circle',
          radius: 5,
          hoverRadius: 5,
          backgroundColor: settings.colors.white,
          borderColor: settings.colors.primary[700],
          borderWidth: 2
        }
      },
      scales: {
        yAxes: [{
          display: false
        }],
        xAxes: [{
          display: false
        }]
      }
    }, options);
    data = data || {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [{
        label: "Earnings",
        data: [400, 200, 450, 460, 220, 380, 800]
      }]
    };
    Charts.create(id, type, options, data);
  };

  var Courses = function Courses(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      elements: {
        line: {
          borderColor: settings.colors.success[700],
          backgroundColor: settings.hexToRGB(settings.colors.success[100], 0.5)
        },
        point: {
          borderColor: settings.colors.success[700]
        }
      }
    }, options);
    Products(id, type, options);
  };

  var LocationDoughnut = function LocationDoughnut(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      tooltips: {
        callbacks: {
          title: function title(a, e) {
            return e.labels[a[0].index];
          },
          label: function label(a, e) {
            var t = "";
            return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
          }
        }
      }
    }, options);
    var data = {
      labels: ["United States", "United Kingdom", "Germany", "India"],
      datasets: [{
        data: [25, 25, 15, 35],
        backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300]],
        hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
      }]
    };
    Charts.create(id, type, options, data);
  };

  var Billing = function Billing(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      tooltips: {
        callbacks: {
          title: function title(a, e) {
            return e.labels[a[0].index];
          },
          label: function label(a, e) {
            var t = "";
            return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
          }
        }
      }
    }, options);
    var data = {
      labels: ["Current Value", null],
      datasets: [{
        data: [75, 25],
        backgroundColor: [settings.colors.primary[500], settings.colors.gray[50]],
        hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
      }]
    };
    Charts.create(id, type, options, data);
  };

  var Gender = function Gender(id) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'roundedBar';
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options = Chart.helpers.merge({
      barRoundness: 1.2,
      scales: {
        xAxes: [{
          maxBarThickness: 15
        }]
      }
    }, options);
    var data = {
      labels: ["10-17", "18-30", "35-45", "46-60", "61-79", "80+"],
      datasets: [{
        label: "Female",
        data: [25, 20, 30, 22, 17, 10]
      }, {
        label: "Male",
        data: [15, 10, 20, 12, 7, 2],
        backgroundColor: settings.colors.primary[100]
      }]
    };
    Charts.create(id, type, options, data);
  }; ///////////////////
  // Create Charts //
  ///////////////////


  EarningsTraffic('#earningsTrafficChart');
  Transactions('#transactionsChart');
  LocationDoughnut('#locationDoughnutChart');
  Billing('#billingChart');
  Products('#productsChart');
  Courses('#coursesChart');
  Gender('#genderChart');
})();

/***/ }),

/***/ 8:
/*!****************************************!*\
  !*** multi ./src/js/page.dashboard.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/demi/Documents/GitHub/admin-lema/src/js/page.dashboard.js */"./src/js/page.dashboard.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3BhZ2UuZGFzaGJvYXJkLmpzIl0sIm5hbWVzIjpbIiQiLCJlIiwiQ2hhcnRzIiwiRWFybmluZ3NUcmFmZmljIiwidHlwZSIsIm9wdGlvbnMiLCJlbGVtZW50cyIsImxpbmUiLCJmaWxsIiwiYmFja2dyb3VuZENvbG9yIiwic2V0dGluZ3MiLCJhcmVhIiwiZGF0YSIsImxhYmVscyIsImRhdGFzZXRzIiwibGFiZWwiLCJUcmFuc2FjdGlvbnMiLCJzY2FsZXMiLCJ5QXhlcyIsInRpY2tzIiwibWF4VGlja3NMaW1pdCIsImNhbGxiYWNrIiwidG9vbHRpcHMiLCJjYWxsYmFja3MiLCJ0IiwiYSIsIm8iLCJyIiwiUHJvZHVjdHMiLCJ0ZW5zaW9uIiwiYm9yZGVyV2lkdGgiLCJwb2ludCIsInBvaW50U3R5bGUiLCJyYWRpdXMiLCJob3ZlclJhZGl1cyIsImJvcmRlckNvbG9yIiwiZGlzcGxheSIsInhBeGVzIiwiQ291cnNlcyIsIkxvY2F0aW9uRG91Z2hudXQiLCJ0aXRsZSIsImhvdmVyQm9yZGVyQ29sb3IiLCJ3aGl0ZSIsIkJpbGxpbmciLCJHZW5kZXIiLCJiYXJSb3VuZG5lc3MiLCJtYXhCYXJUaGlja25lc3MiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGtEQUEwQyxnQ0FBZ0M7QUFDMUU7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnRUFBd0Qsa0JBQWtCO0FBQzFFO0FBQ0EseURBQWlELGNBQWM7QUFDL0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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REQ7QUFGb0IsS0FBcEIsRUFBVnpDLE9BQVUsQ0FBVkE7QUFTQSxRQUFJTyxJQUFJLEdBQUc7QUFDVEMsWUFBTSxFQUFFLDhDQURDLEtBQ0QsQ0FEQztBQUVUQyxjQUFRLEVBQUUsQ0FBQztBQUNUQyxhQUFLLEVBREk7QUFFVEgsWUFBSSxFQUFFO0FBRkcsT0FBRCxFQUdQO0FBQ0RHLGFBQUssRUFESjtBQUVESCxZQUFJLEVBQUUsb0JBRkwsQ0FFSyxDQUZMO0FBR0RILHVCQUFlLEVBQUVDLFFBQVEsQ0FBUkE7QUFIaEIsT0FITztBQUZELEtBQVg7QUFZQVIsVUFBTSxDQUFOQTtBQXBNTyxHQThLVCxDQTlLUyxDQXVNVDtBQUNBO0FBQ0E7OztBQUVBQyxpQkFBZSxDQUFmQSx1QkFBZSxDQUFmQTtBQUNBYSxjQUFZLENBQVpBLG9CQUFZLENBQVpBO0FBQ0F1QixrQkFBZ0IsQ0FBaEJBLHdCQUFnQixDQUFoQkE7QUFDQUksU0FBTyxDQUFQQSxlQUFPLENBQVBBO0FBQ0FmLFVBQVEsQ0FBUkEsZ0JBQVEsQ0FBUkE7QUFDQVUsU0FBTyxDQUFQQSxlQUFPLENBQVBBO0FBQ0FNLFFBQU0sQ0FBTkEsY0FBTSxDQUFOQTtBQWpORixLIiwiZmlsZSI6Ii9kaXN0L2Fzc2V0cy9qcy9wYWdlLmRhc2hib2FyZC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL1wiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gOCk7XG4iLCIoZnVuY3Rpb24oKXtcbiAgJ3VzZSBzdHJpY3QnO1xuICAgIFxuICAkKCdbZGF0YS10b2dnbGU9XCJ0YWJcIl0nKS5vbignaGlkZS5icy50YWInLCBmdW5jdGlvbiAoZSkge1xuICAgICQoZS50YXJnZXQpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKVxuICB9KVxuXG4gIENoYXJ0cy5pbml0KClcbiAgXG4gIHZhciBFYXJuaW5nc1RyYWZmaWMgPSBmdW5jdGlvbihpZCwgdHlwZSA9ICdsaW5lJywgb3B0aW9ucyA9IHt9KSB7XG4gICAgb3B0aW9ucyA9IENoYXJ0LmhlbHBlcnMubWVyZ2Uoe1xuICAgICAgZWxlbWVudHM6IHtcbiAgICAgICAgbGluZToge1xuICAgICAgICAgIGZpbGw6ICdzdGFydCcsXG4gICAgICAgICAgYmFja2dyb3VuZENvbG9yOiBzZXR0aW5ncy5jaGFydHMuY29sb3JzLmFyZWFcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sIG9wdGlvbnMpXG5cbiAgICB2YXIgZGF0YSA9IHtcbiAgICAgIGxhYmVsczogW1wiSmFuXCIsIFwiRmViXCIsIFwiTWFyXCIsIFwiQXByXCIsIFwiTWF5XCIsIFwiSnVuXCIsIFwiSnVsXCIsIFwiQXVnXCIsIFwiU2VwXCIsIFwiT2N0XCIsIFwiTm92XCIsIFwiRGVjXCJdLFxuICAgICAgZGF0YXNldHM6IFt7XG4gICAgICAgIGxhYmVsOiBcIlRyYWZmaWNcIixcbiAgICAgICAgZGF0YTogWzEwLCAyLCA1LCAxNSwgMTAsIDEyLCAxNSwgMjUsIDIyLCAzMCwgMjUsIDQwXVxuICAgICAgfV1cbiAgICB9XG5cbiAgICBDaGFydHMuY3JlYXRlKGlkLCB0eXBlLCBvcHRpb25zLCBkYXRhKVxuICB9XG5cbiAgdmFyIFRyYW5zYWN0aW9ucyA9IGZ1bmN0aW9uKGlkLCB0eXBlID0gJ2xpbmUnLCBvcHRpb25zID0ge30pIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICBzY2FsZXM6IHtcbiAgICAgICAgeUF4ZXM6IFt7XG4gICAgICAgICAgdGlja3M6IHtcbiAgICAgICAgICAgIG1heFRpY2tzTGltaXQ6IDUsXG4gICAgICAgICAgICBjYWxsYmFjazogZnVuY3Rpb24oYSkge1xuICAgICAgICAgICAgICByZXR1cm4gXCIkXCIgKyBhICsgXCJrXCJcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH1dXG4gICAgICB9LFxuICAgICAgdG9vbHRpcHM6IHtcbiAgICAgICAgY2FsbGJhY2tzOiB7XG4gICAgICAgICAgbGFiZWw6IGZ1bmN0aW9uKGEsIGUpIHtcbiAgICAgICAgICAgIHZhciB0ID0gZS5kYXRhc2V0c1thLmRhdGFzZXRJbmRleF0ubGFiZWwgfHwgXCJcIixcbiAgICAgICAgICAgICAgICBvID0gYS55TGFiZWwsXG4gICAgICAgICAgICAgICAgciA9IFwiXCI7XG4gICAgICAgICAgICByZXR1cm4gMSA8IGUuZGF0YXNldHMubGVuZ3RoICYmIChyICs9ICc8c3BhbiBjbGFzcz1cInBvcG92ZXItYm9keS1sYWJlbCBtci1hdXRvXCI+JyArIHQgKyBcIjwvc3Bhbj5cIiksIHIgKz0gJzxzcGFuIGNsYXNzPVwicG9wb3Zlci1ib2R5LXZhbHVlXCI+JCcgKyBvICsgXCJrPC9zcGFuPlwiXG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgfSwgb3B0aW9ucylcblxuICAgIHZhciBkYXRhID0ge1xuICAgICAgbGFiZWxzOiBbXCJKYW5cIiwgXCJGZWJcIiwgXCJNYXJcIiwgXCJBcHJcIiwgXCJNYXlcIiwgXCJKdW5cIiwgXCJKdWxcIiwgXCJBdWdcIiwgXCJTZXBcIiwgXCJPY3RcIiwgXCJOb3ZcIiwgXCJEZWNcIl0sXG4gICAgICBkYXRhc2V0czogW3tcbiAgICAgICAgbGFiZWw6IFwiVHJhbnNhY3Rpb25zXCIsXG4gICAgICAgIGRhdGE6IFs4LCA1LCA5LCA2LCA0NSwgNCwgMzUsIDE0LCA1NSwgMTksIDI1LCAyMF1cbiAgICAgIH1dXG4gICAgfVxuXG4gICAgQ2hhcnRzLmNyZWF0ZShpZCwgdHlwZSwgb3B0aW9ucywgZGF0YSlcbiAgfVxuXG4gIHZhciBQcm9kdWN0cyA9IGZ1bmN0aW9uKGlkLCB0eXBlID0gJ2xpbmUnLCBvcHRpb25zID0ge30sIGRhdGEpIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICBlbGVtZW50czoge1xuICAgICAgICBsaW5lOiB7XG4gICAgICAgICAgZmlsbDogJ3N0YXJ0JyxcbiAgICAgICAgICBiYWNrZ3JvdW5kQ29sb3I6IHNldHRpbmdzLmNoYXJ0cy5jb2xvcnMuYXJlYSxcbiAgICAgICAgICB0ZW5zaW9uOiAwLFxuICAgICAgICAgIGJvcmRlcldpZHRoOiAxXG4gICAgICAgIH0sXG4gICAgICAgIHBvaW50OiB7XG4gICAgICAgICAgcG9pbnRTdHlsZTogJ2NpcmNsZScsXG4gICAgICAgICAgcmFkaXVzOiA1LFxuICAgICAgICAgIGhvdmVyUmFkaXVzOiA1LFxuICAgICAgICAgIGJhY2tncm91bmRDb2xvcjogc2V0dGluZ3MuY29sb3JzLndoaXRlLFxuICAgICAgICAgIGJvcmRlckNvbG9yOiBzZXR0aW5ncy5jb2xvcnMucHJpbWFyeVs3MDBdLFxuICAgICAgICAgIGJvcmRlcldpZHRoOiAyXG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBzY2FsZXM6IHtcbiAgICAgICAgeUF4ZXM6IFt7XG4gICAgICAgICAgZGlzcGxheTogZmFsc2VcbiAgICAgICAgfV0sXG4gICAgICAgIHhBeGVzOiBbe1xuICAgICAgICAgIGRpc3BsYXk6IGZhbHNlXG4gICAgICAgIH1dXG4gICAgICB9XG4gICAgfSwgb3B0aW9ucylcblxuICAgIGRhdGEgPSBkYXRhIHx8IHtcbiAgICAgIGxhYmVsczogW1wiTW9uXCIsIFwiVHVlXCIsIFwiV2VkXCIsIFwiVGh1XCIsIFwiRnJpXCIsIFwiU2F0XCIsIFwiU3VuXCJdLFxuICAgICAgZGF0YXNldHM6IFt7XG4gICAgICAgIGxhYmVsOiBcIkVhcm5pbmdzXCIsXG4gICAgICAgIGRhdGE6IFs0MDAsIDIwMCwgNDUwLCA0NjAsIDIyMCwgMzgwLCA4MDBdXG4gICAgICB9XVxuICAgIH1cblxuICAgIENoYXJ0cy5jcmVhdGUoaWQsIHR5cGUsIG9wdGlvbnMsIGRhdGEpXG4gIH1cblxuICB2YXIgQ291cnNlcyA9IGZ1bmN0aW9uKGlkLCB0eXBlID0gJ2xpbmUnLCBvcHRpb25zID0ge30pIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICBlbGVtZW50czoge1xuICAgICAgICBsaW5lOiB7XG4gICAgICAgICAgYm9yZGVyQ29sb3I6IHNldHRpbmdzLmNvbG9ycy5zdWNjZXNzWzcwMF0sXG4gICAgICAgICAgYmFja2dyb3VuZENvbG9yOiBzZXR0aW5ncy5oZXhUb1JHQihzZXR0aW5ncy5jb2xvcnMuc3VjY2Vzc1sxMDBdLCAwLjUpXG4gICAgICAgIH0sXG4gICAgICAgIHBvaW50OiB7XG4gICAgICAgICAgYm9yZGVyQ29sb3I6IHNldHRpbmdzLmNvbG9ycy5zdWNjZXNzWzcwMF1cbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sIG9wdGlvbnMpXG5cbiAgICBQcm9kdWN0cyhpZCwgdHlwZSwgb3B0aW9ucylcbiAgfVxuXG4gIHZhciBMb2NhdGlvbkRvdWdobnV0ID0gZnVuY3Rpb24oaWQsIHR5cGUgPSAnZG91Z2hudXQnLCBvcHRpb25zID0ge30pIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICB0b29sdGlwczoge1xuICAgICAgICBjYWxsYmFja3M6IHtcbiAgICAgICAgICB0aXRsZTogZnVuY3Rpb24oYSwgZSkge1xuICAgICAgICAgICAgcmV0dXJuIGUubGFiZWxzW2FbMF0uaW5kZXhdXG4gICAgICAgICAgfSxcbiAgICAgICAgICBsYWJlbDogZnVuY3Rpb24oYSwgZSkge1xuICAgICAgICAgICAgdmFyIHQgPSBcIlwiO1xuICAgICAgICAgICAgcmV0dXJuIHQgKz0gJzxzcGFuIGNsYXNzPVwicG9wb3Zlci1ib2R5LXZhbHVlXCI+JyArIGUuZGF0YXNldHNbMF0uZGF0YVthLmluZGV4XSArIFwiJTwvc3Bhbj5cIlxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sIG9wdGlvbnMpXG5cbiAgICB2YXIgZGF0YSA9IHtcbiAgICAgIGxhYmVsczogW1wiVW5pdGVkIFN0YXRlc1wiLCBcIlVuaXRlZCBLaW5nZG9tXCIsIFwiR2VybWFueVwiLCBcIkluZGlhXCJdLFxuICAgICAgZGF0YXNldHM6IFt7XG4gICAgICAgIGRhdGE6IFsyNSwgMjUsIDE1LCAzNV0sXG4gICAgICAgIGJhY2tncm91bmRDb2xvcjogW3NldHRpbmdzLmNvbG9ycy5zdWNjZXNzWzQwMF0sIHNldHRpbmdzLmNvbG9ycy5kYW5nZXJbNDAwXSwgc2V0dGluZ3MuY29sb3JzLnByaW1hcnlbNTAwXSwgc2V0dGluZ3MuY29sb3JzLmdyYXlbMzAwXV0sXG4gICAgICAgIGhvdmVyQm9yZGVyQ29sb3I6IFwiZGFya1wiID09IHNldHRpbmdzLmNoYXJ0cy5jb2xvclNjaGVtZSA/IHNldHRpbmdzLmNvbG9ycy5ncmF5WzgwMF0gOiBzZXR0aW5ncy5jb2xvcnMud2hpdGVcbiAgICAgIH1dXG4gICAgfVxuXG4gICAgQ2hhcnRzLmNyZWF0ZShpZCwgdHlwZSwgb3B0aW9ucywgZGF0YSlcbiAgfVxuXG4gIHZhciBCaWxsaW5nID0gZnVuY3Rpb24oaWQsIHR5cGUgPSAnZG91Z2hudXQnLCBvcHRpb25zID0ge30pIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICB0b29sdGlwczoge1xuICAgICAgICBjYWxsYmFja3M6IHtcbiAgICAgICAgICB0aXRsZTogZnVuY3Rpb24oYSwgZSkge1xuICAgICAgICAgICAgcmV0dXJuIGUubGFiZWxzW2FbMF0uaW5kZXhdXG4gICAgICAgICAgfSxcbiAgICAgICAgICBsYWJlbDogZnVuY3Rpb24oYSwgZSkge1xuICAgICAgICAgICAgdmFyIHQgPSBcIlwiO1xuICAgICAgICAgICAgcmV0dXJuIHQgKz0gJzxzcGFuIGNsYXNzPVwicG9wb3Zlci1ib2R5LXZhbHVlXCI+JyArIGUuZGF0YXNldHNbMF0uZGF0YVthLmluZGV4XSArIFwiJTwvc3Bhbj5cIlxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sIG9wdGlvbnMpXG5cbiAgICB2YXIgZGF0YSA9IHtcbiAgICAgIGxhYmVsczogW1wiQ3VycmVudCBWYWx1ZVwiLCBudWxsXSxcbiAgICAgIGRhdGFzZXRzOiBbe1xuICAgICAgICBkYXRhOiBbNzUsIDI1XSxcbiAgICAgICAgYmFja2dyb3VuZENvbG9yOiBbc2V0dGluZ3MuY29sb3JzLnByaW1hcnlbNTAwXSwgc2V0dGluZ3MuY29sb3JzLmdyYXlbNTBdXSxcbiAgICAgICAgaG92ZXJCb3JkZXJDb2xvcjogXCJkYXJrXCIgPT0gc2V0dGluZ3MuY2hhcnRzLmNvbG9yU2NoZW1lID8gc2V0dGluZ3MuY29sb3JzLmdyYXlbODAwXSA6IHNldHRpbmdzLmNvbG9ycy53aGl0ZVxuICAgICAgfV1cbiAgICB9XG5cbiAgICBDaGFydHMuY3JlYXRlKGlkLCB0eXBlLCBvcHRpb25zLCBkYXRhKVxuICB9XG5cbiAgdmFyIEdlbmRlciA9IGZ1bmN0aW9uKGlkLCB0eXBlID0gJ3JvdW5kZWRCYXInLCBvcHRpb25zID0ge30pIHtcbiAgICBvcHRpb25zID0gQ2hhcnQuaGVscGVycy5tZXJnZSh7XG4gICAgICBiYXJSb3VuZG5lc3M6IDEuMixcbiAgICAgIHNjYWxlczoge1xuICAgICAgICB4QXhlczogW3tcbiAgICAgICAgICBtYXhCYXJUaGlja25lc3M6IDE1XG4gICAgICAgIH1dXG4gICAgICB9XG4gICAgfSwgb3B0aW9ucylcblxuICAgIHZhciBkYXRhID0ge1xuICAgICAgbGFiZWxzOiBbXCIxMC0xN1wiLCBcIjE4LTMwXCIsIFwiMzUtNDVcIiwgXCI0Ni02MFwiLCBcIjYxLTc5XCIsIFwiODArXCJdLFxuICAgICAgZGF0YXNldHM6IFt7XG4gICAgICAgIGxhYmVsOiBcIkZlbWFsZVwiLFxuICAgICAgICBkYXRhOiBbMjUsIDIwLCAzMCwgMjIsIDE3LCAxMF1cbiAgICAgIH0sIHtcbiAgICAgICAgbGFiZWw6IFwiTWFsZVwiLFxuICAgICAgICBkYXRhOiBbMTUsIDEwLCAyMCwgMTIsIDcsIDJdLFxuICAgICAgICBiYWNrZ3JvdW5kQ29sb3I6IHNldHRpbmdzLmNvbG9ycy5wcmltYXJ5WzEwMF1cbiAgICAgIH1dXG4gICAgfVxuXG4gICAgQ2hhcnRzLmNyZWF0ZShpZCwgdHlwZSwgb3B0aW9ucywgZGF0YSlcbiAgfVxuXG4gIC8vLy8vLy8vLy8vLy8vLy8vLy9cbiAgLy8gQ3JlYXRlIENoYXJ0cyAvL1xuICAvLy8vLy8vLy8vLy8vLy8vLy8vXG4gIFxuICBFYXJuaW5nc1RyYWZmaWMoJyNlYXJuaW5nc1RyYWZmaWNDaGFydCcpXG4gIFRyYW5zYWN0aW9ucygnI3RyYW5zYWN0aW9uc0NoYXJ0JylcbiAgTG9jYXRpb25Eb3VnaG51dCgnI2xvY2F0aW9uRG91Z2hudXRDaGFydCcpXG4gIEJpbGxpbmcoJyNiaWxsaW5nQ2hhcnQnKVxuICBQcm9kdWN0cygnI3Byb2R1Y3RzQ2hhcnQnKVxuICBDb3Vyc2VzKCcjY291cnNlc0NoYXJ0JylcbiAgR2VuZGVyKCcjZ2VuZGVyQ2hhcnQnKVxuXG59KSgpIl0sInNvdXJjZVJvb3QiOiIifQ==