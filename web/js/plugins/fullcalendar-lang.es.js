!function (a) {
    "function" == typeof define && define.amd ? define(["jquery.dlmenu", "moment"], a) : "object" == typeof exports ? module.exports = a(require("jquery"), require("moment")) : a(jQuery, moment)
}(function (a, b) {
    !function () {
        "use strict";
        var a = "ene._feb._mar._abr._may._jun._jul._ago._sep._oct._nov._dic.".split("_"), c = "ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic".split("_"), d = (b.defineLocale || b.lang).call(b, "es", {
            months: "enero_febrero_marzo_abril_mayo_junio_julio_agosto_septiembre_octubre_noviembre_diciembre".split("_"),
            monthsShort: function (b, d) {
                return /-MMM-/.test(d) ? c[b.month()] : a[b.month()]
            },
            weekdays: "domingo_lunes_martes_mi\u00E9rcoles_jueves_viernes_s\u00E1bado".split("_"),
            weekdaysShort: "dom._lun._mar._mi\u00E9._jue._vie._s\u00E1b.".split("_"),
            weekdaysMin: "do_lu_ma_mi_ju_vi_s\u00E1".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D [de] MMMM [de] YYYY",
                LLL: "D [de] MMMM [de] YYYY H:mm",
                LLLL: "dddd, D [de] MMMM [de] YYYY H:mm"
            },
            calendar: {
                sameDay: function () {
                    return "[hoy a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                }, nextDay: function () {
                    return "[ma�ana a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                }, nextWeek: function () {
                    return "dddd [a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                }, lastDay: function () {
                    return "[ayer a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                }, lastWeek: function () {
                    return "[el] dddd [pasado a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                }, sameElse: "L"
            },
            relativeTime: {
                future: "en %s",
                past: "hace %s",
                s: "unos segundos",
                m: "un minuto",
                mm: "%d minutos",
                h: "una hora",
                hh: "%d horas",
                d: "un d\u00eda",
                dd: "%d d�as",
                M: "un mes",
                MM: "%d meses",
                y: "un a�o",
                yy: "%d a�os"
            },
            ordinalParse: /\d{1,2}�/,
            ordinal: "%d�",
            week: {dow: 1, doy: 4}
        });
        return d
    }(), a.fullCalendar.datepickerLang("es", "es", {
        closeText: "Cerrar",
        prevText: "&#x3C;Ant",
        nextText: "Sig&#x3E;",
        currentText: "Hoy",
        monthNames: ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"],
        monthNamesShort: ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"],
        dayNames: ["domingo", "lunes", "martes", "mi\u00E9rcoles", "jueves", "viernes", "s\u00E1bado"],
        dayNamesShort: ["dom", "lun", "mar", "mi\u00E9", "jue", "vie", "s\u00E1b"],
        dayNamesMin: ["D", "L", "M", "X", "J", "V", "S"],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: !1,
        showMonthAfterYear: !1,
        yearSuffix: ""
    }), a.fullCalendar.lang("es", {
        buttonText: {month: "Mes", week: "Semana", day: "D\u00eda", list: "Agenda"},
        allDayHtml: "Todo<br/>el d&iacute;a",
        eventLimitText: "m\u00E1s"
    })
});