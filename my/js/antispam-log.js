! function(e, t) {
    if ("function" == typeof define && define.amd) define(["moment", "jquery"], function(a, s) {
        return e.daterangepicker = t(a, s)
    });
    else if ("object" == typeof module && module.exports) {
        var a = "undefined" != typeof window ? window.jQuery : void 0;
        a || (a = require("jquery"), a.fn || (a.fn = {})), module.exports = t(require("moment"), a)
    } else e.daterangepicker = t(e.moment, e.jQuery)
}(this, function(e, t) {
    var a = function(a, s, i) {
        if (this.parentEl = "body", this.element = t(a), this.startDate = e().startOf("day"), this.endDate = e().endOf("day"), this.minDate = !1, this.maxDate = !1, this.dateLimit = !1, this.autoApply = !1, this.singleDatePicker = !1, this.showDropdowns = !1, this.showWeekNumbers = !1, this.showISOWeekNumbers = !1, this.showCustomRangeLabel = !0, this.timePicker = !1, this.timePicker24Hour = !1, this.timePickerIncrement = 1, this.timePickerSeconds = !1, this.linkedCalendars = !0, this.autoUpdateInput = !0, this.alwaysShowCalendars = !1, this.ranges = {}, this.opens = "right", this.element.hasClass("pull-right") && (this.opens = "left"), this.drops = "down", this.element.hasClass("dropup") && (this.drops = "up"), this.buttonClasses = "btn btn-sm", this.applyClass = "btn-success", this.cancelClass = "btn-default", this.locale = {
                direction: "ltr",
                format: e.localeData().longDateFormat("L"),
                separator: " - ",
                applyLabel: "Apply",
                cancelLabel: "Cancel",
                weekLabel: "W",
                customRangeLabel: "Custom Range",
                daysOfWeek: e.weekdaysMin(),
                monthNames: e.monthsShort(),
                firstDay: e.localeData().firstDayOfWeek()
            }, this.callback = function() {}, this.isShowing = !1, this.leftCalendar = {}, this.rightCalendar = {}, "object" == typeof s && null !== s || (s = {}), s = t.extend(this.element.data(), s), "string" == typeof s.template || s.template instanceof t || (s.template = '<div class="daterangepicker dropdown-menu"><div class="calendar left"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_start" value="" /><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="calendar right"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_end" value="" /><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="ranges"><div class="range_inputs"><button class="applyBtn" disabled="disabled" type="button"></button> <button class="cancelBtn" type="button"></button></div></div></div>'), this.parentEl = t(s.parentEl && t(s.parentEl).length ? s.parentEl : this.parentEl), this.container = t(s.template).appendTo(this.parentEl), "object" == typeof s.locale && ("string" == typeof s.locale.direction && (this.locale.direction = s.locale.direction), "string" == typeof s.locale.format && (this.locale.format = s.locale.format), "string" == typeof s.locale.separator && (this.locale.separator = s.locale.separator), "object" == typeof s.locale.daysOfWeek && (this.locale.daysOfWeek = s.locale.daysOfWeek.slice()), "object" == typeof s.locale.monthNames && (this.locale.monthNames = s.locale.monthNames.slice()), "number" == typeof s.locale.firstDay && (this.locale.firstDay = s.locale.firstDay), "string" == typeof s.locale.applyLabel && (this.locale.applyLabel = s.locale.applyLabel), "string" == typeof s.locale.cancelLabel && (this.locale.cancelLabel = s.locale.cancelLabel), "string" == typeof s.locale.weekLabel && (this.locale.weekLabel = s.locale.weekLabel), "string" == typeof s.locale.customRangeLabel && (this.locale.customRangeLabel = s.locale.customRangeLabel)), this.container.addClass(this.locale.direction), "string" == typeof s.startDate && (this.startDate = e(s.startDate, this.locale.format)), "string" == typeof s.endDate && (this.endDate = e(s.endDate, this.locale.format)), "string" == typeof s.minDate && (this.minDate = e(s.minDate, this.locale.format)), "string" == typeof s.maxDate && (this.maxDate = e(s.maxDate, this.locale.format)), "object" == typeof s.startDate && (this.startDate = e(s.startDate)), "object" == typeof s.endDate && (this.endDate = e(s.endDate)), "object" == typeof s.minDate && (this.minDate = e(s.minDate)), "object" == typeof s.maxDate && (this.maxDate = e(s.maxDate)), this.minDate && this.startDate.isBefore(this.minDate) && (this.startDate = this.minDate.clone()), this.maxDate && this.endDate.isAfter(this.maxDate) && (this.endDate = this.maxDate.clone()), "string" == typeof s.applyClass && (this.applyClass = s.applyClass), "string" == typeof s.cancelClass && (this.cancelClass = s.cancelClass), "object" == typeof s.dateLimit && (this.dateLimit = s.dateLimit), "string" == typeof s.opens && (this.opens = s.opens), "string" == typeof s.drops && (this.drops = s.drops), "boolean" == typeof s.showWeekNumbers && (this.showWeekNumbers = s.showWeekNumbers), "boolean" == typeof s.showISOWeekNumbers && (this.showISOWeekNumbers = s.showISOWeekNumbers), "string" == typeof s.buttonClasses && (this.buttonClasses = s.buttonClasses), "object" == typeof s.buttonClasses && (this.buttonClasses = s.buttonClasses.join(" ")), "boolean" == typeof s.showDropdowns && (this.showDropdowns = s.showDropdowns), "boolean" == typeof s.showCustomRangeLabel && (this.showCustomRangeLabel = s.showCustomRangeLabel), "boolean" == typeof s.singleDatePicker && (this.singleDatePicker = s.singleDatePicker, this.singleDatePicker && (this.endDate = this.startDate.clone())), "boolean" == typeof s.timePicker && (this.timePicker = s.timePicker), "boolean" == typeof s.timePickerSeconds && (this.timePickerSeconds = s.timePickerSeconds), "number" == typeof s.timePickerIncrement && (this.timePickerIncrement = s.timePickerIncrement), "boolean" == typeof s.timePicker24Hour && (this.timePicker24Hour = s.timePicker24Hour), "boolean" == typeof s.autoApply && (this.autoApply = s.autoApply), "boolean" == typeof s.autoUpdateInput && (this.autoUpdateInput = s.autoUpdateInput), "boolean" == typeof s.linkedCalendars && (this.linkedCalendars = s.linkedCalendars), "function" == typeof s.isInvalidDate && (this.isInvalidDate = s.isInvalidDate), "function" == typeof s.isCustomDate && (this.isCustomDate = s.isCustomDate), "boolean" == typeof s.alwaysShowCalendars && (this.alwaysShowCalendars = s.alwaysShowCalendars), 0 != this.locale.firstDay)
            for (var l = this.locale.firstDay; l > 0;) this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift()), l--;
        var n, d, o;
        if (void 0 === s.startDate && void 0 === s.endDate && t(this.element).is("input[type=text]")) {
            var r = t(this.element).val(),
                h = r.split(this.locale.separator);
            n = d = null, 2 == h.length ? (n = e(h[0], this.locale.format), d = e(h[1], this.locale.format)) : this.singleDatePicker && "" !== r && (n = e(r, this.locale.format), d = e(r, this.locale.format)), null !== n && null !== d && (this.setStartDate(n), this.setEndDate(d))
        }
        if ("object" == typeof s.ranges) {
            for (o in s.ranges) {
                n = "string" == typeof s.ranges[o][0] ? e(s.ranges[o][0], this.locale.format) : e(s.ranges[o][0]), d = "string" == typeof s.ranges[o][1] ? e(s.ranges[o][1], this.locale.format) : e(s.ranges[o][1]), this.minDate && n.isBefore(this.minDate) && (n = this.minDate.clone());
                var c = this.maxDate;
                if (this.dateLimit && c && n.clone().add(this.dateLimit).isAfter(c) && (c = n.clone().add(this.dateLimit)), c && d.isAfter(c) && (d = c.clone()), !(this.minDate && d.isBefore(this.minDate, this.timepicker ? "minute" : "day") || c && n.isAfter(c, this.timepicker ? "minute" : "day"))) {
                    var m = document.createElement("textarea");
                    m.innerHTML = o;
                    var f = m.value;
                    this.ranges[f] = [n, d]
                }
            }
            var p = "<ul>";
            for (o in this.ranges) p += '<li data-range-key="' + o + '">' + o + "</li>";
            this.showCustomRangeLabel && (p += '<li data-range-key="' + this.locale.customRangeLabel + '">' + this.locale.customRangeLabel + "</li>"), p += "</ul>", this.container.find(".ranges").prepend(p)
        }
        "function" == typeof i && (this.callback = i), this.timePicker || (this.startDate = this.startDate.startOf("day"), this.endDate = this.endDate.endOf("day"), this.container.find(".calendar-time").hide()), this.timePicker && this.autoApply && (this.autoApply = !1), this.autoApply && "object" != typeof s.ranges ? this.container.find(".ranges").hide() : this.autoApply && this.container.find(".applyBtn, .cancelBtn").addClass("hide"), this.singleDatePicker && (this.container.addClass("single"), this.container.find(".calendar.left").addClass("single"), this.container.find(".calendar.left").show(), this.container.find(".calendar.right").hide(), this.container.find(".daterangepicker_input input, .daterangepicker_input > i").hide(), this.timePicker ? this.container.find(".ranges ul").hide() : this.container.find(".ranges").hide()), (void 0 === s.ranges && !this.singleDatePicker || this.alwaysShowCalendars) && this.container.addClass("show-calendar"), this.container.addClass("opens" + this.opens), void 0 !== s.ranges && "right" == this.opens && this.container.find(".ranges").prependTo(this.container.find(".calendar.left").parent()), this.container.find(".applyBtn, .cancelBtn").addClass(this.buttonClasses), this.applyClass.length && this.container.find(".applyBtn").addClass(this.applyClass), this.cancelClass.length && this.container.find(".cancelBtn").addClass(this.cancelClass), this.container.find(".applyBtn").html(this.locale.applyLabel), this.container.find(".cancelBtn").html(this.locale.cancelLabel), this.container.find(".calendar").on("click.daterangepicker", ".prev", t.proxy(this.clickPrev, this)).on("click.daterangepicker", ".next", t.proxy(this.clickNext, this)).on("mousedown.daterangepicker", "td.available", t.proxy(this.clickDate, this)).on("mouseenter.daterangepicker", "td.available", t.proxy(this.hoverDate, this)).on("mouseleave.daterangepicker", "td.available", t.proxy(this.updateFormInputs, this)).on("change.daterangepicker", "select.yearselect", t.proxy(this.monthOrYearChanged, this)).on("change.daterangepicker", "select.monthselect", t.proxy(this.monthOrYearChanged, this)).on("change.daterangepicker", "select.hourselect,select.minuteselect,select.secondselect,select.ampmselect", t.proxy(this.timeChanged, this)).on("click.daterangepicker", ".daterangepicker_input input", t.proxy(this.showCalendars, this)).on("focus.daterangepicker", ".daterangepicker_input input", t.proxy(this.formInputsFocused, this)).on("blur.daterangepicker", ".daterangepicker_input input", t.proxy(this.formInputsBlurred, this)).on("change.daterangepicker", ".daterangepicker_input input", t.proxy(this.formInputsChanged, this)), this.container.find(".ranges").on("click.daterangepicker", "button.applyBtn", t.proxy(this.clickApply, this)).on("click.daterangepicker", "button.cancelBtn", t.proxy(this.clickCancel, this)).on("click.daterangepicker", "li", t.proxy(this.clickRange, this)).on("mouseenter.daterangepicker", "li", t.proxy(this.hoverRange, this)).on("mouseleave.daterangepicker", "li", t.proxy(this.updateFormInputs, this)), this.element.is("input") || this.element.is("button") ? this.element.on({
            "click.daterangepicker": t.proxy(this.show, this),
            "focus.daterangepicker": t.proxy(this.show, this),
            "keyup.daterangepicker": t.proxy(this.elementChanged, this),
            "keydown.daterangepicker": t.proxy(this.keydown, this)
        }) : this.element.on("click.daterangepicker", t.proxy(this.toggle, this)), this.element.is("input") && !this.singleDatePicker && this.autoUpdateInput ? (this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format)), this.element.trigger("change")) : this.element.is("input") && this.autoUpdateInput && (this.element.val(this.startDate.format(this.locale.format)), this.element.trigger("change"))
    };
    return a.prototype = {
        constructor: a,
        setStartDate: function(t) {
            "string" == typeof t && (this.startDate = e(t, this.locale.format)), "object" == typeof t && (this.startDate = e(t)), this.timePicker || (this.startDate = this.startDate.startOf("day")), this.timePicker && this.timePickerIncrement && this.startDate.minute(Math.round(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement), this.minDate && this.startDate.isBefore(this.minDate) && (this.startDate = this.minDate.clone(), this.timePicker && this.timePickerIncrement && this.startDate.minute(Math.round(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement)), this.maxDate && this.startDate.isAfter(this.maxDate) && (this.startDate = this.maxDate.clone(), this.timePicker && this.timePickerIncrement && this.startDate.minute(Math.floor(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement)), this.isShowing || this.updateElement(), this.updateMonthsInView()
        },
        setEndDate: function(t) {
            "string" == typeof t && (this.endDate = e(t, this.locale.format)), "object" == typeof t && (this.endDate = e(t)), this.timePicker || (this.endDate = this.endDate.endOf("day")), this.timePicker && this.timePickerIncrement && this.endDate.minute(Math.round(this.endDate.minute() / this.timePickerIncrement) * this.timePickerIncrement), this.endDate.isBefore(this.startDate) && (this.endDate = this.startDate.clone()), this.maxDate && this.endDate.isAfter(this.maxDate) && (this.endDate = this.maxDate.clone()), this.dateLimit && this.startDate.clone().add(this.dateLimit).isBefore(this.endDate) && (this.endDate = this.startDate.clone().add(this.dateLimit)), this.previousRightTime = this.endDate.clone(), this.isShowing || this.updateElement(), this.updateMonthsInView()
        },
        isInvalidDate: function() {
            return !1
        },
        isCustomDate: function() {
            return !1
        },
        updateView: function() {
            this.timePicker && (this.renderTimePicker("left"), this.renderTimePicker("right"), this.endDate ? this.container.find(".right .calendar-time select").removeAttr("disabled").removeClass("disabled") : this.container.find(".right .calendar-time select").attr("disabled", "disabled").addClass("disabled")), this.endDate ? (this.container.find('input[name="daterangepicker_end"]').removeClass("active"), this.container.find('input[name="daterangepicker_start"]').addClass("active")) : (this.container.find('input[name="daterangepicker_end"]').addClass("active"), this.container.find('input[name="daterangepicker_start"]').removeClass("active")), this.updateMonthsInView(), this.updateCalendars(), this.updateFormInputs()
        },
        updateMonthsInView: function() {
            if (this.endDate) {
                if (!this.singleDatePicker && this.leftCalendar.month && this.rightCalendar.month && (this.startDate.format("YYYY-MM") == this.leftCalendar.month.format("YYYY-MM") || this.startDate.format("YYYY-MM") == this.rightCalendar.month.format("YYYY-MM")) && (this.endDate.format("YYYY-MM") == this.leftCalendar.month.format("YYYY-MM") || this.endDate.format("YYYY-MM") == this.rightCalendar.month.format("YYYY-MM"))) return;
                this.leftCalendar.month = this.startDate.clone().date(2), this.linkedCalendars || this.endDate.month() == this.startDate.month() && this.endDate.year() == this.startDate.year() ? this.rightCalendar.month = this.startDate.clone().date(2).add(1, "month") : this.rightCalendar.month = this.endDate.clone().date(2)
            } else this.leftCalendar.month.format("YYYY-MM") != this.startDate.format("YYYY-MM") && this.rightCalendar.month.format("YYYY-MM") != this.startDate.format("YYYY-MM") && (this.leftCalendar.month = this.startDate.clone().date(2), this.rightCalendar.month = this.startDate.clone().date(2).add(1, "month"));
            this.maxDate && this.linkedCalendars && !this.singleDatePicker && this.rightCalendar.month > this.maxDate && (this.rightCalendar.month = this.maxDate.clone().date(2), this.leftCalendar.month = this.maxDate.clone().date(2).subtract(1, "month"))
        },
        updateCalendars: function() {
            if (this.timePicker) {
                var e, t, a;
                if (this.endDate) {
                    if (e = parseInt(this.container.find(".left .hourselect").val(), 10), t = parseInt(this.container.find(".left .minuteselect").val(), 10), a = this.timePickerSeconds ? parseInt(this.container.find(".left .secondselect").val(), 10) : 0, !this.timePicker24Hour) {
                        var s = this.container.find(".left .ampmselect").val();
                        "PM" === s && e < 12 && (e += 12), "AM" === s && 12 === e && (e = 0)
                    }
                } else if (e = parseInt(this.container.find(".right .hourselect").val(), 10), t = parseInt(this.container.find(".right .minuteselect").val(), 10), a = this.timePickerSeconds ? parseInt(this.container.find(".right .secondselect").val(), 10) : 0, !this.timePicker24Hour) {
                    var s = this.container.find(".right .ampmselect").val();
                    "PM" === s && e < 12 && (e += 12), "AM" === s && 12 === e && (e = 0)
                }
                this.leftCalendar.month.hour(e).minute(t).second(a), this.rightCalendar.month.hour(e).minute(t).second(a)
            }
            this.renderCalendar("left"), this.renderCalendar("right"), this.container.find(".ranges li").removeClass("active"), null != this.endDate && this.calculateChosenLabel()
        },
        renderCalendar: function(a) {
            var s = "left" == a ? this.leftCalendar : this.rightCalendar,
                i = s.month.month(),
                l = s.month.year(),
                n = s.month.hour(),
                d = s.month.minute(),
                o = s.month.second(),
                r = e([l, i]).daysInMonth(),
                h = e([l, i, 1]),
                c = e([l, i, r]),
                m = e(h).subtract(1, "month").month(),
                f = e(h).subtract(1, "month").year(),
                p = e([f, m]).daysInMonth(),
                u = h.day(),
                s = [];
            s.firstDay = h, s.lastDay = c;
            for (var g = 0; g < 6; g++) s[g] = [];
            var k = p - u + this.locale.firstDay + 1;
            k > p && (k -= 7), u == this.locale.firstDay && (k = p - 6);
            for (var v, b, C = e([f, m, k, 12, d, o]), g = 0, v = 0, b = 0; g < 42; g++, v++, C = e(C).add(24, "hour")) g > 0 && v % 7 == 0 && (v = 0, b++), s[b][v] = C.clone().hour(n).minute(d).second(o), C.hour(12), this.minDate && s[b][v].format("YYYY-MM-DD") == this.minDate.format("YYYY-MM-DD") && s[b][v].isBefore(this.minDate) && "left" == a && (s[b][v] = this.minDate.clone()), this.maxDate && s[b][v].format("YYYY-MM-DD") == this.maxDate.format("YYYY-MM-DD") && s[b][v].isAfter(this.maxDate) && "right" == a && (s[b][v] = this.maxDate.clone());
            "left" == a ? this.leftCalendar.calendar = s : this.rightCalendar.calendar = s;
            var D = "left" == a ? this.minDate : this.startDate,
                $ = this.maxDate,
                y = ("left" == a ? this.startDate : this.endDate, "ltr" == this.locale.direction ? {
                    left: "chevron-left",
                    right: "chevron-right"
                } : {
                    left: "chevron-right",
                    right: "chevron-left"
                }),
                x = '<table class="table-condensed">';
            x += "<thead>", x += "<tr>", (this.showWeekNumbers || this.showISOWeekNumbers) && (x += "<th></th>"), D && !D.isBefore(s.firstDay) || this.linkedCalendars && "left" != a ? x += "<th></th>" : x += '<th class="prev available"><i class="fa fa-' + y.left + " glyphicon glyphicon-" + y.left + '"></i></th>';
            var w = this.locale.monthNames[s[1][1].month()] + s[1][1].format(" YYYY");
            if (this.showDropdowns) {
                for (var Y = s[1][1].month(), P = s[1][1].year(), _ = $ && $.year() || P + 5, M = D && D.year() || P - 50, I = P == M, E = P == _, S = '<select class="monthselect">', A = 0; A < 12; A++)(!I || A >= D.month()) && (!E || A <= $.month()) ? S += "<option value='" + A + "'" + (A === Y ? " selected='selected'" : "") + ">" + this.locale.monthNames[A] + "</option>" : S += "<option value='" + A + "'" + (A === Y ? " selected='selected'" : "") + " disabled='disabled'>" + this.locale.monthNames[A] + "</option>";
                S += "</select>";
                for (var L = '<select class="yearselect">', O = M; O <= _; O++) L += '<option value="' + O + '"' + (O === P ? ' selected="selected"' : "") + ">" + O + "</option>";
                L += "</select>", w = S + L
            }
            if (x += '<th colspan="5" class="month">' + w + "</th>", $ && !$.isAfter(s.lastDay) || this.linkedCalendars && "right" != a && !this.singleDatePicker ? x += "<th></th>" : x += '<th class="next available"><i class="fa fa-' + y.right + " glyphicon glyphicon-" + y.right + '"></i></th>', x += "</tr>", x += "<tr>", (this.showWeekNumbers || this.showISOWeekNumbers) && (x += '<th class="week">' + this.locale.weekLabel + "</th>"), t.each(this.locale.daysOfWeek, function(e, t) {
                    x += "<th>" + t + "</th>"
                }), x += "</tr>", x += "</thead>", x += "<tbody>", null == this.endDate && this.dateLimit) {
                var B = this.startDate.clone().add(this.dateLimit).endOf("day");
                $ && !B.isBefore($) || ($ = B)
            }
            for (var b = 0; b < 6; b++) {
                x += "<tr>", this.showWeekNumbers ? x += '<td class="week">' + s[b][0].week() + "</td>" : this.showISOWeekNumbers && (x += '<td class="week">' + s[b][0].isoWeek() + "</td>");
                for (var v = 0; v < 7; v++) {
                    var N = [];
                    s[b][v].isSame(new Date, "day") && N.push("today"), s[b][v].isoWeekday() > 5 && N.push("weekend"), s[b][v].month() != s[1][1].month() && N.push("off"), this.minDate && s[b][v].isBefore(this.minDate, "day") && N.push("off", "disabled"), $ && s[b][v].isAfter($, "day") && N.push("off", "disabled"), this.isInvalidDate(s[b][v]) && N.push("off", "disabled"), s[b][v].format("YYYY-MM-DD") == this.startDate.format("YYYY-MM-DD") && N.push("active", "start-date"), null != this.endDate && s[b][v].format("YYYY-MM-DD") == this.endDate.format("YYYY-MM-DD") && N.push("active", "end-date"), null != this.endDate && s[b][v] > this.startDate && s[b][v] < this.endDate && N.push("in-range");
                    var R = this.isCustomDate(s[b][v]);
                    !1 !== R && ("string" == typeof R ? N.push(R) : Array.prototype.push.apply(N, R));
                    for (var T = "", W = !1, g = 0; g < N.length; g++) T += N[g] + " ", "disabled" == N[g] && (W = !0);
                    W || (T += "available"), x += '<td class="' + T.replace(/^\s+|\s+$/g, "") + '" data-title="r' + b + "c" + v + '">' + s[b][v].date() + "</td>"
                }
                x += "</tr>"
            }
            x += "</tbody>", x += "</table>", this.container.find(".calendar." + a + " .calendar-table").html(x)
        },
        renderTimePicker: function(e) {
            if ("right" != e || this.endDate) {
                var t, a, s, i = this.maxDate;
                if (!this.dateLimit || this.maxDate && !this.startDate.clone().add(this.dateLimit).isAfter(this.maxDate) || (i = this.startDate.clone().add(this.dateLimit)), "left" == e) a = this.startDate.clone(), s = this.minDate;
                else if ("right" == e) {
                    a = this.endDate.clone(), s = this.startDate;
                    var l = this.container.find(".calendar.right .calendar-time div");
                    if (!this.endDate && "" != l.html() && (a.hour(l.find(".hourselect option:selected").val() || a.hour()), a.minute(l.find(".minuteselect option:selected").val() || a.minute()), a.second(l.find(".secondselect option:selected").val() || a.second()), !this.timePicker24Hour)) {
                        var n = l.find(".ampmselect option:selected").val();
                        "PM" === n && a.hour() < 12 && a.hour(a.hour() + 12), "AM" === n && 12 === a.hour() && a.hour(0)
                    }
                    a.isBefore(this.startDate) && (a = this.startDate.clone()), i && a.isAfter(i) && (a = i.clone())
                }
                t = '<select class="hourselect">';
                for (var d = this.timePicker24Hour ? 0 : 1, o = this.timePicker24Hour ? 23 : 12, r = d; r <= o; r++) {
                    var h = r;
                    this.timePicker24Hour || (h = a.hour() >= 12 ? 12 == r ? 12 : r + 12 : 12 == r ? 0 : r);
                    var c = a.clone().hour(h),
                        m = !1;
                    s && c.minute(59).isBefore(s) && (m = !0), i && c.minute(0).isAfter(i) && (m = !0), h != a.hour() || m ? t += m ? '<option value="' + r + '" disabled="disabled" class="disabled">' + r + "</option>" : '<option value="' + r + '">' + r + "</option>" : t += '<option value="' + r + '" selected="selected">' + r + "</option>"
                }
                t += "</select> ", t += ': <select class="minuteselect">';
                for (var r = 0; r < 60; r += this.timePickerIncrement) {
                    var f = r < 10 ? "0" + r : r,
                        c = a.clone().minute(r),
                        m = !1;
                    s && c.second(59).isBefore(s) && (m = !0), i && c.second(0).isAfter(i) && (m = !0), a.minute() != r || m ? t += m ? '<option value="' + r + '" disabled="disabled" class="disabled">' + f + "</option>" : '<option value="' + r + '">' + f + "</option>" : t += '<option value="' + r + '" selected="selected">' + f + "</option>"
                }
                if (t += "</select> ", this.timePickerSeconds) {
                    t += ': <select class="secondselect">';
                    for (var r = 0; r < 60; r++) {
                        var f = r < 10 ? "0" + r : r,
                            c = a.clone().second(r),
                            m = !1;
                        s && c.isBefore(s) && (m = !0), i && c.isAfter(i) && (m = !0), a.second() != r || m ? t += m ? '<option value="' + r + '" disabled="disabled" class="disabled">' + f + "</option>" : '<option value="' + r + '">' + f + "</option>" : t += '<option value="' + r + '" selected="selected">' + f + "</option>"
                    }
                    t += "</select> "
                }
                if (!this.timePicker24Hour) {
                    t += '<select class="ampmselect">';
                    var p = "",
                        u = "";
                    s && a.clone().hour(12).minute(0).second(0).isBefore(s) && (p = ' disabled="disabled" class="disabled"'), i && a.clone().hour(0).minute(0).second(0).isAfter(i) && (u = ' disabled="disabled" class="disabled"'), a.hour() >= 12 ? t += '<option value="AM"' + p + '>AM</option><option value="PM" selected="selected"' + u + ">PM</option>" : t += '<option value="AM" selected="selected"' + p + '>AM</option><option value="PM"' + u + ">PM</option>", t += "</select>"
                }
                this.container.find(".calendar." + e + " .calendar-time div").html(t)
            }
        },
        updateFormInputs: function() {
            this.container.find("input[name=daterangepicker_start]").is(":focus") || this.container.find("input[name=daterangepicker_end]").is(":focus") || (this.container.find("input[name=daterangepicker_start]").val(this.startDate.format(this.locale.format)), this.endDate && this.container.find("input[name=daterangepicker_end]").val(this.endDate.format(this.locale.format)), this.singleDatePicker || this.endDate && (this.startDate.isBefore(this.endDate) || this.startDate.isSame(this.endDate)) ? this.container.find("button.applyBtn").removeAttr("disabled") : this.container.find("button.applyBtn").attr("disabled", "disabled"))
        },
        move: function() {
            var e, a = {
                    top: 0,
                    left: 0
                },
                s = t(window).width();
            this.parentEl.is("body") || (a = {
                top: this.parentEl.offset().top - this.parentEl.scrollTop(),
                left: this.parentEl.offset().left - this.parentEl.scrollLeft()
            }, s = this.parentEl[0].clientWidth + this.parentEl.offset().left), e = "up" == this.drops ? this.element.offset().top - this.container.outerHeight() - a.top : this.element.offset().top + this.element.outerHeight() - a.top, this.container["up" == this.drops ? "addClass" : "removeClass"]("dropup"), "left" == this.opens ? (this.container.css({
                top: e,
                right: s - this.element.offset().left - this.element.outerWidth(),
                left: "auto"
            }), this.container.offset().left < 0 && this.container.css({
                right: "auto",
                left: 9
            })) : "center" == this.opens ? (this.container.css({
                top: e,
                left: this.element.offset().left - a.left + this.element.outerWidth() / 2 - this.container.outerWidth() / 2,
                right: "auto"
            }), this.container.offset().left < 0 && this.container.css({
                right: "auto",
                left: 9
            })) : (this.container.css({
                top: e,
                left: this.element.offset().left - a.left,
                right: "auto"
            }), this.container.offset().left + this.container.outerWidth() > t(window).width() && this.container.css({
                left: "auto",
                right: 0
            }))
        },
        show: function(e) {
            this.isShowing || (this._outsideClickProxy = t.proxy(function(e) {
                this.outsideClick(e)
            }, this), t(document).on("mousedown.daterangepicker", this._outsideClickProxy).on("touchend.daterangepicker", this._outsideClickProxy).on("click.daterangepicker", "[data-toggle=dropdown]", this._outsideClickProxy).on("focusin.daterangepicker", this._outsideClickProxy), t(window).on("resize.daterangepicker", t.proxy(function(e) {
                this.move(e)
            }, this)), this.oldStartDate = this.startDate.clone(), this.oldEndDate = this.endDate.clone(), this.previousRightTime = this.endDate.clone(), this.updateView(), this.container.show(), this.move(), this.element.trigger("show.daterangepicker", this), this.isShowing = !0)
        },
        hide: function(e) {
            this.isShowing && (this.endDate || (this.startDate = this.oldStartDate.clone(), this.endDate = this.oldEndDate.clone()), this.startDate.isSame(this.oldStartDate) && this.endDate.isSame(this.oldEndDate) || this.callback(this.startDate, this.endDate, this.chosenLabel), this.updateElement(), t(document).off(".daterangepicker"), t(window).off(".daterangepicker"), this.container.hide(), this.element.trigger("hide.daterangepicker", this), this.isShowing = !1)
        },
        toggle: function(e) {
            this.isShowing ? this.hide() : this.show()
        },
        outsideClick: function(e) {
            var a = t(e.target);
            "focusin" == e.type || a.closest(this.element).length || a.closest(this.container).length || a.closest(".calendar-table").length || (this.hide(), this.element.trigger("outsideClick.daterangepicker", this))
        },
        showCalendars: function() {
            this.container.addClass("show-calendar"), this.move(), this.element.trigger("showCalendar.daterangepicker", this)
        },
        hideCalendars: function() {
            this.container.removeClass("show-calendar"), this.element.trigger("hideCalendar.daterangepicker", this)
        },
        hoverRange: function(e) {
            if (!this.container.find("input[name=daterangepicker_start]").is(":focus") && !this.container.find("input[name=daterangepicker_end]").is(":focus")) {
                var t = e.target.getAttribute("data-range-key");
                if (t == this.locale.customRangeLabel) this.updateView();
                else {
                    var a = this.ranges[t];
                    this.container.find("input[name=daterangepicker_start]").val(a[0].format(this.locale.format)), this.container.find("input[name=daterangepicker_end]").val(a[1].format(this.locale.format))
                }
            }
        },
        clickRange: function(e) {
            var t = e.target.getAttribute("data-range-key");
            if (this.chosenLabel = t, t == this.locale.customRangeLabel) this.showCalendars();
            else {
                var a = this.ranges[t];
                this.startDate = a[0], this.endDate = a[1], this.timePicker || (this.startDate.startOf("day"), this.endDate.endOf("day")), this.alwaysShowCalendars || this.hideCalendars(), this.clickApply()
            }
        },
        clickPrev: function(e) {
            t(e.target).parents(".calendar").hasClass("left") ? (this.leftCalendar.month.subtract(1, "month"), this.linkedCalendars && this.rightCalendar.month.subtract(1, "month")) : this.rightCalendar.month.subtract(1, "month"), this.updateCalendars()
        },
        clickNext: function(e) {
            t(e.target).parents(".calendar").hasClass("left") ? this.leftCalendar.month.add(1, "month") : (this.rightCalendar.month.add(1, "month"), this.linkedCalendars && this.leftCalendar.month.add(1, "month")), this.updateCalendars()
        },
        hoverDate: function(e) {
            if (t(e.target).hasClass("available")) {
                var a = t(e.target).attr("data-title"),
                    s = a.substr(1, 1),
                    i = a.substr(3, 1),
                    l = t(e.target).parents(".calendar"),
                    n = l.hasClass("left") ? this.leftCalendar.calendar[s][i] : this.rightCalendar.calendar[s][i];
                this.endDate && !this.container.find("input[name=daterangepicker_start]").is(":focus") ? this.container.find("input[name=daterangepicker_start]").val(n.format(this.locale.format)) : this.endDate || this.container.find("input[name=daterangepicker_end]").is(":focus") || this.container.find("input[name=daterangepicker_end]").val(n.format(this.locale.format));
                var d = this.leftCalendar,
                    o = this.rightCalendar,
                    r = this.startDate;
                this.endDate || this.container.find(".calendar td").each(function(e, a) {
                    if (!t(a).hasClass("week")) {
                        var s = t(a).attr("data-title"),
                            i = s.substr(1, 1),
                            l = s.substr(3, 1),
                            h = t(a).parents(".calendar"),
                            c = h.hasClass("left") ? d.calendar[i][l] : o.calendar[i][l];
                        c.isAfter(r) && c.isBefore(n) || c.isSame(n, "day") ? t(a).addClass("in-range") : t(a).removeClass("in-range")
                    }
                })
            }
        },
        clickDate: function(e) {
            if (t(e.target).hasClass("available")) {
                var a = t(e.target).attr("data-title"),
                    s = a.substr(1, 1),
                    i = a.substr(3, 1),
                    l = t(e.target).parents(".calendar"),
                    n = l.hasClass("left") ? this.leftCalendar.calendar[s][i] : this.rightCalendar.calendar[s][i];
                if (this.endDate || n.isBefore(this.startDate, "day")) {
                    if (this.timePicker) {
                        var d = parseInt(this.container.find(".left .hourselect").val(), 10);
                        if (!this.timePicker24Hour) {
                            var o = this.container.find(".left .ampmselect").val();
                            "PM" === o && d < 12 && (d += 12), "AM" === o && 12 === d && (d = 0)
                        }
                        var r = parseInt(this.container.find(".left .minuteselect").val(), 10),
                            h = this.timePickerSeconds ? parseInt(this.container.find(".left .secondselect").val(), 10) : 0;
                        n = n.clone().hour(d).minute(r).second(h)
                    }
                    this.endDate = null, this.setStartDate(n.clone())
                } else if (!this.endDate && n.isBefore(this.startDate)) this.setEndDate(this.startDate.clone());
                else {
                    if (this.timePicker) {
                        var d = parseInt(this.container.find(".right .hourselect").val(), 10);
                        if (!this.timePicker24Hour) {
                            var o = this.container.find(".right .ampmselect").val();
                            "PM" === o && d < 12 && (d += 12), "AM" === o && 12 === d && (d = 0)
                        }
                        var r = parseInt(this.container.find(".right .minuteselect").val(), 10),
                            h = this.timePickerSeconds ? parseInt(this.container.find(".right .secondselect").val(), 10) : 0;
                        n = n.clone().hour(d).minute(r).second(h)
                    }
                    this.setEndDate(n.clone()), this.autoApply && (this.calculateChosenLabel(), this.clickApply())
                }
                this.singleDatePicker && (this.setEndDate(this.startDate), this.timePicker || this.clickApply()), this.updateView(), e.stopPropagation()
            }
        },
        calculateChosenLabel: function() {
            var e = !0,
                t = 0;
            for (var a in this.ranges) {
                if (this.timePicker) {
                    if (this.startDate.isSame(this.ranges[a][0]) && this.endDate.isSame(this.ranges[a][1])) {
                        e = !1, this.chosenLabel = this.container.find(".ranges li:eq(" + t + ")").addClass("active").html();
                        break
                    }
                } else if (this.startDate.format("YYYY-MM-DD") == this.ranges[a][0].format("YYYY-MM-DD") && this.endDate.format("YYYY-MM-DD") == this.ranges[a][1].format("YYYY-MM-DD")) {
                    e = !1, this.chosenLabel = this.container.find(".ranges li:eq(" + t + ")").addClass("active").html();
                    break
                }
                t++
            }
            e && (this.showCustomRangeLabel ? this.chosenLabel = this.container.find(".ranges li:last").addClass("active").html() : this.chosenLabel = null, this.showCalendars())
        },
        clickApply: function(e) {
            this.hide(), this.element.trigger("apply.daterangepicker", this)
        },
        clickCancel: function(e) {
            this.startDate = this.oldStartDate, this.endDate = this.oldEndDate, this.hide(), this.element.trigger("cancel.daterangepicker", this)
        },
        monthOrYearChanged: function(e) {
            var a = t(e.target).closest(".calendar").hasClass("left"),
                s = a ? "left" : "right",
                i = this.container.find(".calendar." + s),
                l = parseInt(i.find(".monthselect").val(), 10),
                n = i.find(".yearselect").val();
            a || (n < this.startDate.year() || n == this.startDate.year() && l < this.startDate.month()) && (l = this.startDate.month(), n = this.startDate.year()), this.minDate && (n < this.minDate.year() || n == this.minDate.year() && l < this.minDate.month()) && (l = this.minDate.month(), n = this.minDate.year()), this.maxDate && (n > this.maxDate.year() || n == this.maxDate.year() && l > this.maxDate.month()) && (l = this.maxDate.month(), n = this.maxDate.year()), a ? (this.leftCalendar.month.month(l).year(n), this.linkedCalendars && (this.rightCalendar.month = this.leftCalendar.month.clone().add(1, "month"))) : (this.rightCalendar.month.month(l).year(n), this.linkedCalendars && (this.leftCalendar.month = this.rightCalendar.month.clone().subtract(1, "month"))), this.updateCalendars()
        },
        timeChanged: function(e) {
            var a = t(e.target).closest(".calendar"),
                s = a.hasClass("left"),
                i = parseInt(a.find(".hourselect").val(), 10),
                l = parseInt(a.find(".minuteselect").val(), 10),
                n = this.timePickerSeconds ? parseInt(a.find(".secondselect").val(), 10) : 0;
            if (!this.timePicker24Hour) {
                var d = a.find(".ampmselect").val();
                "PM" === d && i < 12 && (i += 12),
                    "AM" === d && 12 === i && (i = 0)
            }
            if (s) {
                var o = this.startDate.clone();
                o.hour(i), o.minute(l), o.second(n), this.setStartDate(o), this.singleDatePicker ? this.endDate = this.startDate.clone() : this.endDate && this.endDate.format("YYYY-MM-DD") == o.format("YYYY-MM-DD") && this.endDate.isBefore(o) && this.setEndDate(o.clone())
            } else if (this.endDate) {
                var r = this.endDate.clone();
                r.hour(i), r.minute(l), r.second(n), this.setEndDate(r)
            }
            this.updateCalendars(), this.updateFormInputs(), this.renderTimePicker("left"), this.renderTimePicker("right")
        },
        formInputsChanged: function(a) {
            var s = t(a.target).closest(".calendar").hasClass("right"),
                i = e(this.container.find('input[name="daterangepicker_start"]').val(), this.locale.format),
                l = e(this.container.find('input[name="daterangepicker_end"]').val(), this.locale.format);
            i.isValid() && l.isValid() && (s && l.isBefore(i) && (i = l.clone()), this.setStartDate(i), this.setEndDate(l), s ? this.container.find('input[name="daterangepicker_start"]').val(this.startDate.format(this.locale.format)) : this.container.find('input[name="daterangepicker_end"]').val(this.endDate.format(this.locale.format))), this.updateView()
        },
        formInputsFocused: function(e) {
            this.container.find('input[name="daterangepicker_start"], input[name="daterangepicker_end"]').removeClass("active"), t(e.target).addClass("active"), t(e.target).closest(".calendar").hasClass("right") && (this.endDate = null, this.setStartDate(this.startDate.clone()), this.updateView())
        },
        formInputsBlurred: function(t) {
            if (!this.endDate) {
                var a = this.container.find('input[name="daterangepicker_end"]').val(),
                    s = e(a, this.locale.format);
                s.isValid() && (this.setEndDate(s), this.updateView())
            }
        },
        elementChanged: function() {
            if (this.element.is("input") && this.element.val().length && !(this.element.val().length < this.locale.format.length)) {
                var t = this.element.val().split(this.locale.separator),
                    a = null,
                    s = null;
                2 === t.length && (a = e(t[0], this.locale.format), s = e(t[1], this.locale.format)), (this.singleDatePicker || null === a || null === s) && (a = e(this.element.val(), this.locale.format), s = a), a.isValid() && s.isValid() && (this.setStartDate(a), this.setEndDate(s), this.updateView())
            }
        },
        keydown: function(e) {
            9 !== e.keyCode && 13 !== e.keyCode || this.hide()
        },
        updateElement: function() {
            this.element.is("input") && !this.singleDatePicker && this.autoUpdateInput ? (this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format)), this.element.trigger("change")) : this.element.is("input") && this.autoUpdateInput && (this.element.val(this.startDate.format(this.locale.format)), this.element.trigger("change"))
        },
        remove: function() {
            this.container.remove(), this.element.off(".daterangepicker"), this.element.removeData()
        }
    }, t.fn.daterangepicker = function(e, s) {
        return this.each(function() {
            var i = t(this);
            i.data("daterangepicker") && i.data("daterangepicker").remove(), i.data("daterangepicker", new a(i, e, s))
        }), this
    }, a
}),
function() {
    function e(e) {
        if (-1 !== e.indexOf("?")) {
            var t = e.split("?");
            p = t[0], t[1] = t[1].split("&");
            for (var a = 0; a < t[1].length; a++) {
                var s = t[1][a].split("=");
                b[s[0]] = s[1]
            }
        } else p = e
    }

    function t(e) {
        D.table.append(e)
    }

    function a(e) {
        return v.replace(":DETAILS:", '<a href="#' + e.id + '" class="log-details">' + g.details + "</a>").replace(":SPAM:", u && e.m ? " | " + (e.s ? '<a href="#" class="feedback-action-spam" data-id="' + e.id + '" id="f' + e.id + '">' + g.spam + "</a>" : '<a href="#" class="feedback-action-not-spam" data-id="' + e.id + '" id="f' + e.id + '">' + g.not_spam + "</a>") : "").replace(":DELETE:", u && e.m ? ' | <a href="#" class="feedback-delete" data-id="' + e.id + '">' + g.delete + "</a>" : "").replace(":BLACKLISTS:", u ? ' | <a class="bl_link" data-ip="' + e.i + '" href="/my/show_private?service_id=' + e.sid + '&add_record=' + e.e + ',' + e.i + '&service_type=antispam' + '">' + g.personal_blacklists + "</a>" : "")
    }

    function s(e) {
        return k.replace(":FREQUENCY:", e.q)
        .replace(":FREQUENCY_HINT:", g.frequency_hint)
        .replaceAll(":EMAIL_FREQUENCY:", e.eq)
        .replaceAll(":IP_FREQUENCY:", e.iq)
        .replaceAll(":EMAIL:", e.e)
        .replaceAll(":IP:", e.i)
        .replaceAll(":REQUEST_ID:", e.id)
        .replace(":DATETIME:", e.dt)
        .replace(":SHORT_RESULT:", e.a && e.m ? '<div class="text-success">' + e.r + "</div>" : '<div class="text-danger">' + e.r + "</div>")
        .replace(":VISIBLE_HOSTNAME:", e.h ? '<div class="text-muted">' + e.h + "</div>" : "")
        .replace(":FEEDBACK:", e.f ? '<div class="text-muted" id="feedback_' + e.id + '">' + e.f + "</div>" : '<div class="text-muted hidden" id="feedback_' + e.id + '"></div>')
        .replace(":SENDER_NICKNAME:", e.n ? '<div class="nickname">' + e.n + "</div>" : "")
        .replace(":SENDER_EMAIL:", e.e && "******" != e.e ? '<a href="?ipemailnick=' + e.e + '" class="email">' + e.e + '</a> <a href="/blacklists/' + e.e + '" title="' + e.e + '" target="_blank" class="external"></a>' : e.e ? '<span class="email">' + e.e + "</span>" : "")
        .replace(":EMAIL_EXISTS:", e.ne ? '<a class="text-danger" href="/blacklists/' + e.e + '" target="_blank">Fake email</a>' : '')
        .replace(":SENDER_IP:", e.i ? (e.c ? ' <img src="/images/flags/' + e.c.toLowerCase() + '.png">' : "") + ' <a href="?ipemailnick=' + e.i + '">' + e.i + '</a> <a href="/blacklists/' + e.i + '" title="' + e.i + '" target="_blank" class="external"></a>' : "")
        .replace(":IP:", e.i || "")
        .replace(":APPROVED:", e.s ? "1" : "0")
        .replace(":MENU:", a(e))
    }

    function i(e) {
        $("#log-details-modal-info").addClass("hidden"), $("#log-details-modal-info tr").addClass("hidden"), $("#log-details-modal-text").addClass("hidden"), $("#log-details-modal-texts").addClass("hidden"), $("#log-details-modal-api").addClass("hidden"), $("#log-details-modal-error").addClass("hidden"), $("#log-details-modal-not-spam").addClass("hidden"), $("#log-details-modal-spam").addClass("hidden"), $("#log-details-modal-feedback-message").addClass("hidden"), $("#log-details-modal-feedback-notice").addClass("hidden"), $("#log-details-modal-thanks").addClass("hidden"), $("#log-details-modal-notification").addClass("hidden"), $("#log-details-modal-loading").removeClass("hidden"), $("#log-details-modal").modal(), AJAX.get(p, {
            details: e.substr(1)
        }, function(e) {
            $("#log-details-modal-loading").addClass("hidden");
            try {
                if (e = JSON.parse(e), $("#log-details-modal-info-status").removeClass("hidden"), $("#log-details-modal-info-status td").removeClass("text-danger").removeClass("text-success").text(e.short_result), $("#log-details-modal-api").removeClass("text-danger").removeClass("bg-danger").removeClass("text-success").removeClass("bg-success"), e.allow && e.moderate ? ($("#log-details-modal-info-status td").addClass("text-success"), $("#log-details-modal-api").addClass("text-success").addClass("bg-success")) : ($("#log-details-modal-info-status td").addClass("text-danger"), $("#log-details-modal-api").addClass("text-danger").addClass("bg-danger")), e.feedback && $("#log-details-modal-info-status td").html($("#log-details-modal-info-status td").text() + ' <span class="text-muted">(' + e.feedback + ")</span>"), $("#log-details-modal-info-info").removeClass("hidden"), e.datetime ? $("#log-details-modal-info-info .datetime").removeClass("hidden").text(e.datetime) : $("#log-details-modal-info-info .datetime").addClass("hidden"), e.nickname ? $("#log-details-modal-info-info .nickname").removeClass("hidden").text(e.nickname) : $("#log-details-modal-info-info .nickname").addClass("hidden"), e.email ? $("#log-details-modal-info-info .email").removeClass("hidden").text(e.email) : $("#log-details-modal-info-info .email").addClass("hidden"), e.ip ? ($("#log-details-modal-info-info .ip").removeClass("hidden"), $("#log-details-modal-info-info .ip span").text(e.ip), e.country ? $("#log-details-modal-info-info .ip img").removeClass("hidden").attr("src", "/images/flags/" + e.country.toLowerCase() + ".png") : $("#log-details-modal-info-info .ip img").addClass("hidden")) : $("#log-details-modal-info-info .ip").addClass("hidden"), $("#log-details-modal-info-id").removeClass("hidden"), $("#log-details-modal-info-id a").text(e.id).attr("href", "/my/show_requests?request_id=" + e.id), e.page_url && ($("#log-details-modal-info-url").removeClass("hidden"), $("#log-details-modal-info-url a").text(e.page_url_display).attr("href", e.page_url)), e.referrer && ($("#log-details-modal-info-referrer").removeClass("hidden"), $("#log-details-modal-info-referrer a").text(e.referrer_display).attr("href", e.referrer)), $("#log-details-modal-info").removeClass("hidden"), e.message && !e.message_array && ($("#log-details-modal-text span").html(e.message), $("#log-details-modal-text").removeClass("hidden")), e.message_array) {
                    var t = [];
                    for (var a in e.message_array) t.push("<tr><th>" + a + "</th>"), t.push("<td>" + e.message_array[a] + "</td></tr>");
                    $("#log-details-modal-texts tr").remove(), $("#log-details-modal-texts tbody").append(t.join()), $("#log-details-modal-texts").removeClass("hidden")
                }
                e.comment && ($("#log-details-modal-api").text(e.comment_server), $("#log-details-modal-api").removeClass("hidden")), e.show_report_spam ? $("#log-details-modal-spam").removeClass("hidden").attr("data-id", e.id) : $("#log-details-modal-not-spam").removeClass("hidden").attr("data-id", e.id)
            } catch (e) {
                $("#log-details-modal-error").removeClass("hidden")
            }
        }, function() {
            $("#log-details-modal-error").removeClass("hidden")
        })
    }

    function l(e, t, a, s) {
        AJAX.get("/my/ajax", {
            request_id: e,
            approve: t,
            action: "request_feedback"
        }, function(e) {
            try {
                e = JSON.parse(e), a(e)
            } catch (e) {
                s()
            }
        }, s)
    }

    function n(e, t, a) {
        var s = [];
        $(".r-check:checked").each(function() {
            s.push($(this).val())
        }), AJAX.post("/my/ajax", {
            action: "request_feedback_bulk",
            approve: e,
            requests: s.join(",")
        }, function(e) {
            try {
                e = JSON.parse(e);
                for (var s = 0; s < e.requests.length; s++) c(e.requests[s], e.approve);
                t(e)
            } catch (e) {
                a()
            }
        }, a)
    }

    function d(e, t, a, s) {
        AJAX.get("/my/ajax", {
            request_id: e,
            notice_text: t,
            action: "save_notice"
        }, a, s)
    }

    function o(e) {
        e.preventDefault(), $("#log-feedback-modal-error").addClass("hidden"), $("#log-feedback-modal-thanks").addClass("hidden"), $("#log-feedback-modal-feedback-message").addClass("hidden"), $("#log-feedback-modal-feedback-notice").addClass("hidden"), $("#log-feedback-modal-loading").removeClass("hidden"), $("#log-feedback-modal").modal();
        var t = $(this).data("id");
        l(t, 1, function(e) {
            $("#log-feedback-modal-loading").addClass("hidden"), $("#log-feedback-modal-feedback-message").text(e.message).removeClass("hidden"), $("#log-feedback-modal-feedback-save").attr("data-id", t), $("#log-feedback-modal-feedback-notice p").removeClass("text-danger").addClass("help-block"), $("#log-feedback-modal-feedback-notice").removeClass("hidden"), c(t, !0)
        }, function() {
            $("#log-feedback-modal-loading").addClass("hidden"), $("#log-feedback-modal-error").removeClass("hidden")
        })
    }

    function r(e) {
        e.preventDefault(), $("#log-feedback-modal-error").addClass("hidden"), $("#log-feedback-modal-thanks").addClass("hidden"), $("#log-feedback-modal-feedback-message").addClass("hidden"), $("#log-feedback-modal-feedback-notice").addClass("hidden"), $("#log-feedback-modal-loading").removeClass("hidden"), $("#log-feedback-modal").modal();
        var t = $(this).data("id");
        l(t, 0, function(e) {
            $("#log-feedback-modal-loading").addClass("hidden"), $("#log-feedback-modal-feedback-message").text(e.message).removeClass("hidden"), $("#log-feedback-modal-feedback-save").attr("data-id", t), $("#log-feedback-modal-feedback-notice p").removeClass("text-danger").addClass("help-block"), $("#log-feedback-modal-feedback-notice").removeClass("hidden"), c(t, !1)
        }, function() {
            $("#log-feedback-modal-loading").addClass("hidden"), $("#log-feedback-modal-error").removeClass("hidden")
        })
    }
    var items_per_page = $('#items-per-page option:selected').val();
    function h() {
        b.items_per_page = $('#items-per-page option:selected').val();        
        var current_page = getParameterByName('current_page');
        if(current_page && b.items_per_page==items_per_page){
            b.current_page  = current_page;
        }else{
            b.current_page  = 1;
        }
        items_per_page=b.items_per_page;
        AJAX.get(p, b, function(e) {
            try {
                e = JSON.parse(e)
            } catch (e) {
                return D.table.find("tr").remove(), void t('<tr><td colspan="5" class="text-center text-danger">Internal Server Error</td></tr>')
            }
            C = e.d.length;
            var a = "";
            $('#current-page').text(e.page);
            $('#total-pages').text(e.total_pages);
            $('.pagination').html('');
            if(e.pages && e.pages.length>1){
                page = e.page;                
                if(page=='1'){
                    $('.pagination').append('<li class="disabled"><span aria-hidden="true">«</span></li>');
                }else if(page=='2'){
                    $('.pagination').append('<li><a href="'+f()+'"><span aria-hidden="true">«</span></a></li>');
                }else{
                    $('.pagination').append('<li><a href="'+f()+'&current_page='+(parseInt(page)-1)+'"><span aria-hidden="true">«</span></a></li>');
                }
                var last = 0;
                e.pages.forEach(function(e) {
                    if(page==e){
                        $('.pagination').append('<li class="active"><span>'+e+'</span></li>');
                    }else{
                        if(e==1){
                            $('.pagination').append('<li><a href="'+f()+'">'+e+'</a></li>');
                        }else{
                            $('.pagination').append('<li><a href="'+f()+'&current_page='+e+'">'+e+'</a></li>');
                        }
                    }
                    last = e;
                });
                if(page==e.total_pages){
                    $('.pagination').append('<li class="disabled"><span aria-hidden="true">»</span></li>');
                }else{
                    $('.pagination').append('<li><a href="'+f()+'&current_page='+(parseInt(page)+1)+'"><span aria-hidden="true">»</span></a></li>');
                }
                
            }
            e.d.forEach(function(e) {
                a += s(e)
            }), e.d.length && ($("#records-found").text(e.records_found), $("#export-csv").removeClass("hidden"), $(".bulk-row").removeClass("hidden")), $("#records-found").removeClass("hidden"), D.table.find("tr").remove(), t(a), $("a.log-details").click(function(e) {
                e.preventDefault(), i($(this).attr("href"))
            }), $(".feedback-action-not-spam").click(o), $(".feedback-action-spam").click(r), $(".feedback-delete").click(function(e) {
                e.preventDefault(), $("#log-delete-modal-delete").attr("data-id", $(this).data("id")), $("#log-delete-modal-message").removeClass("hidden"), $("#log-delete-modal-loading").addClass("hidden"), $("#log-delete-modal-error").addClass("hidden"), $("#log-delete-modal-delete").removeClass("disabled"), $("#log-delete-modal").modal()
            }), $(".r-check").change(m), $("#bulk-action-top").change(function() {
                var e = $(this).find("option:selected").val();
                $("#bulk-action-bottom").selectpicker("val", e), m()
            }), $("#bulk-action-bottom").change(function() {
                var e = $(this).find("option:selected").val();
                $("#bulk-action-top").selectpicker("val", e), m()
            }), $(".bulk-check").change(function() {
                $(this).prop("checked") ? ($(".bulk-check").prop("checked", !0), $(".r-check").prop("checked", !0)) : ($(".r-check").prop("checked", !1), $(".bulk-check").prop("checked", !1)), m()
            })
        }, function() {
            D.table.find("tr").remove(), t('<tr><td colspan="5" class="text-center text-danger">Internal Server Error</td></tr>')
        })
    }

    function c(e, t) {
        var a = $("#log-details-modal-info-status td").text();
        t ? (a = a.replace(" (" + g.reported_as_spam + ")", ""), a += ' <span class="text-muted">(' + g.reported_as_not_spam + ")</span>", $("#feedback_" + e).text(g.reported_as_not_spam).removeClass("hidden"), $("#f" + e).off().text(g.spam).click(r)) : (a = a.replace(" (" + g.reported_as_not_spam + ")", ""), a += ' <span class="text-muted">(' + g.reported_as_spam + ")</span>", $("#feedback_" + e).text(g.reported_as_spam).removeClass("hidden"), $("#f" + e).off().text(g.not_spam).click(o)), $("#log-details-modal-info-status td").html(a)
    }

    function m() {
        $(".r-check:checked").length == C ? $(".bulk-check").prop("checked", !0) : $(".bulk-check").prop("checked", !1), $(".r-check:checked").length && $("#bulk-action-top").val() ? $(".bulk-btn").removeClass("disabled") : $(".bulk-btn").addClass("disabled")
    }

    function f() {
        var e = {};
        e.start_from = $("#customdates").data("daterangepicker").startDate.format("YYYY-MM-DD"), e.end_to = $("#customdates").data("daterangepicker").endDate.format("YYYY-MM-DD"), "" !== $("#statuses").val() && (e.allow = $("#statuses").val()), $("#services").val() && (e.service_id = $("#services").val()), $("#countries").val() && (e.country = $("#countries").val()), $("#ipemailnick").val() && (e.ipemailnick = $("#ipemailnick").val());
        var t = [];
        for (var a in e) e.hasOwnProperty(a) && t.push(encodeURIComponent(a) + "=" + encodeURIComponent(e[a]));
        return p + "?" + t.join("&")
    }
    var p, u, g, k = '<tr class="log-row" id="r:REQUEST_ID:"><td><input type="checkbox" value=":REQUEST_ID:" class="r-check" data-approved=":APPROVED:" name="rid[]"></td><td class="nw">:DATETIME:</td><td class="nw">:SHORT_RESULT::VISIBLE_HOSTNAME::FEEDBACK:</td><td class="br">:SENDER_NICKNAME::SENDER_EMAIL::EMAIL_EXISTS::SENDER_IP::MENU:</td><td><span title=":FREQUENCY_HINT:">:FREQUENCY:</span><br><span class="text-muted">IP: <a href="/blacklists/:IP:" title=":IP:" target="_blank">:IP_FREQUENCY:</a></span><br><span class="text-muted">E-mail: <a href="/blacklists/:EMAIL:" title=":EMAIL:" target="_blank">:EMAIL_FREQUENCY:</a></span></td></tr>',
        v = '<div class="text-muted">:DETAILS::SPAM::DELETE::BLACKLISTS:</div>',
        b = {
            is_ajax: "1"
        },
        C = 0,
        D = {
            table: $("#log-table tbody")
        };
    window.antiSpamLog = function(t) {
        u = t.grantwrite, g = t.langs, e(t.url), $("#log-details-modal-not-spam").click(function() {
                var e = $(this);
                e.hasClass("disabled") || (e.addClass("disabled"), $("#log-details-modal-feedback-message").addClass("hidden"), $("#log-details-modal-feedback-notice").addClass("hidden"), $("#log-details-modal-thanks").addClass("hidden"), $("#log-details-modal-notification").addClass("hidden"), $("#log-details-modal-feedback-notice p").removeClass("text-danger").addClass("help-block").text("Leave a notice about this issue to help us resolve it faster"), l(e.data("id"), 1, function(t) {
                    e.removeClass("disabled"), $("#log-details-modal-not-spam").addClass("hidden"), $("#log-details-modal-spam").removeClass("hidden").attr("data-id", e.data("id")), $("#log-details-modal-feedback-save").attr("data-id", e.data("id")), $("#log-details-modal-error").addClass("hidden"), $("#log-details-modal-text").addClass("hidden"), $("#log-details-modal-api").addClass("hidden"), $("#log-details-modal-feedback-message").text(t.message).removeClass("hidden"), $("#log-details-modal-feedback-notice p").removeClass("text-danger").addClass("help-block"), $("#log-details-modal-feedback-notice").removeClass("hidden"), t.notification && $("#log-details-modal-notification").html(t.notification).removeClass("hidden"), c(e.data("id"), !0)
                }, function() {
                    $("#log-details-modal-error").removeClass("hidden"), e.removeClass("disabled")
                }))
            }), $("#log-details-modal-spam").click(function() {
                var e = $(this);
                e.hasClass("disabled") || (e.addClass("disabled"), $("#log-details-modal-feedback-message").addClass("hidden"), $("#log-details-modal-feedback-notice").addClass("hidden"), $("#log-details-modal-thanks").addClass("hidden"), $("#log-details-modal-notification").addClass("hidden"), $("#log-details-modal-feedback-notice p").removeClass("text-danger").addClass("help-block").text("Leave a notice about this issue to help us resolve it faster"), l(e.data("id"), 0, function(t) {
                    e.removeClass("disabled"), $("#log-details-modal-spam").addClass("hidden"), $("#log-details-modal-not-spam").removeClass("hidden").attr("data-id", e.data("id")), $("#log-details-modal-feedback-save").attr("data-id", e.data("id")), $("#log-details-modal-error").addClass("hidden"), $("#log-details-modal-text").addClass("hidden"), $("#log-details-modal-api").addClass("hidden"), $("#log-details-modal-feedback-message").text(t.message).removeClass("hidden"), $("#log-details-modal-feedback-notice p").removeClass("text-danger").addClass("help-block"), $("#log-details-modal-feedback-notice").removeClass("hidden"), t.notification && $("#log-details-modal-notification").html(t.notification).removeClass("hidden"), c(e.data("id"), !1)
                }, function() {
                    $("#log-details-modal-error").removeClass("hidden"), e.removeClass("disabled")
                }))
            }), $("#log-details-modal-feedback-save").click(function() {
                var e = $(this);
                e.hasClass("disabled") || (e.addClass("disabled"), d(e.data("id"), $("#feedback-notice").val(), function() {
                    $("#log-details-modal-feedback-message").addClass("hidden"), $("#log-details-modal-feedback-notice").addClass("hidden"), $("#log-details-modal-thanks").removeClass("hidden")
                }, function() {
                    $("#log-details-modal-feedback-notice p").text("Internal Server Error").addClass("text-danger").removeClass("help-block")
                }))
            }), $("#log-feedback-modal-feedback-save").click(function() {
                var e = $(this);
                e.hasClass("disabled") || (e.addClass("disabled"), d(e.data("id"), $("#feedback-notice2").val(), function() {
                    $("#log-feedback-modal-feedback-message").addClass("hidden"), $("#log-feedback-modal-feedback-notice").addClass("hidden"), $("#log-feedback-modal-thanks").removeClass("hidden")
                }, function() {
                    $("#log-feedback-modal-feedback-notice p").text("Internal Server Error").addClass("text-danger").removeClass("help-block")
                }))
            }), $("#log-delete-modal-delete").click(function() {
                if (!$(this).hasClass("disabled")) {
                    $(this).addClass("disabled"), $("#log-delete-modal-message").addClass("hidden"), $("#log-delete-modal-loading").removeClass("hidden");
                    var e = $(this).data("id");
                    AJAX.get("/my/ajax", {
                        request_id: e,
                        action: "delete_request"
                    }, function() {
                        $("#r" + e).remove(), C--, $("#log-delete-modal").modal("hide")
                    }, function() {
                        $("#log-delete-modal-error").removeClass("hidden"), $("#log-delete-modal-loading").addClass("hidden")
                    })
                }
            }), $(".bulk-btn").click(function() {
                var baction = $("#bulk-action-top").val();
                
                if( baction=='deny' || baction=='allow' ){
                    if($(this).hasClass("disabled")){
                        return;
                    }
                    $('.bulk-btn').addClass("disabled");
                    var add_record = [];
                    $('.r-check:checked').each(function(){
                        add_record.push($('#r'+$(this).val()+' .bl_link').data('ip'));
                        
                    });
                    if(add_record.length){
                        
                        $("#log-bulk-modal-error").addClass("hidden");
                        $("#log-bulk-modal-text").addClass("hidden");
                        $("#log-bulk-modal-apply").addClass("hidden");
                        $("#log-bulk-modal-cancel").addClass("hidden");
                        $("#log-bulk-modal-result").addClass("hidden");
                        $("#log-bulk-modal-loading").removeClass("hidden");
                        $("#log-bulk-modal-close").removeClass("hidden");

                        $('#log-bulk-modal').modal('show');
                        $.ajax({
                            method: "POST",
                            url: "/my/show_private?service_type=antispam",
                            data: {add_record: add_record.join(','), service_id: "all", action: "add_record", add_status: baction, ajax: 1},
                            dataType: "json"
                        }).done(function(data) {
                            $("#log-bulk-modal-loading").addClass("hidden");
                            if(data){
                                var msg = Object.values(data);
                                $("#log-bulk-modal-result").html(msg.join('<br>') + "<br><br>Your Personal lists is <a href='/my/show_private?service_type=antispam'>here</a>.").removeClass("hidden");
                            }else{
                                $("#log-bulk-modal-error").removeClass("hidden");
                            }
                        }).error(function() {
                            $("#log-bulk-modal-loading").addClass("hidden");
                            $("#log-bulk-modal-error").removeClass("hidden");
                        });
                    }
                    return;
                }
                if(!$(this).hasClass("disabled") && baction=='export'){
                    $('#log-form').attr('action',window.location.href + '&mode=csv');
                    $('#log-form').submit();
                    return;
                }
                $(this).hasClass("disabled") || ($("#log-bulk-modal-text").text(g.confirm[$("#bulk-action-top").val()]).removeClass("hidden"), $("#log-bulk-modal-result").addClass("hidden"), $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-error").addClass("hidden"), $("#log-bulk-modal-apply").removeClass("disabled").removeClass("hidden"), $("#log-bulk-modal-cancel").removeClass("hidden"), $("#log-bulk-modal-close").addClass("hidden"), $("#log-bulk-modal").modal())
            }), $(".bulk-approved-btn").click(function() {
                $(".r-check:checked").prop("checked", !1), $(".bulk-check").prop("checked", !1), $(".r-check").each(function() {
                    $(this).data("approved") && $(this).prop("checked", !0)
                })
            }), $("#log-bulk-modal-apply").click(function() {
                if ($(this).blur(), !$(this).hasClass("disabled")) switch ($(this).addClass("disabled"), $("#log-bulk-modal-text").addClass("hidden"), $("#log-bulk-modal-loading").removeClass("hidden"), $("#bulk-action-top").val()) {
                    case "spam":
                        n(0, function(e) {
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-result").text(e.requests.length + " records has been marked as not spam.").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden"), $(".r-check:checked").prop("checked", !1), $(".bulk-check").prop("checked", !1)
                        }, function() {
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-error").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden")
                        });
                        break;
                    case "not_spam":
                        n(1, function(e) {
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-result").text(e.requests.length + " records has been marked as spam.").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden"), $(".r-check:checked").prop("checked", !1), $(".bulk-check").prop("checked", !1)
                        }, function() {
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-error").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden")
                        });
                        break;
                    case "delete":
                        var e = [];
                        $(".r-check:checked").each(function() {
                            e.push($(this).val())
                        }), AJAX.post("/my/ajax", {
                            action: "delete_request_bulk",
                            requests: e
                        }, function(e) {
                            try {
                                e = JSON.parse(e);
                                for (var t = 0; t < e.length; t++) $("#r" + e[t]).remove(), C--;
                                $(".r-check:checked").prop("checked", !1), $(".bulk-check").prop("checked", !1)
                            } catch (e) {
                                return $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-error").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), void $("#log-bulk-modal-close").removeClass("hidden")
                            }
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-result").text(e.length + " records has deleted.").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden")
                        }, function() {
                            $("#log-bulk-modal-loading").addClass("hidden"), $("#log-bulk-modal-error").removeClass("hidden"), $("#log-bulk-modal-apply").addClass("hidden"), $("#log-bulk-modal-cancel").addClass("hidden"), $("#log-bulk-modal-close").removeClass("hidden")
                        })
                }
            }), $("#customdates").daterangepicker({
                locale: {
                    format: "MMM DD, YYYY"
                },
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                    "Last 7 Days": [moment().subtract(7, "days"), moment()],
                    "Last 30 Days": [moment().subtract(30, "days"), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                }
            }),
            $("#filter-btn").click(function() {
                $(this).hasClass("disabled") || ($(this).addClass("disabled"),
                    location.href = f())
            }), $("#ipemailnick").keypress(function(e) {
                13 == e.keyCode && $("#filter-btn").trigger("click")
            }), $("button.filter").click(function() {
                $(this).hasClass("primary") || $(this).hasClass("disabled") || ($(this).addClass("disabled"), location.href = p + "?int=" + $(this).attr("id"))
            }), $("#export-csv a").click(function(e) {
                e.preventDefault(), $("#page-loader").removeClass("hidden"), $.fileDownload($(this).prop("href")).done(function() {
                    $("#page-loader").addClass("hidden")
                }).fail(function() {
                    $("#page-loader").addClass("hidden")
                })
            }), h(),
            $('.pagination').on('click','li a',function(e){
                e.preventDefault();
                if(!$(this).hasClass("disabled")){
                    $(this).addClass('disabled');
                    history.pushState(false,false,$(this).attr('href'));
                    $('#log-table tbody').html('<tr><td colspan="5" class="text-center"><img src="/images/loading.gif"></td></tr>');
                    h();

                }
            });
            $('#items-per-page').change(function(e){
                $('#log-table tbody').html('<tr><td colspan="5" class="text-center"><img src="/images/loading.gif"></td></tr>');
                $('.pagination').html('');
                h();
                history.pushState(false,false,f());
            });
            
    }
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
}();